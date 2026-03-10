@extends('layouts.admin')

@section('title', 'Processos Seletivos')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8 animate-fade-in text-slate-900 dark:text-slate-100">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black tracking-tight flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                        <span class="material-symbols-outlined text-2xl">assignment_turned_in</span>
                    </div>
                    Processos Seletivos
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 font-medium">Gestão global de cronogramas e etapas
                    de admissão.</p>
            </div>

            <a href="{{ route('processos.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white text-sm font-bold rounded-2xl shadow-glow transition-all duration-300 hover:-translate-y-0.5 active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                Novo Processo
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div
                class="glass-card border-emerald-500/20 bg-emerald-500/5 p-4 rounded-2xl flex items-center gap-3 text-emerald-600 dark:text-emerald-400 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="glass-card border-red-500/20 bg-red-500/5 p-4 rounded-2xl flex items-center gap-3 text-red-600 dark:text-red-400 animate-fade-in">
                <span class="material-symbols-outlined">error</span>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Table Card -->
        <div class="glass-card rounded-[2.5rem] shadow-soft border border-slate-200 dark:border-white/5 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5 uppercase">
                            <th
                                class="px-8 py-6 text-[10px] font-black tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                Informações do Processo</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black tracking-[0.2em] text-slate-400 dark:text-slate-500 text-center">
                                Configuração</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black tracking-[0.2em] text-slate-400 dark:text-slate-500 text-center">
                                Status</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right">
                                Painel de Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse ($processos as $processo)
                            <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                            <span
                                                class="text-lg font-black italic">#{{ str_pad($processo->id, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">
                                                {{ $processo->nome }}</p>
                                            <p
                                                class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">
                                                Criado em {{ $processo->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="flex flex-col items-center px-3 py-1.5 rounded-xl bg-blue-500/5 border border-blue-500/10"
                                            title="Etapas">
                                            <span
                                                class="text-xs font-black text-blue-500">{{ $processo->numero_etapas }}</span>
                                            <span class="text-[9px] uppercase font-bold text-blue-400/80">Etapas</span>
                                        </div>
                                        <div class="flex flex-col items-center px-3 py-1.5 rounded-xl bg-amber-500/5 border border-amber-500/10"
                                            title="Ofertas">
                                            <span
                                                class="text-xs font-black text-amber-500">{{ $processo->numero_ofertas }}</span>
                                            <span class="text-[9px] uppercase font-bold text-amber-400/80">Vagas</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    @if ($processo->situacao === 'ATIVO')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Ativo
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Inativo
                                        </span>
                                    @endif
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('inscricao.index', ['processo' => $processo->id]) }}"
                                            class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-emerald-500 dark:hover:text-emerald-400 hover:border-emerald-500/30 transition-all hover:shadow-lg hover:shadow-emerald-500/10 cursor-pointer"
                                            title="Página de Inscrição">
                                            <span class="material-symbols-outlined text-[20px]">link</span>
                                        </a>

                                        <a href="{{ route('alunos.index', ['processo' => $processo->id]) }}"
                                            class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-blue-500 dark:hover:text-blue-400 hover:border-blue-500/30 transition-all hover:shadow-lg hover:shadow-blue-500/10 cursor-pointer"
                                            title="Ver Inscritos">
                                            <span class="material-symbols-outlined text-[20px]">groups</span>
                                        </a>

                                        <a href="{{ route('ofertas.index', ['processo' => $processo->id]) }}"
                                            class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-amber-500 dark:hover:text-amber-400 hover:border-amber-500/30 transition-all hover:shadow-lg hover:shadow-amber-500/10 cursor-pointer"
                                            title="Gestão de Vagas">
                                            <span class="material-symbols-outlined text-[20px]">school</span>
                                        </a>

                                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                                        <a href="{{ route('processos.edit', $processo) }}"
                                            class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-primary dark:hover:text-primary hover:border-primary/30 transition-all hover:shadow-lg hover:shadow-primary/10 cursor-pointer"
                                            title="Editar Configurações">
                                            <span class="material-symbols-outlined text-[20px]">edit_square</span>
                                        </a>

                                        <form action="{{ route('processos.destroy', $processo) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir permanentemente este processo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 hover:border-red-500/30 transition-all hover:shadow-lg hover:shadow-red-500/10 cursor-pointer"
                                                title="Apagar Processo">
                                                <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner">
                                            <span
                                                class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">inventory_2</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Vazio por aqui
                                        </h3>
                                        <p
                                            class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed mb-8">
                                            Não encontramos nenhum processo seletivo cadastrado. Comece agora mesmo!</p>
                                        <a href="{{ route('processos.create') }}"
                                            class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 dark:bg-primary text-white text-sm font-bold rounded-2xl shadow-soft transition-all">
                                            Criar Primeiro Processo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($processos->hasPages())
                <div
                    class="px-8 py-6 border-t border-slate-100 dark:border-slate-800/50 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $processos->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection
