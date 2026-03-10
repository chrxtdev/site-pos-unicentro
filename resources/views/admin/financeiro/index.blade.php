@extends('layouts.admin')

@section('title', 'Gestão Financeira')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-coins text-primary"></i> Gestão Financeira
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Acompanhamento de matrículas, pagamentos e faturas dos
                alunos.</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-surface-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 mb-6">
        <form method="GET" action="{{ route('financeiro.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buscar por
                    Aluno</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="pl-10 block w-full bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 rounded-lg focus:ring-primary focus:border-primary sm:text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                        placeholder="Nome ou e-mail...">
                </div>
            </div>

            <div>
                <label for="curso"
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Curso</label>
                <select name="curso" id="curso"
                    class="block w-full bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 rounded-lg focus:ring-primary focus:border-primary sm:text-sm text-slate-900 dark:text-white">
                    <option value="">Todos os Cursos</option>
                    @foreach (\App\Models\Curso::orderBy('nome')->get() as $c)
                        <option value="{{ $c->nome }}" {{ request('curso') == $c->nome ? 'selected' : '' }}>
                            {{ \Illuminate\Support\Str::limit($c->nome, 30) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status_pagamento"
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Matrícula</label>
                <select name="status_pagamento" id="status_pagamento"
                    class="block w-full bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 rounded-lg focus:ring-primary focus:border-primary sm:text-sm text-slate-900 dark:text-white">
                    <option value="">Todos os Status</option>
                    <option value="pago" {{ request('status_pagamento') == 'pago' ? 'selected' : '' }}>Confirmado (Pago)
                    </option>
                    <option value="pendente" {{ request('status_pagamento') == 'pendente' ? 'selected' : '' }}>Pendente
                    </option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-slate-800 dark:bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 dark:hover:bg-primary/90 transition relative top-[1px]">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrar
                </button>
                @if (request()->anyFilled(['search', 'status_pagamento', 'curso']))
                    <a href="{{ route('financeiro.index') }}"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition relative top-[1px] flex items-center justify-center"
                        title="Limpar Filtros">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <div
        class="bg-white dark:bg-surface-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Aluno e Curso
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Data Matrícula
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Mensalidade
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Status Inscrição
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-surface-dark divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($alunos as $aluno)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        <div
                                            class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                                            {{ substr($aluno->nome, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div
                                            class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                            {{ $aluno->nome }}
                                            @if ($aluno->matricula)
                                                <span
                                                    class="text-[10px] bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded font-mono text-slate-500 border border-slate-200 dark:border-slate-700">RA:
                                                    {{ $aluno->matricula }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                            {{ $aluno->pos_graduacao }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ $aluno->created_at->format('d/m/Y') }}
                            </td>
                            <td
                                class="px-6 py-4 text-center whitespace-nowrap text-sm font-semibold text-slate-700 dark:text-slate-300">
                                R$ {{ number_format($aluno->valor_mensalidade, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if ($aluno->status_pagamento === 'pago')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        <i class="fa-solid fa-check mr-1.5"></i> Confirmado
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                        <i class="fa-regular fa-clock mr-1.5"></i> Pendente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('financeiro.show', $aluno->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary dark:hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Ver Faturas
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                <i class="fa-solid fa-search text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-lg font-medium text-slate-600 dark:text-slate-300">Nenhum aluno encontrado.
                                </p>
                                <p class="text-sm">Tente ajustar seus termos de busca ou filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($alunos->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                {{ $alunos->links() }}
            </div>
        @endif
    </div>

@endsection
