@extends('layouts.admin')

@section('title', 'Diário de Classe: ' . $disciplina->nome)

@section('content')
    <div class="max-w-full mx-auto flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('professor.disciplinas.index') }}"
                    class="text-sm text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 flex items-center gap-1 py-1 mb-1 transition-colors w-fit">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para Turmas
                </a>
                <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
                    {{ $disciplina->nome }}
                </h2>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-3">
                    <span><i class="fa-solid fa-graduation-cap mr-1"></i> {{ $disciplina->curso->nome }}</span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-users mr-1"></i> {{ $matriculas->count() }} Alunos</span>
                </p>
            </div>

            @if ($disciplina->status !== 'fechado')
                <div class="flex items-center gap-3">
                    <button type="submit" form="notasForm"
                        class="flex items-center justify-center gap-2 py-2 px-5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors whitespace-nowrap">
                        <i class="fa-solid fa-save"></i> Salvar Notas
                    </button>
                    <form action="{{ route('professor.notas.fechar', $disciplina->id) }}" method="POST"
                        onsubmit="return confirm('Tem certeza? Após o encerramento, o diário ficará bloqueado para sempre.')">
                        @csrf
                        <button type="submit"
                            class="flex items-center justify-center gap-2 py-2 px-5 border border-red-500/30 text-red-500 bg-red-500/10 hover:bg-red-500 hover:text-white text-sm font-bold rounded-xl shadow-md transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-lock"></i> Encerrar Diário
                        </button>
                    </form>
                </div>
            @else
                <span
                    class="inline-flex items-center gap-2 py-1.5 px-4 bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 font-bold text-sm border border-slate-200 dark:border-slate-700 rounded-xl">
                    <i class="fa-solid fa-lock"></i> Diário Encerrado
                </span>
            @endif
        </div>

        @if (session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div
            class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <form id="notasForm" action="{{ route('professor.notas.update', $disciplina->id) }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5">
                                <th
                                    class="py-5 px-6 font-black text-[10px] uppercase tracking-[0.2em] text-slate-500 sticky left-0 bg-slate-50 dark:bg-slate-900 z-10 w-72 border-r border-slate-200 dark:border-white/5">
                                    Aluno(a)</th>

                                <!-- 1º Bimestre -->
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-indigo-500 bg-indigo-500/5 w-24">T1 (1.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-indigo-500 bg-indigo-500/5 w-24">T2 (1.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-indigo-500 bg-indigo-500/5 w-24">T3 (2.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-indigo-500 bg-indigo-500/5 w-24">AVAL (6.0)</th>
                                <th class="py-5 px-3 font-black text-[10px] uppercase tracking-[0.2em] text-center text-white bg-indigo-600 w-24 shadow-inner">B1 TOTAL</th>

                                <!-- 2º Bimestre -->
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-blue-500 bg-blue-500/5 w-24">T1 (1.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-blue-500 bg-blue-500/5 w-24">T2 (1.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-blue-500 bg-blue-500/5 w-24">T3 (2.0)</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.15em] text-center text-blue-500 bg-blue-500/5 w-24">AVAL (6.0)</th>
                                <th class="py-5 px-3 font-black text-[10px] uppercase tracking-[0.2em] text-center text-white bg-blue-600 w-24 shadow-inner border-r border-slate-200 dark:border-white/5">B2 TOTAL</th>

                                <!-- Resultado -->
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.2em] text-center text-slate-800 dark:text-white w-20">FALTAS</th>
                                <th class="py-5 px-2 font-black text-[10px] uppercase tracking-[0.2em] text-center text-slate-800 dark:text-white w-20">FREQ %</th>
                                <th class="py-5 px-4 font-black text-[10px] uppercase tracking-[0.2em] text-center text-slate-800 dark:text-white w-24">MÉDIA</th>
                                <th class="py-5 px-4 font-black text-[10px] uppercase tracking-[0.2em] text-center text-slate-800 dark:text-white w-32 uppercase">Resultado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($matriculas as $matricula)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    <td
                                        class="py-3 px-4 sticky left-0 bg-white dark:bg-surface-dark group-hover:bg-slate-50 dark:group-hover:bg-slate-800/80 transition-colors z-10 border-r border-slate-100 dark:border-slate-800">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate"
                                            title="{{ $matricula->aluno->nome }}">
                                            {{ \Illuminate\Support\Str::words($matricula->aluno->nome, 3, '...') }}
                                        </p>
                                    </td>

                                    {{-- 1º Bimestre Inputs --}}
                                    <td class="py-2 px-2 bg-indigo-50/20 dark:bg-indigo-900/5">
                                        <input type="number" step="0.01" min="0" max="1.0"
                                            name="notas[{{ $matricula->id }}][b1_t1]"
                                            value="{{ $matricula->notas->b1_t1 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-indigo-500 focus:border-indigo-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-indigo-50/20 dark:bg-indigo-900/5">
                                        <input type="number" step="0.01" min="0" max="1.0"
                                            name="notas[{{ $matricula->id }}][b1_t2]"
                                            value="{{ $matricula->notas->b1_t2 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-indigo-500 focus:border-indigo-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-indigo-50/20 dark:bg-indigo-900/5">
                                        <input type="number" step="0.01" min="0" max="2.0"
                                            name="notas[{{ $matricula->id }}][b1_t3]"
                                            value="{{ $matricula->notas->b1_t3 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-indigo-500 focus:border-indigo-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-indigo-50/20 dark:bg-indigo-900/5">
                                        <input type="number" step="0.01" min="0" max="6.0"
                                            name="notas[{{ $matricula->id }}][b1_aval]"
                                            value="{{ $matricula->notas->b1_aval ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-indigo-500 focus:border-indigo-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-3 text-center bg-indigo-50/40 dark:bg-indigo-900/20">
                                        <span
                                            class="text-sm font-bold text-indigo-700 dark:text-indigo-400">{{ $matricula->notas->b1_total ?? '-' }}</span>
                                    </td>

                                    {{-- 2º Bimestre Inputs --}}
                                    <td class="py-2 px-2 bg-blue-50/20 dark:bg-blue-900/5">
                                        <input type="number" step="0.01" min="0" max="1.0"
                                            name="notas[{{ $matricula->id }}][b2_t1]"
                                            value="{{ $matricula->notas->b2_t1 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-blue-50/20 dark:bg-blue-900/5">
                                        <input type="number" step="0.01" min="0" max="1.0"
                                            name="notas[{{ $matricula->id }}][b2_t2]"
                                            value="{{ $matricula->notas->b2_t2 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-blue-50/20 dark:bg-blue-900/5">
                                        <input type="number" step="0.01" min="0" max="2.0"
                                            name="notas[{{ $matricula->id }}][b2_t3]"
                                            value="{{ $matricula->notas->b2_t3 ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 bg-blue-50/20 dark:bg-blue-900/5">
                                        <input type="number" step="0.01" min="0" max="6.0"
                                            name="notas[{{ $matricula->id }}][b2_aval]"
                                            value="{{ $matricula->notas->b2_aval ?? '' }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td
                                        class="py-2 px-3 text-center bg-blue-50/40 dark:bg-blue-900/20 border-r border-slate-100 dark:border-slate-800">
                                        <span
                                            class="text-sm font-bold text-blue-700 dark:text-blue-400">{{ $matricula->notas->b2_total ?? '-' }}</span>
                                    </td>

                                    {{-- Média, Freq e Status --}}
                                    <td class="py-2 px-2 bg-slate-50/50 dark:bg-slate-900/50">
                                        <input type="number" step="1" min="0" max="{{ $disciplina->carga_horaria }}"
                                            name="notas[{{ $matricula->id }}][faltas]"
                                            value="{{ $matricula->faltas ?? 0 }}"
                                            {{ $disciplina->status === 'fechado' ? 'readonly' : '' }}
                                            class="w-full text-center text-sm font-bold text-slate-700 dark:text-slate-300 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-slate-500 focus:border-slate-500 px-1 py-1.5 hide-arrows {{ $disciplina->status === 'fechado' ? 'opacity-70 cursor-not-allowed bg-slate-50 dark:bg-slate-800' : '' }}">
                                    </td>
                                    <td class="py-2 px-2 text-center bg-slate-50/50 dark:bg-slate-900/50 border-r border-slate-100 dark:border-slate-800">
                                        @php
                                            $freq = $disciplina->carga_horaria > 0 
                                                ? round((($disciplina->carga_horaria - ($matricula->faltas ?? 0)) / $disciplina->carga_horaria) * 100) 
                                                : 0;
                                        @endphp
                                        <span class="text-sm font-black {{ $freq < 75 ? 'text-red-500' : 'text-emerald-500' }}">{{ $freq }}%</span>
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        <span
                                            class="text-sm font-black {{ ($matricula->notas->media_final ?? 0) >= 7.0 ? 'text-emerald-500' : (isset($matricula->notas->media_final) ? 'text-red-500' : 'text-slate-500') }}">
                                            {{ $matricula->notas->media_final ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        @if ($matricula->status == 'aprovado')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20"><i
                                                    class="fa-solid fa-check mr-1"></i> Aprovado</span>
                                        @elseif($matricula->status == 'reprovado')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20"><i
                                                    class="fa-solid fa-xmark mr-1"></i> Reprovado</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700"><i
                                                    class="fa-regular fa-clock mr-1"></i> Cursando</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-12 px-4 text-center text-slate-500">
                                        Nenhum aluno matriculado nesta disciplina ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Ocultar as setas numéricas do tipo number */
        .hide-arrows::-webkit-outer-spin-button,
        .hide-arrows::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .hide-arrows {
            -moz-appearance: textfield;
        }
    </style>
@endsection
