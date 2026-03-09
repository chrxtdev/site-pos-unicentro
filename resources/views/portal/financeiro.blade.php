@extends('layouts.portal')

@section('title', 'Financeiro')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-xl mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-700 flex items-center justify-center shadow-lg text-white">
                            <i class="fa-solid fa-coins text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Central Financeira</h3>
                            <p class="text-gray-400 mt-1 text-sm">Visualize suas mensalidades, histórico de pagamentos e gere boletos.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Status do Pagamento --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }} flex items-center justify-center">
                                <i class="fa-solid {{ $inscricao->status_pagamento === 'pago' ? 'fa-check-circle' : 'fa-clock' }} text-lg"></i>
                            </div>
                            <h4 class="text-gray-300 font-semibold">Status Geral da Matrícula</h4>
                        </div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full border {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400' : 'bg-amber-500/20 border-amber-500/30 text-amber-400' }} font-bold text-lg">
                            <span class="w-2.5 h-2.5 rounded-full {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }} mr-2"></span>
                            {{ $inscricao->status_pagamento === 'pago' ? 'Matrícula Confirmada' : 'Aguardando Pagamento da Matrícula' }}
                        </div>
                    </div>
                    <div class="mt-6 flex items-center text-sm text-gray-400">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Aluno desde {{ $inscricao->created_at->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Próximo Vencimento / Ações --}}
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                </div>
                                <h4 class="text-gray-300 font-semibold">Valor da Mensalidade</h4>
                            </div>
                            <span class="text-2xl font-bold text-white">R$ {{ number_format($inscricao->valor_mensalidade, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if ($inscricao->status_pagamento === 'pago')
                            <div class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-700 border border-gray-600 text-gray-300 rounded-lg font-medium cursor-not-allowed opacity-75">
                                <i class="fa-solid fa-file-invoice"></i> Ver Boleto da Matrícula
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

            {{-- Tabela de Faturas --}}
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="px-6 py-5 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-400"></i> Histórico de Faturas
                    </h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-gray-700 text-gray-300 border border-gray-600 rounded-full">Automático via Asaas</span>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    @if(isset($faturas) && $faturas->count() > 0)
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-900/50 border-b border-gray-700 text-gray-400 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Vencimento</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Valor</th>
                                    <th class="px-6 py-4 text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($faturas as $fatura)
                                    <tr class="hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-300">
                                            {{ \Carbon\Carbon::parse($fatura->dueDate)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($fatura->status === 'RECEIVED' || $fatura->status === 'CONFIRMED')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Pago</span>
                                            @elseif($fatura->status === 'OVERDUE')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">Atrasado</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pendente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-200 font-semibold">
                                            R$ {{ number_format($fatura->value, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ $fatura->invoiceUrl }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-medium inline-flex items-center gap-1 transition-colors">
                                                Visualizar <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-16 text-center text-gray-500">
                            <i class="fa-solid fa-receipt text-5xl mb-4 text-gray-700"></i>
                            <h4 class="text-lg font-semibold text-gray-400 mb-1">Nenhuma fatura lançada</h4>
                            <p class="text-sm">As faturas das suas mensalidades aparecerão aqui automaticamente.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
