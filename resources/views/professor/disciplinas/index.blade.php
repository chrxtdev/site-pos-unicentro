@extends('layouts.admin')

@section('title', 'Acessar Diário de Turma')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                <i class="fa-solid fa-chalkboard-user mr-2 text-indigo-500"></i>Minhas Turmas
            </h2>
        </div>

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($disciplinas as $disciplina)
                <div class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col transition-transform hover:-translate-y-1 hover:shadow-md">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-400 px-2 py-1 rounded-md">
                                {{ $disciplina->curso->nome }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2">
                            {{ $disciplina->nome }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-1">
                            <i class="fa-regular fa-clock"></i> Carga Horária: {{ $disciplina->carga_horaria }}h
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-users"></i> Alunos Matriculados: {{ $disciplina->matriculas_count }}
                        </p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 flex gap-3">
                        <a href="{{ route('professor.notas.show', $disciplina->id) }}"
                            class="flex-1 flex justify-center items-center gap-2 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                            <i class="fa-solid fa-clipboard-list"></i> Diário
                        </a>
                        <a href="{{ route('professor.atividades.index', $disciplina->id) }}"
                            class="flex-1 flex justify-center items-center gap-2 py-2 px-4 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 text-sm font-bold rounded-xl transition-colors">
                            <i class="fa-solid fa-bullhorn"></i> Mural
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16 px-4 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-folder-open text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Nenhuma turma alocada</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">
                            Você ainda não foi alocado em nenhuma disciplina. Solicite à secretaria acadêmica sua vinculação.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
