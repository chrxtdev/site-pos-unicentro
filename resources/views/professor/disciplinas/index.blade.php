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
            @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
            <div class="flex gap-3">
                <a href="{{ route('cursos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-primary/30">
                    <i class="fa-solid fa-plus"></i> Nova Turma
                </a>
            </div>
            @endif
        </div>

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($cursos as $curso)
                <div
                    class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden flex flex-col group hover:shadow-2xl transition-all duration-500">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 flex-1 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-all">
                        </div>

                        @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
                            <div class="absolute top-4 right-4 flex gap-2">
                                <a href="{{ route('cursos.edit', $curso->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-blue-500 transition-colors" title="Editar Turma">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja apagar esta turma? Todas as matérias associadas serão perdidas!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-red-500 transition-colors" title="Apagar Turma">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 mb-4 mt-2">
                            <span
                                class="text-[10px] font-black text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-widest border border-primary/20">
                                Turma Base
                            </span>
                        </div>
                        <h3
                            class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-4 group-hover:text-primary transition-colors">
                            {{ $curso->nome }}
                        </h3>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-primary"></i> {{ $curso->disciplinas_count }} Disciplinas Encontradas
                            </p>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 flex gap-4">
                        <a href="{{ route('professor.disciplinas.curso', $curso->id) }}"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-primary hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-glow active:scale-95">
                            <i class="fa-solid fa-folder-open"></i> Acessar Turma
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
