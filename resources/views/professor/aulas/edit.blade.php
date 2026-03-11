@extends('layouts.admin')

@section('title', 'Editar Aula - ' . $disciplina->nome)

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-8 animate-fade-in">
    <div class="flex items-center justify-between px-4">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Editar Registro de Aula</h3>
        <a href="{{ route('professor.aulas.index', $disciplina) }}" class="text-sm font-bold text-slate-500 hover:text-primary transition-colors">Cancelar</a>
    </div>

    <form action="{{ route('professor.aulas.update', $aula) }}" method="POST" class="flex flex-col gap-8">
        @csrf
        @method('PUT')
        
        <!-- Detalhes da Aula -->
        <div class="glass-card p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Data da Aula</label>
                    <input type="date" name="data" value="{{ $aula->data->format('Y-m-d') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Hora (Opcional)</label>
                    <input type="time" name="hora" value="{{ $aula->hora }}" class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Qtd de Aulas/Horas</label>
                    <input type="number" name="qtd_aulas" value="{{ $aula->qtd_aulas }}" min="1" required class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all">
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Conteúdo Ministrado</label>
                <textarea name="conteudo" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all">{{ $aula->conteudo }}</textarea>
            </div>
        </div>

        <!-- Chamada -->
        <div class="flex flex-col gap-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white px-4">Chamada (Atualizar Presença)</h3>
            <div class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5">
                            <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Aluno(a)</th>
                            <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($matriculas as $matricula)
                        @php
                            $isPresente = $presencas[$matricula->signin_id] ?? true;
                        @endphp
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($matricula->aluno->nome) }}&background=random" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $matricula->aluno->nome }}</p>
                                        <p class="text-xs text-slate-500">{{ $matricula->aluno->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer group/radio">
                                        <input type="radio" name="chamada[{{ $matricula->signin_id }}]" value="1" {{ $isPresente ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 border-slate-300 focus:ring-emerald-500">
                                        <span class="text-xs font-bold text-slate-400 group-hover/radio:text-emerald-500 transition-colors uppercase">Presente</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group/radio-f">
                                        <input type="radio" name="chamada[{{ $matricula->signin_id }}]" value="0" {{ !$isPresente ? 'checked' : '' }} class="w-4 h-4 text-red-500 border-slate-300 focus:ring-red-500">
                                        <span class="text-xs font-bold text-slate-400 group-hover/radio-f:text-red-500 transition-colors uppercase">Falta</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl bg-amber-500 text-white font-black text-lg shadow-glow hover:scale-[1.02] active:scale-95 transition-all">
            Atualizar Aula e Chamada
        </button>
    </form>
</div>
@endsection
