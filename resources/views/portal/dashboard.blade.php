@extends('layouts.portal')

@section('title', 'Meu Portal')

@section('content')

    <div class="py-12">
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
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center shadow-lg text-white">
                            <i class="fa-solid fa-user-graduate text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">
                                Bem-vindo ao seu Portal, {{ auth()->user()->name }}
                            </h3>
                            <p class="text-gray-400 mt-1 text-sm">Acompanhe sua Pós-Graduação e gerencie seus
                                pagamentos.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 Cards de Resumo --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Meu Curso --}}
                <div
                    class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                                <i class="fa-solid fa-graduation-cap text-lg"></i>
                            </div>
                            <h4 class="text-gray-300 font-semibold">Meu Curso</h4>
                        </div>
                        <p class="text-xl font-bold text-white leading-tight">{{ $inscricao->pos_graduacao }}</p>
                    </div>
                    <div class="mt-6 flex items-center text-sm text-gray-400">
                        <i class="fa-solid fa-circle-info mr-2"></i> Especialização
                    </div>
                </div>

                {{-- Status do Pagamento --}}
                <div
                    class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 rounded-lg {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }} flex items-center justify-center">
                                <i
                                    class="fa-solid {{ $inscricao->status_pagamento === 'pago' ? 'fa-check-circle' : 'fa-clock' }} text-lg"></i>
                            </div>
                            <h4 class="text-gray-300 font-semibold">Status Financeiro</h4>
                        </div>
                        <div
                            class="inline-flex items-center px-4 py-2 rounded-full border {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400' : 'bg-amber-500/20 border-amber-500/30 text-amber-400' }} font-bold text-lg">
                            <span
                                class="w-2.5 h-2.5 rounded-full {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }} mr-2"></span>
                            {{ $inscricao->status_pagamento === 'pago' ? 'Pagamento Confirmado' : 'Aguardando Pagamento' }}
                        </div>
                    </div>
                    <div class="mt-6 flex items-center text-sm text-gray-400">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Desde
                        {{ $inscricao->created_at->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Próximo Vencimento / Faturas --}}
                <div
                    class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                </div>
                                <h4 class="text-gray-300 font-semibold">Mensalidade</h4>
                            </div>
                            <span class="text-2xl font-bold text-white">R$
                                {{ number_format($inscricao->valor_mensalidade, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if ($inscricao->status_pagamento === 'pago')
                            <div
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-lg font-medium">
                                <i class="fa-solid fa-check-double"></i> Tudo Certo!
                            </div>
                        @else
                            @php
                                $modoPagamento = $inscricao->forma_pagamento ?? 'boleto';
                                if ($modoPagamento === 'pix') {
                                    $btnCor = 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30';
                                    $btnIcon = 'fa-brands fa-pix';
                                    $btnTxtGerar = 'Pagar via Pix';
                                    $btnTxtVer = 'Visualizar Pix';
                                } elseif ($modoPagamento === 'cartao') {
                                    $btnCor = 'bg-purple-600 hover:bg-purple-700 shadow-purple-500/30';
                                    $btnIcon = 'fa-regular fa-credit-card';
                                    $btnTxtGerar = 'Pagar com Cartão';
                                    $btnTxtVer = 'Tentar Novamente';
                                } else {
                                    $btnCor = 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30';
                                    $btnIcon = 'fa-solid fa-barcode';
                                    $btnTxtGerar = 'Gerar Boleto';
                                    $btnTxtVer = 'Visualizar Boleto';
                                }
                            @endphp
                            <a href="{{ route('boleto.gerar', ['id' => $inscricao->id]) }}" target="_blank"
                                class="w-full flex items-center justify-center gap-2 py-2.5 {{ $btnCor }} text-white rounded-lg transition-colors font-medium shadow-lg focus:ring-4">
                                @if ($inscricao->asaas_payment_id)
                                    <i class="{{ $btnIcon }}"></i> {{ $btnTxtVer }}
                                @else
                                    <i class="fa-solid fa-plus-circle"></i> {{ $btnTxtGerar }}
                                @endif
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            
            {{-- SEÇÃO INFERIOR: BOLETIM E MURAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- COLUNA 1: DESEMPENHO (BOLETIM) - Ocupa 2/3 em TElas Grandes --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-400"></i> Meu Desempenho
                    </h3>
                    
                    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden flex flex-col">
                        @if(isset($matriculas) && $matriculas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm whitespace-nowrap">
                                    <thead class="bg-gray-900 border-b border-gray-700 text-gray-300">
                                        <tr>
                                            <th class="px-5 py-3 font-semibold">Disciplina</th>
                                            <th class="px-5 py-3 font-semibold text-center">Bimestre 1</th>
                                            <th class="px-5 py-3 font-semibold text-center">Bimestre 2</th>
                                            <th class="px-5 py-3 font-semibold text-center text-white">Média Final</th>
                                            <th class="px-5 py-3 font-semibold text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700 text-gray-300">
                                        @foreach($matriculas as $mat)
                                            <tr class="hover:bg-gray-700/50 transition-colors">
                                                <td class="px-5 py-4">
                                                    <p class="font-bold text-white">{{ $mat->disciplina->nome }}</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">Prof. {{ $mat->disciplina->professor->name ?? 'Não Definido' }} &bull; {{ $mat->disciplina->carga_horaria }}h</p>
                                                </td>
                                                <td class="px-5 py-4 text-center font-medium">{{ $mat->notas->b1_total ?? '-' }}</td>
                                                <td class="px-5 py-4 text-center font-medium">{{ $mat->notas->b2_total ?? '-' }}</td>
                                                <td class="px-5 py-4 text-center font-bold text-emerald-400 text-base">
                                                    {{ $mat->notas->media_final ?? '-' }}
                                                </td>
                                                <td class="px-5 py-4 text-center">
                                                    @if($mat->status == 'aprovado')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><i class="fa-solid fa-check mr-1"></i> Aprovado</span>
                                                    @elseif($mat->status == 'reprovado')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20"><i class="fa-solid fa-xmark mr-1"></i> Reprovado</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-gray-700 text-gray-400 border border-gray-600"><i class="fa-regular fa-clock mr-1"></i> Cursando</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-book-open text-4xl mb-4 text-gray-600"></i>
                                <p>Você ainda não foi matriculado em nenhuma disciplina.</p>
                                <p class="text-xs mt-1">A coordenação gerenciará suas turmas em breve.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- COLUNA 2: MURAL DE ATIVIDADES - Ocupa 1/3 --}}
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-indigo-400"></i> Mural da Turma
                    </h3>
                    
                    <div class="flex flex-col gap-4">
                        @forelse($atividades ?? [] as $atividade)
                            <div class="bg-gray-800 border border-gray-700 p-5 rounded-xl shadow-lg relative group overflow-hidden">
                                {{-- Linha indicadora de cor --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                                
                                <div class="pl-2">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">{{ $atividade->disciplina->nome }}</span>
                                            <h4 class="text-base font-bold text-white mt-1 leading-tight">{{ $atividade->titulo }}</h4>
                                        </div>
                                    </div>
                                    
                                    <p class="text-xs text-gray-400 mb-3"><i class="fa-solid fa-user text-[10px] mr-1"></i> {{ $atividade->professor->name }} &bull; {{ $atividade->created_at->diffForHumans() }}</p>
                                    
                                    @if($atividade->descricao)
                                        <p class="text-sm text-gray-300 leading-relaxed mb-4 line-clamp-3 group-hover:line-clamp-none transition-all">{{ $atividade->descricao }}</p>
                                    @endif
                                    
                                    @if($atividade->data_limite)
                                        <div class="mb-3 inline-flex items-center gap-1.5 px-2 py-1 rounded bg-gray-900 border border-gray-700 text-xs font-bold {{ $atividade->data_limite->isPast() ? 'text-red-400' : 'text-amber-400' }}">
                                            <i class="fa-regular fa-clock"></i> Prazo: {{ $atividade->data_limite->format('d/m/Y H:i') }}
                                        </div>
                                    @endif

                                    @if($atividade->arquivo_path || $atividade->link_externo)
                                        <div class="flex flex-col gap-2 pt-3 border-t border-gray-700">
                                            @if($atividade->arquivo_path)
                                                <a href="{{ Storage::url($atividade->arquivo_path) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 text-xs font-bold rounded-lg transition-colors">
                                                    <i class="fa-solid fa-download"></i> Baixar Arquivo Anexo
                                                </a>
                                            @endif
                                            @if($atividade->link_externo)
                                                <a href="{{ $atividade->link_externo }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/30 text-sky-400 text-xs font-bold rounded-lg transition-colors">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Acessar Link Externo
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl text-center text-gray-500">
                                <i class="fa-regular fa-comments text-3xl mb-3 text-gray-600"></i>
                                <p class="text-sm">Nenhuma atividade ou aviso postado pelos professores até o momento.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
    </div>
@endsection
