<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Oferta;
use Illuminate\Http\Request;
use App\Models\Signin;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Rules\ValidCpf;

class InscricaoController extends Controller
{
    public function index()
    {
        // Obtenha os cursos disponíveis da tabela `cursos`
        $cursos = Curso::select('id', 'nome')->get();

        // Passe os cursos para a view
        return view('inscricao.index', compact('cursos'));
    }

    public function ofertasDisponiveis()
    {
        // Pega cursos que possuem ofertas vinculadas
        $cursos = Curso::with('ofertas')->whereHas('ofertas')->get();
        return response()->json($cursos);
    }
    public function store(Request $request, \App\Services\AsaasService $asaasService)
    {

        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => ['required', 'string', new ValidCpf, function ($attribute, $value, $fail) {
                // Validação de unicidade compatível com criptografia (não dá pra usar unique: em campo encrypted)
                $exists = Signin::all()->contains(fn ($s) => $s->cpf === $value);
                if ($exists) {
                    $fail('Este CPF já está cadastrado no sistema.');
                }
            }],
            'email' => 'required|email|max:255|unique:signins,email|unique:users,email', 
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'estado_civil' => 'required|string',
            'ensino_medio' => 'required|string',
            'cor_raca' => 'required|string',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:15',
            'telefone_celular' => 'required|string|max:15',
            'tipo_aluno' => 'required|string',
            'valor_mensalidade' => 'prohibited',
            'forma_pagamento' => 'required|string|in:boleto,pix,cartao',
            'pos_graduacao' => 'required|string',
            'oferta_id' => 'required|exists:ofertas,id',
            'login' => 'required|string|max:255|unique:signins,login',
            'senha' => 'required|string|min:8|confirmed',
        ]);

        // 1. Criação do Usuário base para acesso ao portal
        $user = \App\Models\User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => bcrypt($request->senha),
        ]);

        // 2. Buscar valor_mensalidade da Oferta no banco (blindagem contra manipulação)
        $oferta = Oferta::findOrFail($validated['oferta_id']);
        $validated['valor_mensalidade'] = (float) $oferta->valor_taxa;
        unset($validated['oferta_id']);

        // 3. Criação da Inscrição (Aluno)
        $signin = new Signin($validated);
        $signin->senha = bcrypt($validated['senha']);
        $signin->save();

        // 4. Gerar o Boleto via Service do Asaas
        $boleto = $asaasService->gerarBoletoParaInscricao($signin);

        // 5. Login Automático
        \Illuminate\Support\Facades\Auth::login($user);

        // 6. Redirecionamento (Pode ser JSON caso o form fosse via AJAX, 
        // mas como é form POST normal, retornamos redirect Laravel)
        return redirect()->route('inscricao.sucesso', ['id' => $signin->id]);
    }
    public function comprovante($id)
    {
        $inscricao = Signin::findOrFail($id);
        $this->autorizarAcesso($inscricao);

        return view('inscricao.comprovante', compact('inscricao'));
    }

    public function downloadComprovante($id)
    {
        $inscricao = Signin::findOrFail($id);
        $this->autorizarAcesso($inscricao);

        // Gera o PDF com os dados da inscrição
        $pdf = Pdf::loadView('inscricao.comprovante-pdf', compact('inscricao'))
            ->setPaper('a4', 'portate');

        // Retorna o download do PDF
        return $pdf->stream('comprovante-inscricao.pdf');
    }

    public function sucesso($id)
    {
        $inscricao = Signin::findOrFail($id);
        $this->autorizarAcesso($inscricao);
        
        // Pega a URL do boleto se existir
        $boletoUrl = null;
        if ($inscricao->asaas_payment_id) {
            // Se gerou com o BoletoController/AsaasService temos o ID.
            // O ideal seria pegar a bankSlipUrl da API do Asaas, mas como não salvamos no banco
            // Vamos redirecionar para a rota existente boleto.mostrar se houver necessidade
            // ou deixar vazio caso a interface pegue via controller de boleto. 
            // Porém o BoletoController@gerar faz redirecionamento. 
        }

        return view('inscricao.sucesso', compact('inscricao'));
    }

    private function autorizarAcesso(Signin $inscricao): void
    {
        $user = auth()->user();

        if (!$user) {
            return; // Não autenticado nesta rota (rota pública pós-inscrição)
        }

        if ($user->is_admin) {
            return;
        }

        if ($user->email !== $inscricao->email) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar este recurso.');
        }
    }
}
