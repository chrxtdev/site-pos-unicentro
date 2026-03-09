@extends('layouts.admin')

@section('title', 'Gerenciar Ofertas')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                <i class="fa-solid fa-tags mr-2"></i>Gerenciar Ofertas de Vagas por Curso
            </h1>
        </div>

        {{-- Mensagens de Feedback --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Formulário de Criação/Edição --}}
        <div
            class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                        <i class="fa-solid fa-plus text-indigo-500"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ isset($oferta) ? 'Editar Oferta' : 'Nova Oferta de Vaga' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Preencha os dados abaixo para
                            {{ isset($oferta) ? 'atualizar a oferta' : 'cadastrar uma nova oferta' }}.</p>
                    </div>
                </div>
            </div>

            <form action="{{ isset($oferta) ? route('ofertas.update', $oferta) : route('ofertas.store') }}" method="POST"
                class="p-6 space-y-5">
                @csrf
                @if (isset($oferta))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Processo Seletivo --}}
                    <div>
                        <label for="processo_seletivo_id"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Processo Seletivo
                            *</label>
                        <select name="processo_seletivo_id" id="processo_seletivo_id"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="" disabled selected>-- Selecione um Processo Seletivo --</option>
                            @foreach ($processosSeletivos as $processo)
                                <option value="{{ $processo->id }}"
                                    {{ old('processo_seletivo_id', $oferta->processo_seletivo_id ?? '') == $processo->id ? 'selected' : '' }}>
                                    {{ $processo->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('processo_seletivo_id')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Curso --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="curso_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Curso
                                *</label>
                            <button type="button" onclick="toggleNovoCurso()"
                                class="text-xs font-medium text-indigo-500 hover:text-indigo-400 transition-colors cursor-pointer flex items-center gap-1"
                                id="btnNovoCurso">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                Novo Curso
                            </button>
                        </div>

                        {{-- Select normal --}}
                        <div id="selectCursoWrapper">
                            <select name="curso_id" id="curso_id"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="" disabled>-- Selecione um Curso --</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}"
                                        {{ (int) old('curso_id', $oferta->curso_id ?? '') === $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Formulário inline de criação rápida --}}
                        <div id="novoCursoForm"
                            class="hidden mt-2 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 space-y-3">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-wand-magic-sparkles text-indigo-400 text-xs"></i>
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Criação Rápida de
                                    Curso</span>
                            </div>
                            <input type="text" id="novoCursoNome" placeholder="Nome do curso"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <select id="novoCursoTipo"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Tipo --</option>
                                <option value="Graduação">Graduação</option>
                                <option value="Pós-Graduação">Pós-Graduação</option>
                                <option value="Mestrado">Mestrado</option>
                                <option value="Doutorado">Doutorado</option>
                            </select>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="salvarNovoCurso()"
                                    class="flex-1 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3 py-2 rounded-lg transition-all cursor-pointer flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-check"></i> Salvar Curso
                                </button>
                                <button type="button" onclick="toggleNovoCurso()"
                                    class="text-xs font-medium text-slate-500 hover:text-slate-300 bg-slate-200 dark:bg-slate-700 px-3 py-2 rounded-lg transition-all cursor-pointer">
                                    Cancelar
                                </button>
                            </div>
                            <p id="novoCursoMsg" class="text-xs hidden"></p>
                        </div>

                        @error('curso_id')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Turno --}}
                    <div>
                        <label for="turno"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Turno *</label>
                        <select name="turno" id="turno"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="" disabled>-- Selecione um Turno --</option>
                            <option value="Manhã" {{ old('turno', $oferta->turno ?? '') == 'Manhã' ? 'selected' : '' }}>
                                Manhã</option>
                            <option value="Tarde" {{ old('turno', $oferta->turno ?? '') == 'Tarde' ? 'selected' : '' }}>
                                Tarde</option>
                            <option value="Noturno"
                                {{ old('turno', $oferta->turno ?? '') == 'Noturno' ? 'selected' : '' }}>Noturno</option>
                        </select>
                        @error('turno')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantidade de Vagas --}}
                    <div>
                        <label for="quantidade_vagas"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Quantidade de Vagas
                            *</label>
                        <input type="number" name="quantidade_vagas" id="quantidade_vagas"
                            value="{{ old('quantidade_vagas', $oferta->quantidade_vagas ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: 40">
                        @error('quantidade_vagas')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Locais de Prova --}}
                    <div>
                        <label for="locais_prova"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Locais de Prova
                            *</label>
                        <input type="text" name="locais_prova" id="locais_prova"
                            value="{{ old('locais_prova', $oferta->locais_prova ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: Campus Central - Bloco A">
                        @error('locais_prova')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Valor da Taxa --}}
                    <div>
                        <label for="valor_taxa"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Valor da Taxa de
                            Inscrição</label>
                        <input type="text" name="valor_taxa" id="valor_taxa"
                            value="{{ old('valor_taxa', $oferta->valor_taxa ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: 150.00">
                        @error('valor_taxa')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data de Vencimento --}}
                    <div>
                        <label for="data_vencimento_taxa"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Data de Vencimento
                            da Taxa</label>
                        <input type="date" name="data_vencimento_taxa" id="data_vencimento_taxa"
                            value="{{ old('data_vencimento_taxa', $oferta->data_vencimento_taxa ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('data_vencimento_taxa')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Conta de Recebimento --}}
                    <div>
                        <label for="conta_recebimento"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Conta de
                            Recebimento</label>
                        <input type="text" name="conta_recebimento" id="conta_recebimento"
                            value="{{ old('conta_recebimento', $oferta->conta_recebimento ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: Banco do Brasil - Ag 1234">
                        @error('conta_recebimento')
                            <p class="mt-1.5 text-xs text-red-500"><i
                                    class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-{{ isset($oferta) ? 'save' : 'plus' }}"></i>
                    {{ isset($oferta) ? 'Atualizar Oferta' : 'Cadastrar Oferta' }}
                </button>
            </form>
        </div>

        {{-- Listagem de Ofertas --}}
        <div
            class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                        <i class="fa-solid fa-list text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Ofertas Cadastradas</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $ofertas->total() }}
                            {{ $ofertas->total() === 1 ? 'oferta encontrada' : 'ofertas encontradas' }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Curso</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Turno</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($ofertas as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $item->curso->nome }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $item->turno === 'Manhã' ? 'bg-amber-500/10 text-amber-500' : ($item->turno === 'Tarde' ? 'bg-blue-500/10 text-blue-500' : 'bg-indigo-500/10 text-indigo-400') }}">
                                        <i
                                            class="fa-solid fa-{{ $item->turno === 'Manhã' ? 'sun' : ($item->turno === 'Tarde' ? 'cloud-sun' : 'moon') }} text-[10px]"></i>
                                        {{ $item->turno }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('ofertas.duplicate', $item) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs font-medium text-blue-500 hover:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                                                <i class="fa-solid fa-copy text-[10px]"></i>
                                                Duplicar
                                            </button>
                                        </form>
                                        <a href="{{ route('ofertas.edit', $item) }}"
                                            class="text-xs font-medium text-amber-500 hover:text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                            Editar
                                        </a>
                                        <form action="{{ route('ofertas.destroy', $item) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta oferta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs font-medium text-red-500 hover:text-red-400 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <i class="fa-solid fa-inbox text-3xl text-slate-300 dark:text-slate-600 mb-3"></i>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Nenhuma oferta cadastrada.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ofertas->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $ofertas->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleNovoCurso() {
            const form = document.getElementById('novoCursoForm');
            const select = document.getElementById('selectCursoWrapper');
            const btn = document.getElementById('btnNovoCurso');
            const isHidden = form.classList.contains('hidden');

            form.classList.toggle('hidden');
            select.classList.toggle('hidden');
            btn.innerHTML = isHidden ?
                '<i class="fa-solid fa-list text-[10px]"></i> Selecionar Existente' :
                '<i class="fa-solid fa-plus text-[10px]"></i> Novo Curso';
        }

        function salvarNovoCurso() {
            const nome = document.getElementById('novoCursoNome').value.trim();
            const tipo = document.getElementById('novoCursoTipo').value;
            const msg = document.getElementById('novoCursoMsg');

            if (!nome || !tipo) {
                msg.textContent = 'Preencha o nome e o tipo do curso.';
                msg.className = 'text-xs text-red-500 mt-1';
                msg.classList.remove('hidden');
                return;
            }

            msg.textContent = 'Salvando...';
            msg.className = 'text-xs text-indigo-400 mt-1';
            msg.classList.remove('hidden');

            fetch('{{ route('cursos.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        nome,
                        tipo
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('curso_id');
                        const option = new Option(data.curso.nome, data.curso.id, true, true);
                        select.appendChild(option);

                        toggleNovoCurso();

                        document.getElementById('novoCursoNome').value = '';
                        document.getElementById('novoCursoTipo').selectedIndex = 0;
                        msg.classList.add('hidden');
                    } else {
                        msg.textContent = data.message || 'Erro ao salvar curso.';
                        msg.className = 'text-xs text-red-500 mt-1';
                    }
                })
                .catch(() => {
                    msg.textContent = 'Erro de conexão. Tente novamente.';
                    msg.className = 'text-xs text-red-500 mt-1';
                });
        }
    </script>
@endpush
