@extends('layouts.admin')

@section('title', 'Painel de Controle')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div
                class="relative overflow-hidden bg-white dark:bg-surface-dark p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-blue-50 dark:bg-blue-500/10 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">groups</span>
                    </div>
                    <span
                        class="flex items-center gap-1 text-sm font-medium text-primary bg-primary/10 px-2 py-1 rounded-lg">
                        <span class="material-symbols-outlined text-sm">trending_up</span> Em Alta
                    </span>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total de Matrículas</h3>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">
                    {{ number_format($totalAlunos, 0, ',', '.') }}</p>
            </div>
            <!-- Card 2 -->
            <div
                class="relative overflow-hidden bg-white dark:bg-surface-dark p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-primary/10 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                    </div>
                    <span
                        class="flex items-center gap-1 text-sm font-medium text-primary bg-primary/10 px-2 py-1 rounded-lg">
                        <span class="material-symbols-outlined text-sm">trending_up</span> Mês Atual
                    </span>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Receita Bruta (Mês)</h3>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">R$
                    {{ number_format($receitaMensal, 2, ',', '.') }}</p>
            </div>
            <!-- Card 3 -->
            <div
                class="relative overflow-hidden bg-white dark:bg-surface-dark p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-purple-50 dark:bg-purple-500/10 p-3 rounded-xl">
                        <span
                            class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">app_registration</span>
                    </div>
                    <span
                        class="flex items-center gap-1 text-sm font-medium text-primary bg-primary/10 px-2 py-1 rounded-lg">
                        <span class="material-symbols-outlined text-sm">trending_up</span> Em Tempo Real
                    </span>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Inscritos Hoje</h3>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $inscricoesHoje }}</p>
            </div>
        </div>
        <!-- Recent Enrollments -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Matrículas Recentes</h3>
                <button class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">Ver Todas</button>
            </div>
            <div
                class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <th
                                    class="p-4 pl-6 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Aluno(a)</th>
                                <th
                                    class="p-4 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Curso Escolhido</th>
                                <th
                                    class="p-4 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Data</th>
                                <th
                                    class="p-4 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Método</th>
                                <th
                                    class="p-4 pr-6 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 text-right">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                            @foreach ($matriculasRecentes as $matricula)
                                @php
                                    $icon = 'barcode_scanner';
                                    $bgBadge =
                                        'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 border-blue-200 dark:border-blue-500/30';
                                    $tipo = 'Boleto';

                                    if ($matricula->forma_pagamento == 'pix') {
                                        $icon = 'qr_code';
                                        $bgBadge =
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30';
                                        $tipo = 'Pix';
                                    } elseif ($matricula->forma_pagamento == 'cartao') {
                                        $icon = 'credit_card';
                                        $bgBadge =
                                            'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400 border-purple-200 dark:border-purple-500/30';
                                        $tipo = 'Cartão';
                                    }
                                @endphp
                                <!-- Row -->
                                <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                                <img alt="Profile picture of {{ $matricula->nome }}"
                                                    class="w-full h-full object-cover"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($matricula->nome) }}&background=random">
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white">{{ $matricula->nome }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $matricula->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $matricula->pos_graduacao }}</td>
                                    <td class="p-4 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $matricula->created_at->format('d M, Y') }}</td>
                                    <td class="p-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $bgBadge }} border">
                                            <span class="material-symbols-outlined text-[14px]">{{ $icon }}</span>
                                            {{ $tipo }}
                                        </span>
                                    </td>
                                    <td class="p-4 pr-6 text-right">
                                        @if ($matricula->status_pagamento === 'pago')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                                                <i class="fa-solid fa-check-circle mr-1"></i> Pago
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                                                <i class="fa-solid fa-clock mr-1"></i> Pendente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
