@extends('layouts.admin')

@section('title', 'Gerenciar Cursos')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                <i class="fa-solid fa-graduation-cap mr-2"></i>Gerenciar Cursos
            </h1>

            <div class="flex items-center gap-4">
                <form action="{{ route('cursos.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar curso ou tipo..."
                        class="w-full sm:w-72 pl-10 pr-10 py-2.5 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                    </div>
                    @if (isset($search) && $search)
                        <a href="{{ route('cursos.index') }}"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
                <span
                    class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-full">
                    {{ $cursos->total() }} {{ $cursos->total() === 1 ? 'curso' : 'cursos' }}
                </span>
            </div>
        </div>

        {{-- Mensagens de Feedback --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <div class="text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Formulário de Criação/Edição --}}
        <div
            class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                        <i class="fa-solid fa-{{ isset($curso) ? 'pen' : 'plus' }} text-indigo-500"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ isset($curso) ? 'Editar Curso' : 'Novo Curso' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ isset($curso) ? 'Altere os dados e salve.' : 'Preencha os dados para cadastrar um novo curso.' }}
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ isset($curso) ? route('cursos.update', $curso) : route('cursos.store') }}" method="POST"
                class="p-6 space-y-5">
                @csrf
                @if (isset($curso))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nome"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nome do Curso
                            *</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome', $curso->nome ?? '') }}"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: Direito Trabalhista">
                    </div>
                    <div>
                        <label for="tipo"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tipo do Curso
                            *</label>
                        <select name="tipo" id="tipo"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="" disabled {{ !isset($curso) ? 'selected' : '' }}>-- Selecione um Tipo --
                            </option>
                            @foreach (['Graduação', 'Pós-Graduação', 'Mestrado', 'Doutorado'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ old('tipo', $curso->tipo ?? '') == $tipo ? 'selected' : '' }}>{{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    @if (isset($curso))
                        <a href="{{ route('cursos.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                            Cancelar
                        </a>
                    @endif
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 cursor-pointer">
                        <i class="fa-solid fa-{{ isset($curso) ? 'save' : 'plus' }}"></i>
                        {{ isset($curso) ? 'Atualizar Curso' : 'Cadastrar Curso' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Listagem de Cursos --}}
        <div
            class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                        <i class="fa-solid fa-list text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Cursos Cadastrados</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $cursos->total() }}
                            {{ $cursos->total() === 1 ? 'curso encontrado' : 'cursos encontrados' }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Nome do Curso</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Tipo</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($cursos as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-book text-indigo-400 text-xs"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $item->nome }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $item->tipo === 'Graduação'
                                            ? 'bg-blue-500/10 text-blue-500'
                                            : ($item->tipo === 'Pós-Graduação'
                                                ? 'bg-emerald-500/10 text-emerald-500'
                                                : ($item->tipo === 'Mestrado'
                                                    ? 'bg-amber-500/10 text-amber-500'
                                                    : 'bg-rose-500/10 text-rose-400')) }}">
                                        {{ $item->tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('cursos.edit', $item) }}"
                                            class="text-xs font-medium text-amber-500 hover:text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                            Editar
                                        </a>
                                        <form action="{{ route('cursos.destroy', $item) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Tem certeza que deseja excluir o curso \'{{ $item->nome }}\'?')">
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
                                    <i
                                        class="fa-solid fa-graduation-cap text-3xl text-slate-300 dark:text-slate-600 mb-3"></i>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum curso cadastrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($cursos->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $cursos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
