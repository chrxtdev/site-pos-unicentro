@extends('layouts.admin')

@section('title', 'Registro de Aulas - ' . $disciplina->nome)

@section('content')
<div class="max-w-7xl mx-auto flex flex-col gap-8 animate-fade-in">
    <!-- Header com Progresso -->
    <div class="glass-card p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft relative overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $disciplina->nome }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestão de frequência e conteúdo ministrado</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('professor.disciplinas.curso', $disciplina->curso_id) }}" class="px-6 py-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    Voltar
                </a>
                <a href="{{ route('professor.aulas.create', $disciplina) }}" class="px-6 py-3 rounded-2xl bg-primary text-white font-bold text-sm shadow-glow hover:scale-105 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> Registrar Aula
                </a>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between items-end mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Progresso da Carga Horária</span>
                <span class="text-sm font-black text-primary">{{ $totalHorasDadas }}/{{ $disciplina->carga_horaria }}h ({{ round($progresso) }}%)</span>
            </div>
            <div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="h-full bg-primary shadow-glow transition-all duration-1000" style="width: {{ min(100, $progresso) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Lista de Aulas -->
    <div class="flex flex-col gap-4">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white px-4">Histórico de Aulas</h3>
        
        @forelse($aulas as $aula)
        <div class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-6 items-start">
                <div class="flex-shrink-0 flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 font-black">
                    <span class="text-xs uppercase opacity-50">{{ $aula->data->format('M') }}</span>
                    <span class="text-xl">{{ $aula->data->format('d') }}</span>
                </div>
                
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase border border-indigo-100 dark:border-indigo-500/20">
                            {{ $aula->qtd_aulas }} {{ $aula->qtd_aulas > 1 ? 'Aulas' : 'Aula' }}
                        </span>
                        @if($aula->hora)
                        <span class="text-xs text-slate-400 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> {{ \Carbon\Carbon::parse($aula->hora)->format('H:i') }}
                        </span>
                        @endif
                    </div>
                    <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed italic">
                        {{ $aula->conteudo ?: 'Sem descrição de conteúdo.' }}
                    </p>
                </div>

                <div class="flex items-center gap-2 self-end md:self-center">
                    <a href="{{ route('professor.aulas.edit', $aula) }}" class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </a>
                    <form action="{{ route('professor.aulas.destroy', $aula) }}" method="POST" onsubmit="return confirm('Excluir registro de aula?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="glass-card p-12 rounded-[2rem] border border-dashed border-slate-300 dark:border-slate-700 text-center">
            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-4">event_busy</span>
            <p class="text-slate-500 dark:text-slate-400">Nenhuma aula registrada nesta disciplina ainda.</p>
            <a href="{{ route('professor.aulas.create', $disciplina) }}" class="inline-flex items-center gap-2 text-primary font-bold text-sm mt-4 hover:underline">
                Registrar primeira aula agora
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
