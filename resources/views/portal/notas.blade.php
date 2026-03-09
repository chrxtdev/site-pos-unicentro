@extends('layouts.portal')

@section('title', 'Desempenho Acadêmico')

@section('content')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER DESEMPENHO --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-3 tracking-tight">
                        Meu Boletim
                        <span class="px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-500 text-xs font-bold uppercase tracking-widest border border-emerald-500/20">
                            Notas Oficiais
                        </span>
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Acompanhe o seu rendimento em cada disciplina do curso <strong class="text-slate-700 dark:text-slate-300">{{ $inscricao->pos_graduacao }}</strong>.
                    </p>
                </div>
            </div>

            {{-- RESUMO ESTATÍSTICO --}}
            @php
                $totalDisciplinas = $matriculas->count();
                $aprovadas = $matriculas->where('status', 'aprovado')->count();
                $reprovadas = $matriculas->where('status', 'reprovado')->count();
                $cursando = $matriculas->whereNotIn('status', ['aprovado', 'reprovado'])->count();
                
                // Média Geral
                $somaMedias = 0;
                $contMedias = 0;
                foreach($matriculas as $m) {
                    if(isset($m->notas->media_final) && is_numeric($m->notas->media_final)) {
                        $somaMedias += $m->notas->media_final;
                        $contMedias++;
                    }
                }
                $mediaGeral = $contMedias > 0 ? round($somaMedias / $contMedias, 1) : 0;
            @endphp

            @if($totalDisciplinas > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400">
                            <i class="fa-solid fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Disciplinas</p>
                            <h4 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $totalDisciplinas }}</h4>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Aprovadas</p>
                            <h4 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $aprovadas }}</h4>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-spinner text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Cursando</p>
                            <h4 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $cursando }}</h4>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-900 to-slate-950 rounded-2xl p-6 border border-slate-800 shadow-xl flex flex-col justify-center relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class="fa-solid fa-star text-6xl text-white"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Média Geral do Curso</p>
                            <div class="flex items-baseline gap-1.5 text-white">
                                <h4 class="text-4xl font-black tracking-tighter {{ $mediaGeral >= 7 ? 'text-emerald-400' : ($mediaGeral > 0 ? 'text-red-400' : 'text-white') }}">{{ number_format($mediaGeral, 1, ',', '.') }}</h4>
                                <span class="text-sm font-medium text-slate-500">/ 10</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GRID DE DISCIPLINAS --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($matriculas as $mat)
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden group hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                            
                            {{-- Cabeçalho da Disciplina --}}
                            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-800/20 flex justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-1 group-hover:text-primary transition-colors">
                                        {{ $mat->disciplina->nome }}
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                        <i class="fa-solid fa-chalkboard-user"></i> Prof. {{ $mat->disciplina->professor->name ?? 'Não Definido' }}
                                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600 mx-1"></span>
                                        <i class="fa-regular fa-clock"></i> {{ $mat->disciplina->carga_horaria }}h
                                    </p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3">
                                    {{-- FREQUÊNCIA --}}
                                    @php
                                        $percFreq = $mat->total_aulas > 0 ? ($mat->presencas / $mat->total_aulas) * 100 : 0;
                                        $freqColor = $percFreq >= 75 ? 'text-emerald-500 bg-emerald-500/10' : 'text-red-500 bg-red-500/10';
                                    @endphp
                                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm" title="Frequência: {{ $mat->presencas }}/{{ $mat->total_aulas }} aulas">
                                        <i class="fa-solid fa-user-check text-[10px] text-slate-400"></i>
                                        <span class="text-xs font-bold {{ $freqColor }}">{{ round($percFreq) }}%</span>
                                    </div>

                                    @if($mat->status == 'aprovado')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                            Aprovado
                                        </span>
                                    @elseif($mat->status == 'reprovado')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-red-500/10 text-red-500 border border-red-500/20">
                                            Reprovado
                                        </span>
                                    @elseif($mat->status == 'recuperacao' || $mat->status == 'exame')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                            Exame Final
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                            Cursando
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Detalhamento de Frequência (Mobile) --}}
                            <div class="sm:hidden px-6 py-2 bg-slate-100/50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">Frequência</span>
                                <span class="text-xs font-bold {{ $freqColor }}">{{ round($percFreq) }}% ({{ $mat->presencas }}/{{ $mat->total_aulas }} aulas)</span>
                            </div>

                            {{-- Corpo com as Notas --}}
                            <div class="p-6">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative">
                                    
                                    <!-- Bimestre 1 -->
                                    <div class="col-span-1 border border-slate-100 dark:border-slate-800 rounded-xl p-4 text-center bg-slate-50 dark:bg-slate-800/30">
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Bimestre 1</span>
                                        <span class="block text-2xl font-semibold text-slate-800 dark:text-slate-200">
                                            {{ isset($mat->notas->b1_total) ? number_format($mat->notas->b1_total, 1, ',', '.') : '--' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Bimestre 2 -->
                                    <div class="col-span-1 border border-slate-100 dark:border-slate-800 rounded-xl p-4 text-center bg-slate-50 dark:bg-slate-800/30">
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Bimestre 2</span>
                                        <span class="block text-2xl font-semibold text-slate-800 dark:text-slate-200">
                                            {{ isset($mat->notas->b2_total) ? number_format($mat->notas->b2_total, 1, ',', '.') : '--' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Recuperação/Exame (Opcional) -->
                                    <div class="col-span-1 border border-slate-100 dark:border-slate-800 rounded-xl p-4 text-center bg-slate-50 dark:bg-slate-800/30 opacity-70">
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Recuperação</span>
                                        <span class="block text-2xl font-semibold text-slate-600 dark:text-slate-400">
                                            {{ isset($mat->notas->recuperacao) && $mat->notas->recuperacao > 0 ? number_format($mat->notas->recuperacao, 1, ',', '.') : '--' }}
                                        </span>
                                    </div>

                                    <!-- Média Final -->
                                    @php
                                        $mf = $mat->notas->media_final ?? null;
                                        $isAprovado = $mf >= 7.0;
                                        $mfClass = $mf === null ? 'text-slate-800 dark:text-slate-200' : ($isAprovado ? 'text-emerald-500 dark:text-emerald-400' : 'text-red-500 dark:text-red-400');
                                    @endphp
                                    <div class="col-span-1 border-2 {{ $isAprovado ? 'border-emerald-500/20 bg-emerald-500/5' : ($mf !== null ? 'border-red-500/20 bg-red-500/5' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800') }} rounded-xl p-4 text-center relative overflow-hidden">
                                        <span class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Média Final</span>
                                        <span class="block text-3xl font-black tracking-tighter {{ $mfClass }}">
                                            {{ $mf !== null ? number_format($mf, 1, ',', '.') : '--' }}
                                        </span>
                                    </div>

                                </div>

                                {{-- COMPOSIÇÃO DA NOTA (ATIVIDADES) --}}
                                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
                                    <h4 class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-list-check"></i> Composição da Nota (Atividades)
                                    </h4>
                                    
                                    @php
                                        // Busca as atividades da disciplina
                                        $atividades = \App\Models\Atividade::where('disciplina_id', $mat->disciplina_id)->limit(3)->get();
                                    @endphp

                                    <div class="space-y-3">
                                        @forelse($atividades as $index => $atv)
                                            @php
                                                // Mapeamento fictício para os slots da tabela notas
                                                $notaAtv = null;
                                                if($index == 0) $notaAtv = $mat->notas->b1_t1 ?? null;
                                                if($index == 1) $notaAtv = $mat->notas->b1_t2 ?? null;
                                                if($index == 2) $notaAtv = $mat->notas->b1_t3 ?? null;
                                            @endphp
                                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/20 rounded-xl border border-slate-100 dark:border-slate-800 group/item hover:border-primary/30 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700 font-bold text-xs">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate max-w-[180px] sm:max-w-[250px]">{{ $atv->titulo }}</p>
                                                        <p class="text-[10px] text-slate-500">Prazo: {{ $atv->data_limite ? $atv->data_limite->format('d/m/Y') : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-black text-primary">{{ $notaAtv !== null ? number_format($notaAtv, 1, ',', '.') : '--' }}</span>
                                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Nota</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500 text-center py-2 italic font-medium">As atividades desta disciplina serão listadas em breve.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="bg-white dark:bg-surface-dark rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm">
                    <div class="w-24 h-24 mx-auto bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-graduation-cap text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-3 tracking-tight">Nenhuma matrícula encontrada</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                        Parece que você ainda não foi matriculado em nenhuma disciplina oficialmente. A coordenação do curso gerenciará suas turmas com os professores em breve.
                    </p>
                </div>
            @endif

        </div>
    </div>

@endsection
