<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Disciplina;
use App\Models\Atividade;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{
    public function index()
    {
        $totalAlunos = Signin::count();

        $receitaMensal = Signin::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('valor_mensalidade');

        $inscricoesHoje = Signin::whereDate('created_at', Carbon::today())->count();

        $matriculasRecentes = Signin::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAlunos',
            'receitaMensal',
            'inscricoesHoje',
            'matriculasRecentes'
        ));
    }

    public function portal()
    {
        $user = auth()->user();
        $inscricao = Signin::where('email', $user->email)->first();

        $isAdmin = $user->is_admin || $user->hasRole(['admin_master', 'financeiro', 'admin_comum', 'professor']);

        if (!$inscricao && $isAdmin) {
            $inscricao = new Signin([
                'id' => 0,
                'nome' => $user->name,
                'pos_graduacao' => 'Visualização de Admin',
                'status_pagamento' => 'pago',
                'valor_mensalidade' => 0.00,
                'forma_pagamento' => 'boleto',
            ]);
            $inscricao->created_at = now();
        } elseif (!$inscricao) {
            auth()->logout();
            return redirect()->route('inscricao.index')->withErrors(['login' => 'Nenhuma inscrição ativa encontrada para este e-mail. Por favor, inscreva-se primeiro.']);
        }

        $matriculas = collect();
        $atividades = collect();

        if ($inscricao && $inscricao->id > 0) {
            $matriculas = \App\Models\MatriculaDisciplina::where('signin_id', $inscricao->id)
                ->with(['disciplina.professor', 'notas'])
                ->get();
            
            $disciplinasIds = $matriculas->pluck('disciplina_id');
            if ($disciplinasIds->isNotEmpty()) {
                $atividades = \App\Models\Atividade::whereIn('disciplina_id', $disciplinasIds)
                    ->with(['disciplina', 'professor'])
                    ->latest()
                    ->take(20) // Limita feed de atividades a 20 recentes
                    ->get();
            }
        }

        return view('portal.dashboard', compact('inscricao', 'matriculas', 'atividades'));
    }

    public function portalFinanceiro()
    {
        $user = auth()->user();
        $inscricao = \App\Models\Signin::where('email', $user->email)->first();

        $isAdmin = $user->is_admin || $user->hasRole(['admin_master', 'financeiro', 'admin_comum', 'professor']);

        // O redirecionamento e lógica de segurança são idênticos ao principal
        if (!$inscricao && $isAdmin) {
            $inscricao = new \App\Models\Signin([
                'id' => 0,
                'nome' => $user->name,
                'pos_graduacao' => 'Visualização de Admin',
                'status_pagamento' => 'pago',
                'valor_mensalidade' => 0.00,
                'forma_pagamento' => 'boleto',
            ]);
            $inscricao->created_at = now();
        } elseif (!$inscricao) {
            auth()->logout();
            return redirect()->route('inscricao.index')->withErrors(['login' => 'Nenhuma inscrição ativa encontrada para este e-mail. Por favor, inscreva-se primeiro.']);
        }

        // Busca as faturas reais no Asaas se houver um parcelamento vinculado
        $faturas = collect([]);
        $asaasService = app(\App\Services\AsaasService::class);

        // Auto-sincronização para inscrições feitas antes da migração de IDs
        if (!$inscricao->asaas_installment_id && $inscricao->asaas_payment_id) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'access_token' => config('services.asaas.key'),
                ])->get(config('services.asaas.url') . '/payments/' . $inscricao->asaas_payment_id);
                
                if ($response->successful()) {
                    $dados = $response->json();
                    $inscricao->update([
                        'asaas_customer_id' => $dados['customer'] ?? null,
                        'asaas_installment_id' => $dados['installment'] ?? null,
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Erro ao sincronizar IDs Asaas: ' . $e->getMessage());
            }
        }

        $faturasRaw = collect([]);

        if ($inscricao->asaas_installment_id) {
            $faturasRaw = $asaasService->buscarFaturasParcelamento($inscricao->asaas_installment_id);
        } elseif ($inscricao->asaas_payment_id) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'access_token' => config('services.asaas.key'),
                ])->get(config('services.asaas.url') . '/payments/' . $inscricao->asaas_payment_id);
                
                if ($response->successful()) {
                    $faturasRaw = collect([$response->json()]);
                }
            } catch (\Exception $e) {
                // Silencioso
            }
        }
            
        // Paginação Manual (3-4 por página conforme pedido)
        $perPage = 4;
        $currentPage = Paginator::resolveCurrentPage('page') ?: 1;
        $items = $faturasRaw->forPage($currentPage, $perPage);
        
        $faturas = new LengthAwarePaginator(
            $items,
            $faturasRaw->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        // Auto-Cura (Fallback de Webhook): 
        // Se a inscrição consta como pendente, mas a API do Asaas retornou alguma fatura já paga, aprova!
        if ($inscricao->status_pagamento !== 'pago' && $faturasRaw->count() > 0) {
            $faturaPaga = $faturasRaw->firstWhere(function ($fatura) {
                return in_array($fatura['status'], ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH']);
            });

            if ($faturaPaga) {
                $inscricao->status_pagamento = 'pago';
                if (!$inscricao->matricula) {
                    $geradorService = app(\App\Services\GeradorMatriculaService::class);
                    $inscricao->matricula = $geradorService->gerarMatricula($inscricao);
                }
                $inscricao->save();
                \Illuminate\Support\Facades\Log::info('Auto-cura Asaas na aba Financeiro: Pagamento detectado como Pago.', ['signin' => $inscricao->id]);
            }
        }
        
        return view('portal.financeiro', compact('inscricao', 'faturas'));
    }

    public function portalNotas()
    {
        $user = auth()->user();
        $inscricao = \App\Models\Signin::where('email', $user->email)->first();

        if (!$inscricao && $user->is_admin) {
            $inscricao = new \App\Models\Signin([
                'id' => 0,
                'nome' => $user->name,
                'pos_graduacao' => 'Visualização de Admin',
                'status_pagamento' => 'pago',
                'valor_mensalidade' => 0.00,
                'forma_pagamento' => 'boleto',
            ]);
            $inscricao->created_at = now();
        } elseif (!$inscricao) {
            auth()->logout();
            return redirect()->route('inscricao.index')->withErrors(['login' => 'Nenhuma inscrição ativa encontrada para este e-mail. Por favor, inscreva-se primeiro.']);
        }

        $matriculas = collect();

        if ($inscricao && $inscricao->id > 0) {
            $matriculas = \App\Models\MatriculaDisciplina::where('signin_id', $inscricao->id)
                ->with(['disciplina.professor', 'notas'])
                ->orderBy('id', 'asc') // Opcional, mantem a ordem matriculada
                ->get();
        }

        return view('portal.notas', compact('inscricao', 'matriculas'));
    }

    public function impersonate($id)
    {
        $aluno = Signin::findOrFail($id);
        $user = \App\Models\User::where('email', $aluno->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Usuário de acesso não encontrado para este aluno.');
        }

        // Guarda o ID do admin atual na sessão para poder voltar depois
        session()->put('impersonator_id', \Illuminate\Support\Facades\Auth::id());

        // Faz login silencioso como o aluno respectivo
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('aluno.portal')->with('success', 'Você está acessando como ' . $user->name);
    }

    public function leaveImpersonate()
    {
        // Recupera o ID do admin que estava impersonando
        $adminId = session('impersonator_id');

        if ($adminId) {
            // Loga de volta como o admin primeiro para não perder os dados
            \Illuminate\Support\Facades\Auth::loginUsingId($adminId);

            // Limpa a sessão de impersonation
            session()->forget('impersonator_id');

            return redirect()->route('admin.dashboard')->with('success', 'Você voltou a navegar como Administrador.');
        }

        return redirect()->route('dashboard');
    }

    public function portalDocumentos()
    {
        $user = auth()->user();
        $inscricao = Signin::where('email', $user->email)->first();
        return view('portal.documentos', compact('inscricao'));
    }

    public function portalMural(Disciplina $disciplina = null)
    {
        $user = auth()->user();
        $inscricao = Signin::where('email', $user->email)->first();

        if (!$inscricao && $user->is_admin) {
            $inscricao = new Signin([
                'id' => 0,
                'nome' => $user->name,
                'pos_graduacao' => 'Visualização de Admin',
            ]);
        } elseif (!$inscricao) {
            auth()->logout();
            return redirect()->route('inscricao.index')->withErrors(['login' => 'Nenhuma inscrição.']);
        }

        // Busca matrículas do aluno para renderizar o menu lateral do mural
        $matriculas = \App\Models\MatriculaDisciplina::where('signin_id', $inscricao->id ?? 0)
            ->with('disciplina')
            ->get();
            
        // Se nenhuma matéria foi passada, seleciona a primeira como padrão
        if (!$disciplina && $matriculas->isNotEmpty()) {
            return redirect()->route('aluno.mural', $matriculas->first()->disciplina_id);
        }

        // Proteção: Garante que o aluno ou admin tenha acesso àquela matéria
        if ($disciplina && $inscricao->id > 0) {
            $temAcesso = $matriculas->contains('disciplina_id', $disciplina->id);
            if (!$temAcesso && !$user->is_admin) {
                return abort(403, 'Você não está matriculado nesta disciplina.');
            }
        }

        $avisos = collect();
        if ($disciplina) {
            $avisos = Atividade::where('disciplina_id', $disciplina->id)
                ->with('professor')
                ->latest()
                ->get();
        }

        return view('portal.mural', compact('inscricao', 'matriculas', 'disciplina', 'avisos'));
    }

    public function downloadBoletim()
    {
        $user = auth()->user();
        $inscricao = \App\Models\Signin::where('email', $user->email)->first();

        if (!$inscricao) {
            return back()->withErrors(['erro' => 'Inscrição não encontrada.']);
        }

        $matriculas = \App\Models\MatriculaDisciplina::where('signin_id', $inscricao->id)
            ->with(['disciplina.professor', 'notas'])
            ->get();

        $pdf = Pdf::loadView('portal.boletim-pdf', compact('inscricao', 'matriculas'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('boletim-escolar-' . ($inscricao->matricula ?? $inscricao->id) . '.pdf');
    }

    public function downloadMatricula()
    {
        $user = auth()->user();
        $inscricao = Signin::where('email', $user->email)->first();

        if (!$inscricao || !$inscricao->matricula) {
            return back()->withErrors(['erro' => 'Você ainda não possui um número de matrícula gerado.']);
        }

        $pdf = Pdf::loadView('portal.matricula-pdf', compact('inscricao'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('declaracao-matricula-' . $inscricao->matricula . '.pdf');
    }
}