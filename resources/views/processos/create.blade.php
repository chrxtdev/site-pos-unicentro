@extends('layouts.admin')

@section('title', isset($processo) ? 'Editar Processo Seletivo' : 'Novo Processo Seletivo')

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col gap-8 animate-fade-in text-slate-900 dark:text-slate-100">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('processos.index') }}"
                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-primary/10 hover:text-primary transition-all duration-300">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black tracking-tight">
                        {{ isset($processo) ? 'Editar Processo' : 'Novo Processo Seletivo' }}</h2>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-widest mt-0.5">Definição de parâmetros e
                        prazos</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-[2.5rem] shadow-soft border border-slate-200 dark:border-white/5 p-8 md:p-12">
            <form action="{{ isset($processo) ? route('processos.update', $processo) : route('processos.store') }}"
                method="POST" class="space-y-8">
                @csrf
                @if (isset($processo))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nome do Processo -->
                    <div class="md:col-span-2 group">
                        <label for="nome"
                            class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-2 transition-colors group-focus-within:text-primary">Título
                            do Processo Seletivo</label>
                        <div class="relative">
                            <input type="text" name="nome" id="nome"
                                value="{{ old('nome', $processo->nome ?? '') }}"
                                class="w-full bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm font-bold placeholder-slate-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                                placeholder="Ex: Processo Seletivo 2024/02">
                            @error('nome')
                                <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Configurações Numéricas -->
                    <div x-data class="group">
                        <label for="numero_etapas"
                            class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-2 transition-colors group-focus-within:text-primary">Qtd.
                            de Etapas</label>
                        <input type="text" name="numero_etapas" id="numero_etapas"
                            value="{{ old('numero_etapas', $processo->numero_etapas ?? '1') }}" x-mask="99"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm font-bold focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-center">
                        @error('numero_etapas')
                            <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data class="group">
                        <label for="numero_ofertas"
                            class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-2 transition-colors group-focus-within:text-primary">Vagas
                            Ofertadas</label>
                        <input type="text" name="numero_ofertas" id="numero_ofertas"
                            value="{{ old('numero_ofertas', $processo->numero_ofertas ?? '100') }}" x-mask="9999"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm font-bold focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none text-center">
                        @error('numero_ofertas')
                            <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Situação -->
                    <div class="md:col-span-2 group">
                        <label
                            class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-4 transition-colors group-focus-within:text-primary">Disponibilidade
                            do Processo</label>
                        <div class="flex gap-4">
                            <label class="relative flex-1 cursor-pointer group/opt">
                                <input type="radio" name="situacao" value="ATIVO" class="peer hidden"
                                    {{ old('situacao', $processo->situacao ?? 'ATIVO') == 'ATIVO' ? 'checked' : '' }}>
                                <div
                                    class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/5 transition-all text-center">
                                    <span
                                        class="material-symbols-outlined block text-2xl text-slate-300 dark:text-slate-600 peer-checked:group-[]:text-emerald-500 mb-1">check_circle</span>
                                    <p
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 peer-checked:group-[]:text-emerald-500">
                                        Ativo</p>
                                </div>
                            </label>

                            <label class="relative flex-1 cursor-pointer group/opt">
                                <input type="radio" name="situacao" value="INATIVO" class="peer hidden"
                                    {{ old('situacao', $processo->situacao ?? '') == 'INATIVO' ? 'checked' : '' }}>
                                <div
                                    class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-red-500 peer-checked:bg-red-500/5 transition-all text-center">
                                    <span
                                        class="material-symbols-outlined block text-2xl text-slate-300 dark:text-slate-600 peer-checked:group-[]:text-red-500 mb-1">cancel</span>
                                    <p
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 peer-checked:group-[]:text-red-500">
                                        Inativo</p>
                                </div>
                            </label>
                        </div>
                        @error('situacao')
                            <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800/50 flex justify-end">
                    <button type="submit"
                        class="px-10 py-4 bg-primary hover:bg-primary/90 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-glow transition-all duration-300 hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        {{ isset($processo) ? 'Atualizar Configurações' : 'Salvar Processo' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
