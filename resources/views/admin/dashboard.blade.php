@extends('layouts.admin')

@section('title', 'Painel de Controle')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8 animate-fade-in">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div
                class="glass-card p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all">
                </div>
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="bg-blue-500/10 p-4 rounded-2xl text-blue-500 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total
                    de Matrículas</h3>
                <p class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    {{ number_format($totalAlunos, 0, ',', '.') }}
                </p>
            </div>

            <!-- Card 2 -->
            <div
                class="glass-card p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all">
                </div>
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="bg-primary/10 p-4 rounded-2xl text-primary group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <span class="material-symbols-outlined text-3xl">payments</span>
                    </div>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">
                    Receita Mensal</h3>
                <p class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    R$ {{ number_format($receitaMensal, 2, ',', '.') }}
                </p>
            </div>

            <!-- Card 3 -->
            <div
                class="glass-card p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all">
                </div>
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="bg-purple-500/10 p-4 rounded-2xl text-purple-400 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <span class="material-symbols-outlined text-3xl">app_registration</span>
                    </div>
                </div>
                <h3 class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">
                    Inscritos Hoje</h3>
                <p class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">
                    {{ $inscricoesHoje }}
                </p>
            </div>
        </div>
        <!-- Recent Enrollments -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Matrículas Recentes</h3>
                <a href="{{ route('alunos.index') }}"
                    class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">Ver Todas</a>
            </div>
            <div class="glass-card rounded-[2rem] shadow-soft border border-slate-200 dark:border-white/5 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Aluno(a)
                                </th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Curso
                                    Escolhido</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Data</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Método</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right">
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
