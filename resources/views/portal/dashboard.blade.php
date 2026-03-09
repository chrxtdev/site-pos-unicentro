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

            {{-- Card Único de Resumo Acadêmico --}}
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg shadow-gray-900/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                        <i class="fa-solid fa-graduation-cap text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm font-semibold mb-1 uppercase tracking-wider">Meu Curso</h4>
                        <p class="text-2xl font-bold text-white leading-tight mb-2">{{ $inscricao->pos_graduacao }}</p>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center bg-slate-700/50 border border-slate-600 px-2 py-0.5 rounded text-xs font-mono text-slate-300">
                                <i class="fa-solid fa-hashtag text-[9px] mr-1 text-slate-500"></i> RA: {{ $inscricao->matricula ?? 'Aguardando Liberação' }}
                            </span>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest opacity-60">Pós-Graduação</p>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-400 font-medium border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Matrícula Ativa
                    </span>
                </div>
            </div>
            
            {{-- SEÇÃO INFERIOR: MURAL DE ATIVIDADES --}}
            <div class="max-w-4xl space-y-6">
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
