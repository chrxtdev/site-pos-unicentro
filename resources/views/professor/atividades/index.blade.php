@extends('layouts.admin')

@section('title', 'Mural de Atividades: ' . $disciplina->nome)

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('professor.disciplinas.index') }}"
                    class="text-sm text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 flex items-center gap-1 py-1 mb-1 transition-colors w-fit">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para Turmas
                </a>
                <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
                    Mural da Disciplina
                </h2>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-3">
                    <span><i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $disciplina->nome }}</span>
                </p>
            </div>

            <button type="button" onclick="document.getElementById('modalNovaAtividade').classList.remove('hidden')"
                class="flex items-center justify-center gap-2 py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Nova Postagem
            </button>
        </div>

        @if (session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex flex-col gap-1 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span class="text-sm font-bold">Falha ao salvar a atividade:</span>
                </div>
                <ul class="list-disc list-inside text-xs mt-1 ml-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col gap-4">
            @forelse($atividades as $atividade)
                <div
                    class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-shadow relative group">

                    {{-- Actions Menu --}}
                    @hasanyrole('admin_master|professor')
                        <div
                            class="absolute top-4 right-4 flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <button type="button"
                                onclick="openEditModal({{ $atividade->id }}, '{{ addslashes($atividade->titulo) }}', '{{ addslashes($atividade->descricao) }}', '{{ $atividade->link_externo }}', '{{ $atividade->data_limite ? $atividade->data_limite->format('Y-m-d\TH:i') : '' }}')"
                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-amber-500 flex items-center justify-center transition-colors"
                                title="Editar">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('professor.atividades.destroy', $atividade->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja apagar esta atividade permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors"
                                    title="Apagar">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    @endhasanyrole

                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/30">
                                Postado em {{ $atividade->created_at->format('d/m/Y H:i') }}
                            </span>
                            @if ($atividade->data_limite)
                                @php
                                    $isLate = $atividade->data_limite->isPast();
                                @endphp
                                <span
                                    class="{{ $isLate ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border-red-200 dark:border-red-500/20' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20' }} text-[10px] uppercase font-bold px-2 py-0.5 rounded border flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> Prazo:
                                    {{ $atividade->data_limite->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $atividade->titulo }}</h3>
                    </div>

                    @if ($atividade->descricao)
                        <div
                            class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line leading-relaxed border-l-2 border-indigo-500 pl-3">
                            {{ $atividade->descricao }}
                        </div>
                    @endif

                    @if ($atividade->arquivo_path || $atividade->link_externo)
                        <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                            @if ($atividade->arquivo_path)
                                <a href="{{ Storage::url($atividade->arquivo_path) }}" target="_blank"
                                    class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-file-arrow-down text-indigo-500"></i> Baixar Anexo
                                </a>
                            @endif
                            @if ($atividade->link_externo)
                                <a href="{{ $atividade->link_externo }}" target="_blank"
                                    class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-sky-500"></i> Acessar Link Externo
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div
                    class="text-center py-16 px-4 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-2xl border-dashed">
                    <div
                        class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-bullhorn text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Mural Vazio</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">
                        Ainda não há nenhuma atividade, link ou material compartilhado. Clique em "Nova Postagem" para
                        começar.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL NOVA ATIVIDADE --}}
    <div id="modalNovaAtividade"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-surface-dark w-full max-w-lg rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div
                class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 shrink-0">
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Nova Postagem no Mural</h3>
                <button type="button" onclick="document.getElementById('modalNovaAtividade').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form id="formNovaAtividade" action="{{ route('professor.atividades.store', $disciplina->id) }}"
                    method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Título da
                            Atividade/Aviso *</label>
                        <input type="text" name="titulo" required placeholder="Ex: Material da Aula 01"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Descrição /
                            Instruções (Opcional)</label>
                        <textarea name="descricao" rows="4" placeholder="Detalhes do que o aluno deve fazer..."
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Arquivo Anexo
                            (Opcional)</label>
                        <input type="file" name="arquivo"
                            class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 transition-colors">
                        <p class="text-[10px] text-slate-500 mt-1">PDF, Imagens, DOCs ou ZIPs (Máx 10MB).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Link Externo
                            (Opcional)</label>
                        <input type="url" name="link_externo" placeholder="https://youtube.com/..."
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Prazo / Data Limite
                            (Opcional)</label>
                        <input type="datetime-local" name="data_limite"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <p class="text-[10px] text-slate-500 mt-1">Deixe em branco se não houver um prazo estipulado.</p>
                    </div>
                </form>
            </div>
            <div
                class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('modalNovaAtividade').classList.add('hidden')"
                    class="flex-1 py-2.5 px-4 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl transition-colors">Cancelar</button>
                <button type="submit" form="formNovaAtividade"
                    class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors">Publicar
                    Atividade</button>
            </div>
        </div>
    </div>

    {{-- MODAL EDIÇÃO ATIVIDADE --}}
    <div id="modalEditAtividade"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-surface-dark w-full max-w-lg rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div
                class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 shrink-0">
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Editar Postagem</h3>
                <button type="button" onclick="document.getElementById('modalEditAtividade').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form id="formEditAtividade" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Título da
                            Atividade/Aviso *</label>
                        <input type="text" name="titulo" id="edit_titulo" required
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Descrição /
                            Instruções (Opcional)</label>
                        <textarea name="descricao" id="edit_descricao" rows="4"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Substituir Arquivo
                            Anexo (Opcional)</label>
                        <input type="file" name="arquivo"
                            class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 transition-colors">
                        <p class="text-[10px] text-slate-500 mt-1">Se não selecionar nada, o arquivo atual será mantido.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Link Externo
                            (Opcional)</label>
                        <input type="url" name="link_externo" id="edit_link"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Prazo / Data Limite
                            (Opcional)</label>
                        <input type="datetime-local" name="data_limite" id="edit_data"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </form>
            </div>
            <div
                class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex gap-3 shrink-0">
                <button type="button" onclick="document.getElementById('modalEditAtividade').classList.add('hidden')"
                    class="flex-1 py-2.5 px-4 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl transition-colors">Cancelar</button>
                <button type="submit" form="formEditAtividade"
                    class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors">Salvar
                    Alterações</button>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, titulo, descricao, link, data) {
            document.getElementById('edit_titulo').value = titulo;
            document.getElementById('edit_descricao').value = descricao;
            document.getElementById('edit_link').value = link;
            document.getElementById('edit_data').value = data;

            let form = document.getElementById('formEditAtividade');
            let baseUrl = "{{ route('professor.atividades.update', 1000) }}";
            form.action = baseUrl.replace('1000', id);

            document.getElementById('modalEditAtividade').classList.remove('hidden');
        }
    </script>
@endsection
