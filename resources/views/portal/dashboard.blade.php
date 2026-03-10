@extends('layouts.portal')

@section('title', 'Meu Portal')

@section('content')

    <div class="py-8 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alertas de Sessão (Erros do Asaas, Sucesso de envio etc.) --}}
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-400 rounded-lg bg-red-500/10 border border-red-500/20 shadow-sm"
                    role="alert">
                    <span class="font-medium"><i class="fa-solid fa-circle-exclamation mr-2"></i> Erro!</span>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-emerald-400 rounded-lg bg-emerald-500/10 border border-emerald-500/20 shadow-sm"
                    role="alert">
                    <span class="font-medium"><i class="fa-solid fa-check-circle mr-2"></i> Sucesso!</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header de Boas-Vindas --}}
            <div
                class="relative overflow-hidden bg-white/50 dark:bg-slate-800/40 backdrop-blur-md border border-white/20 dark:border-white/5 rounded-3xl p-8 shadow-soft group">
                <div
                    class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700">
                </div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                    <div
                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-primary to-emerald-700 flex items-center justify-center shadow-glow text-white transform group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-user-graduate text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            Bem-vindo, <span class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}</span>!
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-1 text-lg font-medium">Sua jornada acadêmica
                            continua aqui.</p>
                    </div>
                </div>
            </div>

            {{-- Card Único de Resumo Acadêmico --}}
            <div
                class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-8 shadow-2xl border border-white/10 group">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform duration-700">
                    <i class="fa-solid fa-graduation-cap text-9xl text-white"></i>
                </div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-primary backdrop-blur-md border border-white/10">
                            <i class="fa-solid fa-bookmark text-2xl"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <h4 class="text-primary text-xs font-black uppercase tracking-[0.2em] mb-2">Curso Atual</h4>
                            <p class="text-3xl font-black text-white leading-tight mb-3">
                                {{ $inscricao->pos_graduacao }}
                            </p>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                                <span
                                    class="inline-flex items-center bg-white/5 border border-white/10 px-3 py-1 rounded-xl text-xs font-mono text-slate-300 backdrop-blur-sm">
                                    <i class="fa-solid fa-hashtag mr-2 text-primary"></i> RA:
                                    {{ $inscricao->matricula ?? '---' }}
                                </span>
                                <span
                                    class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-[10px] font-bold uppercase tracking-widest">
                                    Status: Ativo
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <a href="{{ route('aluno.notas') }}"
                            class="flex-1 sm:flex-none flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-emerald-600 text-white font-black rounded-2xl shadow-glow transition-all active:scale-95 group/btn">
                            <i class="fa-solid fa-chart-line transition-transform group-hover/btn:-translate-y-1"></i>
                            VER DESEMPENHO
                        </a>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO INFERIOR: ATALHOS RÁPIDOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-12">

                {{-- Card Atalho Mural --}}
                <a href="{{ route('aluno.mural') }}"
                    class="group relative overflow-hidden glass-card p-1 border border-slate-200 dark:border-white/5 rounded-[2rem] shadow-soft transition-all hover:shadow-2xl hover:-translate-y-2">
                    <div class="p-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <i class="fa-solid fa-bullhorn text-2xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 dark:text-white mb-3">Mural da Turma</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8">
                            Fique por dentro de todos os avisos, materiais e comunicados oficiais postados por seus
                            professores.
                        </p>
                        <div class="flex items-center gap-2 text-indigo-500 font-black text-sm uppercase tracking-widest">
                            Explorar Mural <i
                                class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-2"></i>
                        </div>
                    </div>
                </a>

                {{-- Card Atalho Financeiro --}}
                <a href="{{ route('aluno.financeiro') }}"
                    class="group relative overflow-hidden glass-card p-1 border border-slate-200 dark:border-white/5 rounded-[2rem] shadow-soft transition-all hover:shadow-2xl hover:-translate-y-2">
                    <div class="p-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <i class="fa-solid fa-wallet text-2xl"></i>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 dark:text-white mb-3">Gestão Financeira</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8">
                            Consulte seus pagamentos, emita boletos e acompanhe seu histórico financeiro de forma
                            simplificada.
                        </p>
                        <div class="flex items-center gap-2 text-emerald-500 font-black text-sm uppercase tracking-widest">
                            Ver Financeiro <i
                                class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-2"></i>
                        </div>
                    </div>
                </a>

            </div>

        </div>
    </div>
@endsection
