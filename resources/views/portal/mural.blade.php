@extends('layouts.portal')

@section('title', 'Mural Acadêmico')

@section('content')

    <div class="py-8 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER DESEMPENHO --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2
                        class="text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-3 tracking-tight">
                        Mural Acadêmico
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Avisos, recados e materiais oficiais disponibilizados pelos professores em cada disciplina.
                    </p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">

                {{-- COLUNA ESQUERDA: LISTA DE MATÉRIAS --}}
                <div class="lg:w-1/3">
                    <div
                        class="glass-card rounded-3xl shadow-soft border border-slate-200 dark:border-white/5 overflow-hidden sticky top-24">
                        <div
                            class="px-6 py-5 bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-white/5">
                            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-tighter">Minhas
                                Disciplinas</h3>
                        </div>
                        <div
                            class="divide-y divide-slate-100 dark:divide-white/5 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            @forelse($matriculas as $mat)
                                <a href="{{ route('aluno.mural', $mat->disciplina_id) }}"
                                    class="block px-6 py-5 transition-all hover:bg-white dark:hover:bg-slate-800/50 group
                                          {{ $disciplina && $disciplina->id == $mat->disciplina_id ? 'bg-primary/5 dark:bg-primary/10 border-l-4 border-primary' : 'border-l-4 border-transparent' }}">
                                    <h4
                                        class="text-sm font-black {{ $disciplina && $disciplina->id == $mat->disciplina_id ? 'text-primary' : 'text-slate-700 dark:text-slate-200' }} leading-tight mb-2 group-hover:translate-x-1 transition-transform">
                                        {{ $mat->disciplina->nome }}
                                    </h4>
                                    <p class="text-xs text-slate-500 flex items-center gap-2">
                                        <div
                                            class="w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px]">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <span class="font-medium">Prof.
                                            {{ $mat->disciplina->professor->name ?? 'N/D' }}</span>
                                    </p>
                                </a>
                            @empty
                                <div class="px-6 py-12 text-center text-slate-500 text-sm">
                                    <i class="fa-solid fa-folder-open text-3xl mb-3 opacity-20 block"></i>
                                    Nenhuma disciplina cursando atualmente.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- COLUNA DIREITA: FEED DO MURAL --}}
                <div class="lg:w-2/3">
                    @if ($disciplina)
                        <div
                            class="glass-card rounded-[2rem] shadow-soft border border-slate-200 dark:border-white/5 mb-6 overflow-hidden">
                            <div
                                class="px-8 py-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                                <div>
                                    <span
                                        class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-2">Feed
                                        Interativo</span>
                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $disciplina->nome }}
                                    </h3>
                                </div>
                            </div>

                            <div class="p-6">
                                <div
                                    class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 dark:before:via-slate-800 before:to-transparent">

                                    @forelse($avisos as $aviso)
                                        <div class="relative flex items-start w-full group">
                                            {{-- Indicador Timeline --}}
                                            <div
                                                class="absolute left-0 w-10 h-10 hidden sm:flex items-center justify-center -translate-x-1/2 rounded-full border-4 border-white dark:border-surface-dark {{ $aviso->tipo == 'material' ? 'bg-blue-500' : 'bg-primary' }} text-white shadow-sm z-10 transition-transform group-hover:scale-110">
                                                <i
                                                    class="fa-solid {{ $aviso->arquivo_path ? 'fa-file-pdf' : 'fa-bullhorn' }} text-sm"></i>
                                            </div>

                                            {{-- Card de Conteúdo --}}
                                            <div class="w-full sm:ml-8">
                                                <div
                                                    class="bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-white/5 rounded-3xl p-8 shadow-soft hover:shadow-xl transition-all group/post">


                                                    <div
                                                        class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-4">
                                                        <div>
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <span
                                                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $aviso->arquivo_path ? 'bg-blue-500/10 text-blue-500' : 'bg-primary/10 text-primary' }}">
                                                                    {{ $aviso->arquivo_path ? 'Material' : 'Aviso' }}
                                                                </span>
                                                                <span class="text-xs text-slate-400">
                                                                    <i class="fa-regular fa-clock mr-1"></i>
                                                                    {{ $aviso->created_at->diffForHumans() }}
                                                                    ({{ $aviso->created_at->format('d/m/Y H:i') }})
                                                                </span>
                                                            </div>
                                                            <h4
                                                                class="text-lg font-bold text-slate-800 dark:text-slate-100">
                                                                {{ $aviso->titulo }}</h4>
                                                        </div>
                                                        <div class="shrink-0">
                                                            <div
                                                                class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 px-3 py-1.5 rounded-full">
                                                                <div
                                                                    class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs text-slate-500">
                                                                    <i class="fa-solid fa-user"></i>
                                                                </div>
                                                                <span
                                                                    class="text-xs font-medium text-slate-600 dark:text-slate-400">Prof.
                                                                    {{ $aviso->professor->name ?? 'N/D' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 mb-4 whitespace-pre-wrap">
                                                        {!! nl2br(e($aviso->descricao)) !!}</div>

                                                    @if ($aviso->arquivo_path)
                                                        <div
                                                            class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                                            <a href="{{ Storage::url($aviso->arquivo_path) }}"
                                                                target="_blank"
                                                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-900 dark:hover:bg-slate-900/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition-colors">
                                                                <i class="fa-solid fa-download text-primary"></i>
                                                                Visualizar / Baixar Anexo
                                                            </a>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div
                                            class="bg-slate-50 dark:bg-slate-800/20 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-regular fa-comments text-2xl text-slate-400"></i>
                                            </div>
                                            <p class="text-slate-600 dark:text-slate-400 font-medium">Nenhum aviso ou
                                                material publicado nesta disciplina até o momento.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="bg-white dark:bg-surface-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-12 text-center h-full flex flex-col justify-center items-center">
                            <i class="fa-solid fa-arrow-pointer text-4xl text-slate-300 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">Selecione uma Disciplina
                            </h3>
                            <p class="text-slate-500 max-w-sm">Escolha uma matéria no menu lateral para visualizar os
                                últimos avisos e materiais publicados pelo seu professor.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
