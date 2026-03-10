@extends('layouts.admin')

@section('title', 'Acessar Diário de Turma')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8 animate-fade-in">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-4">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tighter flex items-center gap-4">
                    Minhas Turmas
                    <span
                        class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] border border-primary/20 shadow-glow">
                        Professor
                    </span>
                </h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-medium">
                    Gerencie seus diários e interaja com seus alunos
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($disciplinas as $disciplina)
                <div
                    class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden flex flex-col group hover:shadow-2xl transition-all duration-500">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 flex-1 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-all">
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="text-[10px] font-black text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-widest border border-primary/20">
                                {{ $disciplina->curso->nome }}
                            </span>
                        </div>
                        <h3
                            class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-4 group-hover:text-primary transition-colors">
                            {{ $disciplina->nome }}
                        </h3>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-clock text-primary"></i> {{ $disciplina->carga_horaria }} Horas
                            </p>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-users text-primary"></i> {{ $disciplina->matriculas_count }} Alunos
                            </p>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 flex gap-4">
                        <a href="{{ route('professor.notas.show', $disciplina->id) }}"
                            class="flex-1 flex justify-center items-center gap-2 py-3 px-4 bg-primary hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-glow active:scale-95">
                            <i class="fa-solid fa-clipboard-list"></i> Diário
                        </a>
                        <a href="{{ route('professor.atividades.index', $disciplina->id) }}"
                            class="flex-1 flex justify-center items-center gap-2 py-3 px-4 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-black uppercase tracking-widest rounded-xl transition-all active:scale-95">
                            <i class="fa-solid fa-bullhorn"></i> Mural
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div
                        class="text-center py-16 px-4 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-2xl">
                        <div
                            class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-folder-open text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Nenhuma turma alocada</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">
                            Você ainda não foi alocado em nenhuma disciplina. Solicite à secretaria acadêmica sua
                            vinculação.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
