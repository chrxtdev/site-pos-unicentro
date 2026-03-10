@extends('layouts.portal')

@section('title', 'Desempenho Acadêmico')

@section('content')

    <div class="py-8 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER DESEMPENHO --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                <div>
                    <h2
                        class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tighter flex items-center gap-4">
                        Desempenho Acadêmico
                        <span
                            class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-[0.2em] border border-emerald-500/20 shadow-glow">
                            Oficial
                        </span>
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-medium">
                        Acompanhe suas notas e situação no curso <span
                            class="text-primary font-bold">{{ $inscricao->pos_graduacao }}</span>
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
                foreach ($matriculas as $m) {
                    if (isset($m->notas->media_final) && is_numeric($m->notas->media_final)) {
                        $somaMedias += $m->notas->media_final;
                        $contMedias++;
                    }
                }
                $mediaGeral = $contMedias > 0 ? round($somaMedias / $contMedias, 1) : 0;
            @endphp

            @if ($totalDisciplinas > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                        <div
                            class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-white/5 shadow-soft group hover:shadow-xl transition-all duration-500">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-book"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">
                                        Disciplinas</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $totalDisciplinas }}</h4>
                                </div>
                            </div>
                        </div>

                        <div
                            class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-white/5 shadow-soft group hover:shadow-xl transition-all duration-500">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">
                                        Aprovadas</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $aprovadas }}</h4>
                                </div>
                            </div>
                        </div>

                        <div
                            class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-white/5 shadow-soft group hover:shadow-xl transition-all duration-500">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">
                                        Cursando</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $cursando }}</h4>
                                </div>
                            </div>
                        </div>

                        <div
                            class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-950 rounded-3xl p-6 border border-white/10 shadow-2xl group hover:shadow-primary/20 transition-all duration-500">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-award text-6xl text-white"></i>
                            </div>
                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2">Média
                                    Geral</p>
                                <div class="flex items-baseline gap-2 text-white">
                                    <h4
                                        class="text-4xl font-black tracking-tighter {{ $mediaGeral >= 7 ? 'text-emerald-400' : ($mediaGeral > 0 ? 'text-red-400' : 'text-white') }}">
                                        {{ number_format($mediaGeral, 1, ',', '.') }}
                                    </h4>
                                    <span class="text-sm font-bold text-slate-500">/ 10</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="glass-card rounded-[2rem] shadow-soft border border-slate-200 dark:border-white/5 overflow-hidden mb-12">
                    <div
                        class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <h3
                            class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                            <i class="fa-solid fa-table-list text-primary"></i>
                            Boletim Semestral - {{ date('Y') }}/1
                        </h3>
                        <a href="{{ route('aluno.boletim.pdf') }}" target="_blank"
                            class="px-6 py-2.5 bg-primary hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-glow flex items-center gap-2">
                            <i class="fa-solid fa-print"></i> Imprimir PDF
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-900/80 text-slate-400 font-semibold border-b border-slate-800">
                                    <th class="p-3 border-r border-slate-800 w-16 text-center">Diário</th>
                                    <th class="p-3 border-r border-slate-800 min-w-[200px]">Disciplina</th>
                                    <th class="p-3 border-r border-slate-800 text-center w-20">C. H.</th>
                                    <th class="p-3 border-r border-slate-800 text-center w-20" title="Total de Aulas">T.
                                        Aulas</th>
                                    <th class="p-3 border-r border-slate-800 text-center w-20" title="Total de Faltas">
                                        Faltas</th>
                                    <th class="p-3 border-r border-slate-800 text-center w-20">% Freq.</th>
                                    <th class="p-3 border-r border-slate-800 text-center w-28">Situação</th>

                                    {{-- Etapa 1 --}}
                                    <th class="p-2 border-r border-slate-800 text-center bg-slate-800/30" colspan="2"
                                        title="Etapa 1 (Bimestre 1)">E1</th>

                                    {{-- Etapa 2 --}}
                                    <th class="p-2 border-r border-slate-800 text-center bg-slate-800/30" colspan="2"
                                        title="Etapa 2 (Bimestre 2)">E2</th>

                                    {{-- Recuperação (Opcional, simulando Etapa 3/Exame) --}}
                                    <th class="p-2 border-r border-slate-800 text-center bg-slate-800/30" colspan="2"
                                        title="Exame Final/Recuperação">Exame</th>

                                    <th class="p-3 border-r border-slate-800 text-center bg-slate-800/50 w-24">MFD</th>
                                    <th class="p-3 text-center w-24">Opções</th>
                                </tr>
                                <tr
                                    class="bg-slate-900/50 text-[10px] text-slate-500 uppercase font-bold border-b border-slate-800">
                                    <th class="p-1 border-r border-slate-800" colspan="7"></th>
                                    {{-- Sub-colunas E1 --}}
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Nota">N</th>
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Faltas Esp.">F</th>
                                    {{-- Sub-colunas E2 --}}
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Nota">N</th>
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Faltas Esp.">F</th>
                                    {{-- Sub-colunas Exame --}}
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Nota">N</th>
                                    <th class="p-1 border-r border-slate-800 text-center w-12" title="Faltas Esp.">F</th>

                                    <th class="p-1 border-r border-slate-800" colspan="2"></th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300 divide-y divide-slate-800/50">
                                @forelse($matriculas as $mat)
                                    @php
                                        $percFreq =
                                            $mat->total_aulas > 0 ? ($mat->presencas / $mat->total_aulas) * 100 : 0;
                                        $freqOk = $percFreq >= 75;
                                        $mf = $mat->notas->media_final ?? null;
                                        $isAprovado = $mf >= 7.0 && $freqOk;

                                        // Notas Limpas
                                        $n1 = isset($mat->notas->b1_total)
                                            ? number_format($mat->notas->b1_total, 2, ',', '')
                                            : '-';
                                        $n2 = isset($mat->notas->b2_total)
                                            ? number_format($mat->notas->b2_total, 2, ',', '')
                                            : '-';
                                        $nf =
                                            isset($mat->notas->recuperacao) && $mat->notas->recuperacao > 0
                                                ? number_format($mat->notas->recuperacao, 2, ',', '')
                                                : '-';
                                        $mfd = $mf !== null ? number_format($mf, 2, ',', '') : '-';
                                    @endphp
                                    <tr class="hover:bg-slate-800/30 transition-colors group">
                                        <td class="p-3 border-r border-slate-800/50 text-center text-slate-500 font-mono">
                                            {{ $mat->disciplina_id }}0{{ $mat->id }}</td>
                                        <td class="p-3 border-r border-slate-800/50 truncate max-w-xs">
                                            <div class="font-bold text-slate-200" title="{{ $mat->disciplina->nome }}">
                                                {{ Str::limit($mat->disciplina->nome, 40) }}
                                            </div>
                                            <div class="text-[10px] text-slate-500">Prof.
                                                {{ $mat->disciplina->professor->name ?? 'N/D' }}</div>
                                        </td>
                                        <td class="p-3 border-r border-slate-800/50 text-center text-slate-400">
                                            {{ $mat->disciplina->carga_horaria }}h</td>
                                        <td class="p-3 border-r border-slate-800/50 text-center text-slate-400">
                                            {{ $mat->total_aulas }}</td>
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center {{ $mat->faltas > 0 ? 'text-red-400 font-bold' : 'text-slate-400' }}">
                                            {{ $mat->faltas }}</td>
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center font-bold {{ $freqOk ? 'text-slate-300' : 'text-red-400' }}">
                                            {{ number_format($percFreq, 1, ',', '') }}%</td>
                                        <td
                                            class="px-6 py-5 border-r border-slate-200 dark:border-white/5 text-center font-black">
                                            @if ($mat->status == 'aprovado')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Aprovado
                                                </span>
                                            @elseif($mat->status == 'reprovado')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest border border-red-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Reprovado
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                    Cursando
                                                </span>
                                            @endif
                                        </td>

                                        <!-- E1 -->
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 font-medium {{ isset($mat->notas->b1_total) && $mat->notas->b1_total >= 7 ? 'text-emerald-400' : (isset($mat->notas->b1_total) ? 'text-red-400' : 'text-slate-500') }}">
                                            {{ $n1 }}</td>
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 text-slate-500">
                                            0</td>

                                        <!-- E2 -->
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 font-medium {{ isset($mat->notas->b2_total) && $mat->notas->b2_total >= 7 ? 'text-emerald-400' : (isset($mat->notas->b2_total) ? 'text-red-400' : 'text-slate-500') }}">
                                            {{ $n2 }}</td>
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 text-slate-500">
                                            0</td>

                                        <!-- Exame -->
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 font-medium text-amber-400">
                                            {{ $nf }}</td>
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/10 text-slate-500">
                                            0</td>

                                        <!-- MFD (Média Final) -->
                                        <td
                                            class="p-3 border-r border-slate-800/50 text-center bg-slate-800/40 font-black text-base {{ $isAprovado ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $mfd }}</td>

                                        <!-- Opções (Detalhar) -->
                                        <td class="px-6 py-5 text-center">
                                            <button onclick="openDetalheModal('disciplina-{{ $mat->id }}')"
                                                class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-primary hover:text-white text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-95 mx-auto group/btn">
                                                <span>Detalhar</span>
                                                <i
                                                    class="fa-solid fa-chevron-down transition-transform group-hover:translate-y-0.5"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- LINHA OCULTA: DETALHAMENTO DE NOTAS --}}
                                    <tr id="detalhe-disciplina-{{ $mat->id }}"
                                        class="hidden bg-slate-50/50 dark:bg-slate-900/50 shadow-inner">
                                        <td colspan="15" class="p-8">
                                            <div
                                                class="max-w-5xl mx-auto glass-card rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/5 animate-fade-in shadow-2xl">
                                                <div
                                                    class="bg-[#151a21] px-5 py-4 flex justify-between items-center border-b border-emerald-500/20">
                                                    <h4 class="text-white font-bold text-lg">Notas:
                                                        {{ $mat->disciplina->nome }}</h4>
                                                    <button onclick="closeDetalheModal('disciplina-{{ $mat->id }}')"
                                                        class="text-slate-400 hover:text-white transition-colors">
                                                        <i class="fa-solid fa-xmark text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="p-5 space-y-6">
                                                    {{-- Professores --}}
                                                    <div
                                                        class="border border-emerald-500/30 rounded-lg p-4 bg-[#151a21]/50">
                                                        <p class="text-emerald-400 text-sm font-bold mb-2">Professores</p>
                                                        <p class="text-slate-300 text-sm">
                                                            {{ $mat->disciplina->professor->name ?? 'Professor Não Designado' }}
                                                        </p>
                                                    </div>

                                                    {{-- Etapa 1 --}}
                                                    <div>
                                                        <p class="text-slate-300 text-sm mb-3">Etapa 1 - Soma Simples</p>
                                                        <table class="w-full text-sm text-left">
                                                            <thead class="text-white font-bold border-b border-slate-700">
                                                                <tr>
                                                                    <th class="py-2 w-16">Sigla</th>
                                                                    <th class="py-2 w-24">Tipo</th>
                                                                    <th class="py-2">Descrição</th>
                                                                    <th class="py-2 w-16 text-center">Peso</th>
                                                                    <th class="py-2 w-32 text-center">Nota Obtida</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="divide-y divide-slate-800 text-slate-300 bg-[#151a21]/80">
                                                                @forelse(\App\Models\Atividade::where('disciplina_id', $mat->disciplina_id)->limit(3)->get() as $index => $atv)
                                                                    @php
                                                                        $notaAtv = null;
                                                                        if ($index == 0) {
                                                                            $notaAtv = $mat->notas->b1_t1 ?? null;
                                                                        }
                                                                        if ($index == 1) {
                                                                            $notaAtv = $mat->notas->b1_t2 ?? null;
                                                                        }
                                                                        if ($index == 2) {
                                                                            $notaAtv = $mat->notas->b1_t3 ?? null;
                                                                        }

                                                                        $siglas = ['A', 'E', 'P'];
                                                                        $tipos = [
                                                                            'Assiduidade',
                                                                            'Atividade em Grupo',
                                                                            'Prova',
                                                                        ];
                                                                    @endphp
                                                                    <tr class="hover:bg-slate-800/50">
                                                                        <td class="py-3 px-2">
                                                                            {{ $siglas[$index] ?? 'Atd' }}</td>
                                                                        <td class="py-3">
                                                                            {{ $tipos[$index] ?? 'Trabalho' }}</td>
                                                                        <td class="py-3 px-2">{{ $atv->titulo }}</td>
                                                                        <td class="py-3 text-center">-</td>
                                                                        <td
                                                                            class="py-3 text-center font-bold {{ $notaAtv ? 'text-white' : 'text-slate-600' }}">
                                                                            {{ $notaAtv ? number_format($notaAtv, 2, ',', '') : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="5"
                                                                            class="py-4 text-center text-slate-500">Nenhuma
                                                                            atividade registrada nesta etapa.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    {{-- Exibir total Etapa 1 --}}
                                                    <div
                                                        class="flex justify-between items-center bg-[#151a21] p-3 rounded-lg border border-slate-700">
                                                        <span class="text-slate-400 font-bold text-sm">Total Etapa
                                                            1:</span>
                                                        <span
                                                            class="text-white font-bold text-lg">{{ $n1 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="p-8 text-center text-slate-500 bg-slate-800/10">
                                            Nenhuma matrícula ativa registrada.
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- Linha TOTAIS (Mock) --}}
                                @if (count($matriculas) > 0)
                                    <tr class="bg-slate-900 font-bold text-slate-400 border-t border-slate-700">
                                        <td colspan="2" class="p-3 text-right uppercase text-xs tracking-widest">
                                            Totais:</td>
                                        <td class="p-3 text-center">
                                            {{ $matriculas->sum(function ($m) {return $m->disciplina->carga_horaria;}) }}h
                                        </td>
                                        <td class="p-3 text-center">{{ $matriculas->sum('total_aulas') }}</td>
                                        <td class="p-3 text-center">{{ $matriculas->sum('faltas') }}</td>
                                        <td class="p-3 text-center text-emerald-500">Média:
                                            {{ number_format($mediaGeral, 1, ',', '') }}</td>
                                        <td colspan="9"></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    function openDetalheModal(id) {
                        const target = document.getElementById('detalhe-' + id);
                        const isHidden = target.classList.contains('hidden');

                        // Fecha todos abertos primeiro para manter apenas um
                        document.querySelectorAll('[id^="detalhe-disciplina-"]').forEach(el => el.classList.add('hidden'));

                        // Se estava fechado, abre agora. Se estava aberto, o comando acima já fechou.
                        if (isHidden) {
                            target.classList.remove('hidden');
                        }
                    }

                    function closeDetalheModal(id) {
                        document.getElementById('detalhe-' + id).classList.add('hidden');
                    }
                </script>
            @else
                {{-- EMPTY STATE --}}
                <div
                    class="bg-white dark:bg-surface-dark rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-sm">
                    <div
                        class="w-24 h-24 mx-auto bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-graduation-cap text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-3 tracking-tight">Nenhuma matrícula
                        encontrada</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                        Parece que você ainda não foi matriculado em nenhuma disciplina oficialmente. A coordenação do curso
                        gerenciará suas turmas com os professores em breve.
                    </p>
                </div>
            @endif

        </div>
    </div>

@endsection
