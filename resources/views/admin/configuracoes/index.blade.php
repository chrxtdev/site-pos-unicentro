@extends('layouts.admin')

@section('title', 'Configurações do Sistema')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                <i class="fa-solid fa-gear mr-2"></i>Configurações do Sistema
            </h2>
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

        {{-- Tabs Navigation --}}
        <div class="flex border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
            <button type="button" onclick="switchTab('admin')" id="btn-admin"
                class="tab-btn px-6 py-3 font-bold text-sm border-b-2 text-indigo-500 border-indigo-500 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-user-shield mr-2"></i> Administradores
            </button>
            <button type="button" onclick="switchTab('inst')" id="btn-inst"
                class="tab-btn px-6 py-3 font-semibold text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white whitespace-nowrap transition-colors">
                <i class="fa-solid fa-building mr-2"></i> Instituição
            </button>
            <button type="button" onclick="switchTab('integ')" id="btn-integ"
                class="tab-btn px-6 py-3 font-semibold text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white whitespace-nowrap transition-colors">
                <i class="fa-solid fa-plug mr-2"></i> Integrações e Webhook
            </button>
        </div>

        {{-- ABA 1: Administradores --}}
        <div id="tab-admin" class="tab-content block">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Formulário: Criar Novo Admin --}}
                <div
                    class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-fit">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-user-plus text-indigo-500"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Criar Novo Administrador
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Adicione um novo usuário com acesso
                                    administrativo</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('configuracoes.storeAdmin') }}" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nome
                                Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                autocomplete="off"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase"
                                placeholder="NOME DO ADMINISTRADOR" oninput="this.value = this.value.toUpperCase();">
                        </div>

                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">E-mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                autocomplete="off"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="admin@escola.com.br">
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Senha</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="Mínimo 8 caracteres">
                        </div>

                        <div>
                            <label for="role"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nível de Acesso</label>
                            <select name="role" id="role" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="" disabled selected>Selecione um perfil...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ strtoupper(str_replace('_', ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                            <i class="fa-solid fa-plus"></i> Criar Administrador
                        </button>
                    </form>
                </div>

                {{-- Lista de Admins --}}
                <div
                    class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-fit">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-users-gear text-emerald-500"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Administradores
                                    Cadastrados</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $admins->count() }}
                                    {{ $admins->count() === 1 ? 'administrador' : 'administradores' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[400px] overflow-y-auto">
                        @forelse($admins as $admin)
                            <div
                                class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $admin->name }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $admin->email }}</p>
                                            @if($admin->roles->count() > 0)
                                                <span class="text-[10px] uppercase font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100">
                                                    {{ str_replace('_', ' ', $admin->roles->first()->name) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if ($admin->id === auth()->id())
                                        <span
                                            class="text-xs font-medium bg-indigo-500/10 text-indigo-500 px-3 py-1 rounded-full">Você</span>
                                    @else
                                        @php
                                            $adminRoleName = $admin->roles->count() > 0 ? $admin->roles->first()->name : '';
                                        @endphp
                                        <button type="button"
                                            onclick="openEditAdminModal({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ addslashes($admin->email) }}', '{{ $adminRoleName }}')"
                                            class="opacity-0 group-hover:opacity-100 text-xs font-medium text-amber-500 hover:text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i> Editar
                                        </button>
                                        <form method="POST" action="{{ route('configuracoes.destroyAdmin', $admin) }}"
                                            class="inline" onsubmit="return confirm('Remover {{ $admin->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="opacity-0 group-hover:opacity-100 text-xs font-medium text-red-500 hover:text-red-400 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i> Remover
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <p class="text-sm text-slate-500">Nenhum cadstrado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ABA 2: Instituição --}}
        <div id="tab-inst" class="tab-content hidden">
            <div
                class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden max-w-4xl mx-auto">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-building text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Dados da Instituição</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Personalize os dados de atendimento aos
                                alunos.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('configuracoes.storeAppConfig') }}" class="p-6 space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nome
                                Fantasia (Aparecerá nos Portais)</label>
                            <input type="text" name="configs[inst_nome]"
                                value="{{ get_config('inst_nome', 'Unicentro Educacional') }}"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> WhatsApp de Suporte
                            </label>
                            <input type="text" name="configs[inst_whatsapp]"
                                value="{{ get_config('inst_whatsapp', '') }}" placeholder="Ex: 11 99999-9999"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">E-mail de
                                Suporte</label>
                            <input type="email" name="configs[inst_email]" value="{{ get_config('inst_email', '') }}"
                                placeholder="suporte@instituicao.com.br"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit"
                            class="flex items-center justify-center gap-2 py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                            <i class="fa-solid fa-save"></i> Salvar Dados
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ABA 3: Integrações --}}
        <div id="tab-integ" class="tab-content hidden">
            <div
                class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden max-w-4xl mx-auto">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-plug-circle-bolt text-amber-500"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Integrações de Parceiros
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Tokens de API do Banco e plataformas de
                                envio.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('configuracoes.storeAppConfig') }}" class="p-6 space-y-6">
                    @csrf
                    {{-- ASAAS --}}
                    <div
                        class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-building-columns text-blue-500"></i> Asaas (Pagamentos)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-400 mb-1.5">Chave de
                                    API (Secret Token)</label>
                                <div class="relative">
                                    <input type="password" name="configs[asaas_token]" id="asaas_token"
                                        value="{{ get_config('asaas_token', '') }}" placeholder="ak_xxxxxxxxxxxxxxxxx"
                                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-mono tracking-widest pl-4 pr-10">
                                    <button type="button" onclick="togglePassword('asaas_token')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                        <i class="fa-solid fa-eye" id="icon_asaas_token"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CLOUDSERVO --}}
                    <div
                        class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-regular fa-paper-plane text-emerald-500"></i> CloudServo (Mensagens Automáticas)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-400 mb-1.5">Token de
                                    Acesso</label>
                                <div class="relative">
                                    <input type="password" name="configs[cloudservo_token]" id="cloudservo_token"
                                        value="{{ get_config('cloudservo_token', '') }}"
                                        placeholder="Insira o Token do CloudServo"
                                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono tracking-widest pl-4 pr-10">
                                    <button type="button" onclick="togglePassword('cloudservo_token')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                        <i class="fa-solid fa-eye" id="icon_cloudservo_token"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit"
                            class="flex items-center justify-center gap-2 py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                            <i class="fa-solid fa-save"></i> Salvar Integrações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal de Editar Admin (Oculto por padrão) --}}
        <div id="editAdminModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
            <div id="editAdminModalContent"
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300 overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white"><i
                            class="fa-solid fa-user-pen text-indigo-500 mr-2"></i>Editar Administrador</h3>
                    <button type="button" onclick="closeEditAdminModal()"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="editAdminForm" method="POST" action="" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nome
                            Completo</label>
                        <input type="text" name="name" id="edit_admin_name" required autocomplete="off"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">E-mail</label>
                        <input type="email" name="email" id="edit_admin_email" required autocomplete="off"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nova Senha <span
                                class="text-xs text-slate-400 font-normal">(opcional)</span></label>
                        <input type="password" name="password" autocomplete="new-password"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Deixe branco se não for alterar">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nível de Acesso <span
                                class="text-xs text-slate-400 font-normal">(opcional)</span></label>
                        <select name="role" id="edit_admin_role"
                            class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Manter atual</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ strtoupper(str_replace('_', ' ', $role->name)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="closeEditAdminModal()"
                            class="flex-1 py-2.5 px-4 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-xl transition-colors">Cancelar</button>
                        <button type="submit"
                            class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-transform hover:-translate-y-0.5">Salvar
                            Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabId) {
            // Esconder todos os conteúdos
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });

            // Mostrar o requisitado
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.add('block');

            // Resetar estilos dos botões
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('font-bold', 'text-indigo-500', 'border-indigo-500');
                btn.classList.add('font-semibold', 'text-slate-500', 'border-transparent');
            });

            // Ativar o botão clicado
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('font-semibold', 'text-slate-500', 'border-transparent');
            activeBtn.classList.add('font-bold', 'text-indigo-500', 'border-indigo-500');
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('icon_' + inputId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function openEditAdminModal(id, name, email, roleName = '') {
            document.getElementById('edit_admin_name').value = name;
            document.getElementById('edit_admin_email').value = email;
            document.getElementById('edit_admin_role').value = roleName;

            let form = document.getElementById('editAdminForm');
            let baseUrl = "{{ route('configuracoes.destroyAdmin', 1000) }}";
            form.action = baseUrl.replace('1000', id).replace('destroyAdmin', 'updateAdmin');

            let modal = document.getElementById('editAdminModal');
            let modalContent = document.getElementById('editAdminModalContent');

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeEditAdminModal() {
            let modal = document.getElementById('editAdminModal');
            let modalContent = document.getElementById('editAdminModalContent');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
    </script>
@endpush
