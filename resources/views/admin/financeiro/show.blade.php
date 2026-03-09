@extends('layouts.admin')

@section('title', 'Detalhes Financeiros do Aluno')

@section('content')

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <a href="{{ route('financeiro.index') }}" class="group inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-primary transition-colors">
                    <span class="w-6 h-6 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 group-hover:bg-primary/10 mr-2 transition-colors">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </span>
                    Voltar à Listagem
                </a>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-3 tracking-tight">
                Ficha Financeira
                <span class="px-2.5 py-1 rounded-md bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest border border-primary/20">
                    Detalhes
                </span>
            </h2>
        </div>
        
        <!-- Action Buttons Internos ex: Impersonate para ajudar -->
        <a href="{{ route('admin.impersonate', $aluno->id) }}" target="_blank" class="px-5 py-2.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-xl text-sm font-bold hover:bg-indigo-500/20 dark:hover:bg-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/10 transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-shield"></i> Entrar como Aluno
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Resumo do Aluno -->
        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 lg:col-span-2 relative overflow-hidden group hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <!-- Efeito Blur Background -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>

            <div class="flex flex-col sm:flex-row justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-6 mb-6 relative z-10 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 text-primary flex items-center justify-center font-bold text-2xl shadow-inner border border-primary/20">
                        {{ substr($aluno->nome, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $aluno->nome }}</h3>
                        <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            <span class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">RA: {{ $aluno->matricula ?? '---' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                            <span>{{ $aluno->cpf }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                            <span class="truncate max-w-[150px] sm:max-w-none">{{ $aluno->email }}</span>
                        </div>
                    </div>
                </div>
                @if($aluno->status_pagamento === 'pago')
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shadow-sm whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Matrícula Ativa
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 shadow-sm whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span> Matrícula Pendente
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                <div class="space-y-1.5">
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><i class="fa-solid fa-graduation-cap"></i> Curso Vinculado</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-2" title="{{ $aluno->pos_graduacao }}">{{ $aluno->pos_graduacao }}</span>
                </div>
                <div class="space-y-1.5">
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><i class="fa-solid fa-credit-card"></i> Forma Preferencial</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 capitalize flex items-center gap-2">
                        @if(strtolower($aluno->forma_pagamento) == 'pix')
                            <i class="fa-brands fa-pix text-emerald-500"></i> Pix
                        @else
                            <i class="fa-solid fa-barcode text-slate-400"></i> {{ $aluno->forma_pagamento ?? 'Boleto' }}
                        @endif
                    </span>
                </div>
                <div class="space-y-1.5">
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><i class="fa-regular fa-calendar-check"></i> Inscrito Em</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $aluno->created_at->format('d/m/Y \à\s H:i') }}</span>
                </div>
                <div class="space-y-1.5">
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><i class="fa-brands fa-whatsapp"></i> Telefone Contato</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $aluno->telefone_celular }}</span>
                </div>
            </div>
        </div>

        <!-- Info Financeira Resumo -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-950 rounded-2xl shadow-xl border border-slate-800 p-8 flex flex-col justify-center text-center relative overflow-hidden group">
            <!-- Glow background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-primary/20 blur-[80px] rounded-full pointer-events-none opacity-50 group-hover:opacity-70 transition-opacity"></div>
            
            <i class="fa-solid fa-coins absolute -right-6 -bottom-6 text-8xl text-white/5 opacity-10 rotate-[-15deg] group-hover:scale-110 transition-transform duration-500"></i>
            
            <div class="relative z-10 flex flex-col items-center">
                <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full text-[10px] font-bold text-slate-300 uppercase tracking-widest mb-4">Valor da Mensalidade</span>
                <div class="flex items-baseline justify-center gap-1 text-white">
                    <span class="text-2xl font-semibold opacity-80">R$</span>
                    <span class="text-5xl font-black tracking-tight">{{ number_format($aluno->valor_mensalidade, 2, ',', '.') }}</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-5 leading-relaxed max-w-[220px] font-medium border-t border-white/10 pt-4">
                    Integração com gateway de pagamentos automatizada. Faturas geradas sempre no início do mês.
                </p>
            </div>
        </div>
    </div>

    <!-- Histórico de Faturas -->
    <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20 backdrop-blur-xl">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                Faturas Lançadas
            </h3>
            <span class="flex items-center gap-2 text-xs font-bold px-3 py-1.5 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Webhook Asaas Ativo
            </span>
        </div>
        
        <div class="overflow-x-auto">
            @if(isset($faturas) && $faturas->count() > 0)
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-white dark:bg-surface-dark border-b border-slate-100 dark:border-slate-800 uppercase text-[10px] font-extrabold tracking-widest text-slate-400 dark:text-slate-500">
                        <tr>
                            <th class="px-8 py-5">Vencimento</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5">Valor</th>
                            <th class="px-8 py-5 text-center">Referência (Gateway)</th>
                            <th class="px-8 py-5 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                        @foreach($faturas as $fatura)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-8 py-5 font-semibold text-slate-900 dark:text-slate-200">
                                    {{ \Carbon\Carbon::parse($fatura->dueDate)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-5">
                                    @if($fatura->status === 'RECEIVED' || $fatura->status === 'CONFIRMED')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20"><i class="fa-solid fa-check mr-1.5 opacity-70"></i> Pago</span>
                                    @elseif($fatura->status === 'OVERDUE')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500 border border-red-500/20"><i class="fa-solid fa-triangle-exclamation mr-1.5 opacity-70"></i> Atrasado</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20"><i class="fa-solid fa-hourglass-half mr-1.5 opacity-70"></i> Pendente</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-900 dark:text-white tabular-nums">
                                    R$ {{ number_format($fatura->value, 2, ',', '.') }}
                                </td>
                                <td class="px-8 py-5 text-center text-xs text-slate-400 font-mono tracking-wider">
                                    {{ $fatura->id ?? '---' }}
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <a href="{{ $fatura->invoiceUrl }}" target="_blank" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary rounded-lg text-xs font-bold shadow-sm hover:shadow transition-all inline-flex items-center gap-2">
                                        Fatura Oficial <i class="fa-solid fa-arrow-up-right-from-square opacity-70"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-8 py-24 text-center text-slate-500 dark:text-slate-400">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-receipt text-3xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Nenhuma fatura encontrada</h4>
                    <p class="text-sm max-w-sm mx-auto leading-relaxed">Os pagamentos começarão a registrar automaticamente via Gateway assim que a primeira cobrança for processada.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
