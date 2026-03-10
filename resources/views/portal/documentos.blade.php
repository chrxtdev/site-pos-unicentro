@extends('layouts.portal')

@section('title', 'Central de Documentos')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2
                        class="text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-3 tracking-tight">
                        Documentos Oficiais
                        <span
                            class="px-2.5 py-1 rounded-md bg-blue-500/10 text-blue-500 text-xs font-bold uppercase tracking-widest border border-blue-500/20">
                            Auto-Serviço
                        </span>
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        Gere e baixe documentos com validade digital diretamente pelo portal.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Certificado de Matrícula -->
                <div
                    class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm hover:border-primary/50 transition-all group">
                    <div
                        class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Declaração de Matrícula</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                        Documento oficial comprovando seu vínculo ativo com a instituição e o curso de pós-graduação.
                    </p>
                    <a href="{{ route('aluno.documentos.matricula') }}" target="_blank"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 dark:bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity gap-2">
                        <i class="fa-solid fa-download"></i> Gerar PDF
                    </a>
                </div>

                <!-- Histórico (Placeholder) -->
                <div
                    class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm opacity-60 grayscale relative overflow-hidden">
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-[9px] font-black uppercase text-slate-400">Em
                            Breve</span>
                    </div>
                    <div
                        class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-5">
                        <i class="fa-solid fa-list-ol text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-400 mb-2">Histórico Acadêmico</h3>
                    <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                        Relatório completo de notas e frequências de todas as disciplinas concluídas.
                    </p>
                    <button disabled
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-200 text-slate-400 rounded-xl text-sm font-bold cursor-not-allowed">
                        Indisponível
                    </button>
                </div>
            </div>

            <!-- Ajuda -->
            <div class="bg-indigo-500/5 border border-indigo-500/10 rounded-2xl p-6 mt-12 flex items-start gap-4">
                <div
                    class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-500 shrink-0">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-300">Precisa de um documento específico?
                    </h4>
                    <p class="text-xs text-indigo-700/70 dark:text-indigo-400/70 mt-1">
                        Caso necessite de documentos que não estão listados acima (como programas de disciplina ou conteúdo
                        programático), entre em contato com a Secretaria Acadêmica.
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
