@extends('layouts.admin')

@section('title', 'Editar Aluno')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                <i class="fa-solid fa-user-pen mr-2"></i>Editar Aluno
            </h2>
            <a href="{{ route('alunos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-500 hover:bg-slate-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <!-- Formulário -->
        <div class="bg-white dark:bg-surface-dark shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <form action="{{ route('alunos.update', $aluno->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ $aluno->nome }}"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nome')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- CPF -->
                    <div>
                        <label for="cpf"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">CPF</label>
                        <div x-data>
                            <input type="text" name="cpf" id="cpf" value="{{ $aluno->cpf }}"
                                x-mask="999.999.999-99"
                                class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('cpf')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ $aluno->email }}"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label for="telefone_celular"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telefone</label>
                        <div x-data>
                            <input type="text" name="telefone_celular" id="telefone_celular"
                                value="{{ $aluno->telefone_celular }}" x-mask="(99) 99999-9999"
                                class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('telefone_celular')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tipo de Aluno / Curso -->
                    <div>
                        <label for="tipo_aluno" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tipo de
                            Aluno / Curso</label>
                        <select id="tipo_aluno" name="tipo_aluno" onchange="updateMensalidade()" required
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione</option>
                            <option value="ativo"
                                {{ old('tipo_aluno', $aluno->tipo_aluno) == 'ativo' ? 'selected' : '' }}>Aluno Ativo
                            </option>
                            <option value="egresso"
                                {{ old('tipo_aluno', $aluno->tipo_aluno) == 'egresso' ? 'selected' : '' }}>Aluno Egresso
                            </option>
                            <option value="externo"
                                {{ old('tipo_aluno', $aluno->tipo_aluno) == 'externo' ? 'selected' : '' }}>Aluno Externo
                            </option>
                        </select>
                        @error('tipo_aluno')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Valor da Mensalidade -->
                    <div>
                        <label for="valor_mensalidade"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Valor da Mensalidade
                            *</label>
                        <input type="text" id="valor_mensalidade" name="valor_mensalidade"
                            value="{{ old('valor_mensalidade', $aluno->valor_mensalidade) }}" readonly
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-not-allowed">
                        @error('valor_mensalidade')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status de Pagamento -->
                    <div>
                        <label for="status_pagamento"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status Financeiro</label>
                        <select id="status_pagamento" name="status_pagamento"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pago"
                                {{ old('status_pagamento', $aluno->status_pagamento) == 'pago' ? 'selected' : '' }}>Pago
                                Confirmado</option>
                            <option value="pendente"
                                {{ old('status_pagamento', $aluno->status_pagamento) == 'pendente' ? 'selected' : '' }}>
                                Pendente</option>
                            <option value="atrasado"
                                {{ old('status_pagamento', $aluno->status_pagamento) == 'atrasado' ? 'selected' : '' }}>
                                Atrasado / Inadimplente</option>
                        </select>
                    </div>

                    <!-- Login -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Login no
                            Sistema</label>
                        <input type="text" name="login" id="login" value="{{ $aluno->login }}"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('login')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="senha" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nova
                            Senha</label>
                        <input type="password" name="senha" id="senha"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Deixe em branco para manter a senha">
                        @error('senha')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="senha_confirmation"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Corfirmar Nova
                            Senha</label>
                        <input type="password" name="senha_confirmation" id="senha_confirmation"
                            class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-black dark:text-white font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Repita a nova senha">
                    </div>
                </div>

                <!-- Botões -->
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
                    <a href="{{ route('alunos.index') }}"
                        class="px-5 py-2 rounded-lg font-medium bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg font-medium bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-500/20 transition-all">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
        <script>
            const mensalidades = {
                ativo: 150.00,
                egresso: 200.00,
                externo: 250.00
            };

            function updateMensalidade() {
                const tipoAluno = document.getElementById('tipo_aluno').value;
                const valorMensalidadeInput = document.getElementById('valor_mensalidade');

                // Define o valor da mensalidade baseado no tipo de aluno
                if (mensalidades[tipoAluno]) {
                    valorMensalidadeInput.value = mensalidades[tipoAluno].toFixed(2); // Formato americano
                } else {
                    valorMensalidadeInput.value = ''; // Limpa o campo
                }
            }

            // Corrige o valor antes de submeter o formulário
            document.querySelector('form').addEventListener('submit', function() {
                const valorMensalidadeInput = document.getElementById('valor_mensalidade');
                if (valorMensalidadeInput.value) {
                    valorMensalidadeInput.value = valorMensalidadeInput.value.replace(',',
                        '.'); // Substitui vírgulas por pontos
                }
            });

            // Atualiza a mensalidade ao carregar a página (se o tipo já estiver selecionado)
            document.addEventListener('DOMContentLoaded', () => {
                updateMensalidade();
            });
        </script>
    </div>
    </div>
@endsection
