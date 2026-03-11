<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use App\Models\Disciplina;
use App\Models\Atividade;
use App\Models\MatriculaDisciplina;
use App\Services\AsaasService;
use App\Services\GeradorMatriculaService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isExclusiveProfessor = $user->hasRole('professor') && $user->roles->count() == 1 && !$user->is_admin;

        if ($isExclusiveProfessor) {
            $disciplinaIds = Disciplina::where('professor_id', $user->id)->pluck('id');

            return view('admin.dashboard', [
                'isExclusiveProfessor' => true,
                'minhasDisciplinasCount' => $disciplinaIds->count(),
                'meusCursosCount' => Disciplina::where('professor_id', $user->id)->pluck('curso_id')->unique()->count(),
                'meusAlunosCount' => MatriculaDisciplina::whereIn('disciplina_id', $disciplinaIds)->count(),
            ]);
        }

        $totalAlunos = Signin::count();
        $receitaMensal = Signin::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('valor_mensalidade');
        $inscricoesHoje = Signin::whereDate('created_at', Carbon::today())->count();
        $matriculasRecentes = Signin::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalAlunos', 'receitaMensal', 'inscricoesHoje', 'matriculasRecentes', 'isExclusiveProfessor'
        ));
    }

    public function portal()
    {
        $inscricao = $this->resolverInscricao();
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        $matriculas = collect();
        $atividades = collect();

        if ($inscricao->id > 0) {
            $matriculas = MatriculaDisciplina::where('signin_id', $inscricao->id)
                ->with(['disciplina.professor', 'notas'])
                ->get();

            $disciplinasIds = $matriculas->pluck('disciplina_id');
            if ($disciplinasIds->isNotEmpty()) {
                $atividades = Atividade::whereIn('disciplina_id', $disciplinasIds)
                    ->with(['disciplina', 'professor'])
                    ->latest()
                    ->take(20)
                    ->get();
            }
        }

        return view('portal.dashboard', compact('inscricao', 'matriculas', 'atividades'));
    }

    public function portalFinanceiro()
    {
        $inscricao = $this->resolverInscricao();
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        $faturas = collect();
        $asaasService = app(AsaasService::class);

        // Auto-sincronização para inscrições feitas antes da migração de IDs
        if (!$inscricao->asaas_installment_id && $inscricao->asaas_payment_id) {
            try {
                $response = Http::withHeaders([
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
                Log::warning('Erro ao sincronizar IDs Asaas: ' . $e->getMessage());
            }
        }

        $faturasRaw = $this->buscarFaturas($inscricao, $asaasService);

        // Paginação Manual
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

        $this->autoCurarPagamento($inscricao, $faturasRaw);

        return view('portal.financeiro', compact('inscricao', 'faturas'));
    }

    public function portalNotas()
    {
        $inscricao = $this->resolverInscricao();
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        $matriculas = collect();

        if ($inscricao->id > 0) {
            $matriculas = MatriculaDisciplina::where('signin_id', $inscricao->id)
                ->with(['disciplina.professor', 'notas'])
                ->orderBy('id', 'asc')
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

        session()->put('impersonator_id', Auth::id());
        Auth::login($user);

        return redirect()->route('aluno.portal')->with('success', 'Você está acessando como ' . $user->name);
    }

    public function leaveImpersonate()
    {
        $adminId = session('impersonator_id');

        if ($adminId) {
            Auth::loginUsingId($adminId);
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
        $inscricao = $this->resolverInscricao();
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        $user = auth()->user();
        $matriculas = MatriculaDisciplina::where('signin_id', $inscricao->id ?? 0)
            ->with('disciplina')
            ->get();

        if (!$disciplina && $matriculas->isNotEmpty()) {
            return redirect()->route('aluno.mural', $matriculas->first()->disciplina_id);
        }

        if ($disciplina && $inscricao->id > 0) {
            $temAcesso = $matriculas->contains('disciplina_id', $disciplina->id);
            if (!$temAcesso && !$user->is_admin) {
                abort(403, 'Você não está matriculado nesta disciplina.');
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
        $inscricao = $this->resolverInscricao(requireReal: true);
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        $matriculas = MatriculaDisciplina::where('signin_id', $inscricao->id)
            ->with(['disciplina.professor', 'notas'])
            ->get();

        $pdf = Pdf::loadView('portal.boletim-pdf', compact('inscricao', 'matriculas'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('boletim-escolar-' . ($inscricao->matricula ?? $inscricao->id) . '.pdf');
    }

    public function downloadMatricula()
    {
        $inscricao = $this->resolverInscricao(requireReal: true);
        if ($inscricao instanceof \Illuminate\Http\RedirectResponse) {
            return $inscricao;
        }

        if (!$inscricao->matricula) {
            return back()->withErrors(['erro' => 'Você ainda não possui um número de matrícula gerado.']);
        }

        $pdf = Pdf::loadView('portal.matricula-pdf', compact('inscricao'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('declaracao-matricula-' . $inscricao->matricula . '.pdf');
    }

    /**
     * Resolve a inscrição do usuário logado.
     * Se admin sem inscrição, retorna um mock. Se aluno sem, redireciona.
     */
    private function resolverInscricao(bool $requireReal = false): Signin|\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $inscricao = Signin::where('email', $user->email)->first();
        $isAdmin = $user->is_admin || $user->hasRole(['admin_master', 'financeiro', 'admin_comum', 'professor']);

        if (!$inscricao && $isAdmin && !$requireReal) {
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
            return redirect()->route('inscricao.index')
                ->withErrors(['login' => 'Nenhuma inscrição ativa encontrada. Por favor, inscreva-se primeiro.']);
        }

        return $inscricao;
    }

    /**
     * Busca faturas do Asaas priorizando customer_id > installment_id > payment_id.
     */
    private function buscarFaturas(Signin $inscricao, AsaasService $asaasService): \Illuminate\Support\Collection
    {
        if ($inscricao->asaas_customer_id) {
            return $asaasService->buscarPagamentosPorCliente($inscricao->asaas_customer_id);
        }

        if ($inscricao->asaas_installment_id) {
            return $asaasService->buscarFaturasParcelamento($inscricao->asaas_installment_id);
        }

        if ($inscricao->asaas_payment_id) {
            try {
                $response = Http::withHeaders([
                    'access_token' => config('services.asaas.key'),
                ])->get(config('services.asaas.url') . '/payments/' . $inscricao->asaas_payment_id);

                if ($response->successful()) {
                    return collect([$response->json()]);
                }
            } catch (\Exception $e) {
                // Silencioso
            }
        }

        return collect();
    }

    /**
     * Auto-Cura: se o Asaas retornou fatura paga mas a inscrição está pendente, corrige.
     */
    private function autoCurarPagamento(Signin $inscricao, \Illuminate\Support\Collection $faturasRaw): void
    {
        if ($inscricao->status_pagamento === 'pago' || $faturasRaw->isEmpty()) {
            return;
        }

        $faturaPaga = $faturasRaw->first(function ($fatura) {
            return in_array($fatura['status'], ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH']);
        });

        if (!$faturaPaga) {
            return;
        }

        $inscricao->status_pagamento = 'pago';
        if (!$inscricao->matricula) {
            $inscricao->matricula = app(GeradorMatriculaService::class)->gerarMatricula($inscricao);
        }
        $inscricao->save();

        Log::info('Auto-cura Asaas: Pagamento detectado como Pago.', ['signin' => $inscricao->id]);
    }
}