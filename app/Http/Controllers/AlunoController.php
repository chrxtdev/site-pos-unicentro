<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $processoId = $request->input('processo_seletivo_id') ?? $request->input('processo');

        $alunos = Signin::when($search, function ($query) use ($search) {
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->when($processoId, function ($query) use ($processoId) {
            // Como signins armazena o nome da pos em string, buscamos as ofertas do processo
            $cursosDoProcesso = \App\Models\Oferta::where('processo_seletivo_id', $processoId)
                ->with('curso')
                ->get()
                ->pluck('curso.nome')
                ->toArray();
            
            $query->whereIn('pos_graduacao', $cursosDoProcesso);
        })->latest()->paginate(25)->withQueryString();

        return view('alunos.index', compact('alunos', 'search'));
    }

    public function edit($id)
    {
        $aluno = Signin::findOrFail($id);

        $boletoStatus = null;
        if ($aluno->asaas_payment_id) {
            $boletoStatus = $this->consultarStatusBoleto($aluno->asaas_payment_id);
        }

        return view('alunos.edit', compact('aluno', 'boletoStatus'));
    }

    public function update(Request $request, $id)
    {
        $aluno = Signin::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:signins,email,' . $aluno->id,
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'estado_civil' => 'required|string',
            'ensino_medio' => 'required|string',
            'cor_raca' => 'required|string',
            'nome_pai' => 'nullable|string|max:255',
            'nome_mae' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:15',
            'telefone_celular' => 'required|string|max:15',
            'tipo_aluno' => 'required|string',
            'valor_mensalidade' => 'required|numeric',
            'login' => 'required|string|max:255|unique:signins,login,' . $aluno->id,
            'senha' => 'nullable|string|min:8|confirmed',
        ]);

        $data = collect($validated)->except(['senha', 'senha_confirmation'])->toArray();

        if ($request->filled('senha')) {
            $data['senha'] = bcrypt($validated['senha']);
        }

        $aluno->update($data);

        return redirect()->route('alunos.index')->with('success', 'Dados do aluno atualizados com sucesso!');
    }

    public function reimprimirBoleto($paymentId)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => config('services.asaas.key'),
        ])->get(config('services.asaas.url') . '/payments/' . $paymentId);

        if ($response->successful()) {
            $boletoData = $response->json();
            
            // Suporte universal a Boleto, PIX e Cartão de Crédito
            $urlDePagamento = $boletoData['bankSlipUrl'] ?? $boletoData['invoiceUrl'] ?? null;
            
            if ($urlDePagamento) {
                return redirect($urlDePagamento);
            }
        }

        return redirect()->back()->with('error', 'Erro ao obter o boleto.');
    }

    private function consultarStatusBoleto(string $paymentId): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'access_token' => config('services.asaas.key'),
            ])->get(config('services.asaas.url') . '/payments/' . $paymentId);

            if ($response->successful()) {
                return strtoupper($response->json('status'));
            }

            return 'ERRO AO OBTER STATUS';
        } catch (\Exception $e) {
            return 'ERRO AO OBTER STATUS';
        }
    }
}
