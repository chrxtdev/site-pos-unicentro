@extends('layouts.admin')

@section('title', 'Lista de Alunos')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                <i class="fa-solid fa-users mr-2"></i>Lista de Alunos
            </h2>

            <div class="flex items-center gap-4">
                <form action="{{ route('alunos.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar por Nome, CPF ou E-mail..."
                        class="w-full sm:w-80 pl-10 pr-4 py-2 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    @if (isset($search))
                        <a href="{{ route('alunos.index') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-red-500">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
                <span class="text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $alunos->total() }}
                    registros</span>
            </div>
        </div>

        {{-- Mensagem de sucesso --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fa-solid fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nome</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                E-mail</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Curso</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pgto</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($alunos as $aluno)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $aluno->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $aluno->nome }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $aluno->cpf }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $aluno->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $aluno->pos_graduacao }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($aluno->status_pagamento === 'pago')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                            <i class="fa-solid fa-check-circle text-[10px]"></i> Pago
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.impersonate', $aluno->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors border border-emerald-200 dark:border-emerald-800"
                                            title="Acessar como Aluno">
                                            <i class="fa-solid fa-user-lock"></i> Acessar
                                        </a>
                                        @if ($aluno->asaas_payment_id)
                                            <button
                                                onclick="openModal('{{ route('boleto.reimprimir', $aluno->asaas_payment_id) }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900/50 transition-colors border border-gray-200 dark:border-gray-800"
                                                title="Reimprimir Boleto">
                                                <i class="fa-solid fa-print"></i> Boleto
                                            </button>
                                        @endif
                                        <a href="{{ route('alunos.edit', $aluno->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                                            title="Editar Aluno">
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-6 mb-4">
                                            <i class="fa-solid fa-user-slash text-4xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-1">Nenhum aluno
                                            encontrado</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Os alunos aparecerão aqui após
                                            se inscreverem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if ($alunos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $alunos->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
    </div>

    {{-- Modal de Boleto --}}
    <div id="modal" class="fixed inset-0 z-50 hidden bg-gray-900/75 flex items-center justify-center"
        onclick="if(event.target===this)closeModal()">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-11/12 md:w-3/5 lg:w-2/5 max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <i class="fa-solid fa-barcode"></i> Boleto
                </h2>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <iframe id="boleto-iframe" src=""
                class="w-full h-96 rounded-lg border border-gray-200 dark:border-gray-700"></iframe>
        </div>
    </div>

    <script>
        function openModal(url) {
            document.getElementById('boleto-iframe').src = url;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('boleto-iframe').src = '';
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection
