@extends('layouts.portal')

@section('title', 'Financeiro')

@section('content')

    <div class="py-12 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div
                class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft p-8 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700">
                </div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div
                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-primary to-emerald-700 flex items-center justify-center shadow-glow text-white group-hover:rotate-6 transition-transform duration-500">
                        <i class="fa-solid fa-coins text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tighter">Central
                            Financeira</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-medium">Gerencie suas mensalidades e
                            pagamentos com total segurança</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Status do Pagamento --}}
                <div
                    class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 p-8 shadow-soft group hover:shadow-2xl transition-all duration-500 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-shield-check text-6xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }} flex items-center justify-center border border-current/20">
                                <i
                                    class="fa-solid {{ $inscricao->status_pagamento === 'pago' ? 'fa-check-circle' : 'fa-clock' }} text-xl"></i>
                            </div>
                            <h4 class="text-slate-900 dark:text-white font-black uppercase tracking-tight">Status da
                                Matrícula</h4>
                        </div>
                        <div
                            class="inline-flex items-center px-6 py-3 rounded-2xl {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20' }} font-black text-xl border shadow-glow">
                            <span
                                class="w-2.5 h-2.5 rounded-full {{ $inscricao->status_pagamento === 'pago' ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }} mr-3"></span>
                            {{ $inscricao->status_pagamento === 'pago' ? 'Confirmada' : 'Pendente' }}
                        </div>
                    </div>
                    <div
                        class="mt-8 flex items-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                        <i class="fa-solid fa-calendar-check mr-2 text-primary"></i> Ativo desde
                        {{ $inscricao->created_at->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Próximo Vencimento / Ações --}}
                <div
                    class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 hover:border-gray-600 transition-all flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                </div>
                                <h4 class="text-gray-300 font-semibold">Valor da Mensalidade</h4>
                            </div>
                            <span class="text-2xl font-bold text-white">R$
                                {{ number_format($inscricao->valor_mensalidade, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        @if ($inscricao->status_pagamento === 'pago')
                            <div
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-700 border border-gray-600 text-gray-300 rounded-lg font-medium cursor-not-allowed opacity-75">
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
                                } else {
                                    $btnCor = 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30';
                                    $btnIcon = 'fa-solid fa-barcode';
                                    $btnTxtGerar = 'Gerar Boleto';
                                    $btnTxtVer = 'Visualizar Carnê';
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
            <div class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden">
                <div
                    class="px-8 py-6 border-b border-slate-200 dark:border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-900/50">
                    <h3
                        class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                        <i class="fa-solid fa-list-check text-primary"></i> Histórico de Faturas
                    </h3>
                    <span
                        class="text-[10px] font-black px-4 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10 rounded-full uppercase tracking-widest">
                        Processamento via Asaas
                    </span>
                </div>

                <div class="p-0 overflow-x-auto">
                    @if (isset($faturas) && $faturas->count() > 0)
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead
                                class="bg-gray-900/50 border-b border-gray-700 text-gray-400 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Fatura</th>
                                    <th class="px-6 py-4">Vencimento</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Valor</th>
                                    <th class="px-6 py-4 text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach ($faturas as $fatura)
                                    <tr class="hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-gray-300 font-bold">Parcela
                                                {{ ($faturas->currentPage() - 1) * $faturas->perPage() + $loop->iteration }}/{{ $faturas->total() }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 uppercase tracking-tighter">Ref.
                                                {{ \Carbon\Carbon::parse($fatura['dueDate'])->subDays(5)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-300">
                                            {{ \Carbon\Carbon::parse($fatura['dueDate'])->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $status = $fatura['status'];
                                            @endphp
                                            @if ($status === 'RECEIVED' || $status === 'CONFIRMED' || $status === 'RECEIVED_IN_CASH')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Pago</span>
                                            @elseif($status === 'OVERDUE')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">Atrasado</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pendente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-200 font-semibold">
                                            R$ {{ number_format($fatura['value'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ $fatura['bankSlipUrl'] ?? $fatura['invoiceUrl'] }}" target="_blank"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium inline-flex items-center gap-1 transition-colors">
                                                Visualizar <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Links de Paginação --}}
                        <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-white/5">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Mostrando faturas {{ $faturas->firstItem() }} a {{ $faturas->lastItem() }} de {{ $faturas->total() }}</span>
                                <div class="pagination-custom">
                                    {{ $faturas->links() }}
                                </div>
                            </div>
                        </div>
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
