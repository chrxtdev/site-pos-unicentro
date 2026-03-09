<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        if (!$inscricao && $user->is_admin) {
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
}