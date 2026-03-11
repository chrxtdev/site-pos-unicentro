<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição Pós-Graduação | UNICENTRO MA</title>
    <meta name="description"
        content="Faça agora sua inscrição na Pós-Graduação da UNICENTRO. Escolha seu curso, a forma de pagamento e dê o próximo passo na sua carreira profissional.">

    <!-- Open Graph -->
    <meta property="og:title" content="Inscrição Pós-Graduação UNICENTRO">
    <meta property="og:description" content="Acesse o portal e realize sua matrícula de forma rápida 100% online.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('inscricao.index') }}">
    <meta property="og:image" content="{{ asset('images/unicentroma.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('inscricao.index') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body
    class="bg-slate-50 dark:bg-slate-900 min-h-screen flex flex-col font-sans text-slate-900 dark:text-slate-100 selection:bg-blue-500 selection:text-white">

    <!-- Header -->
    <header
        class="bg-blue-900 dark:bg-slate-950 text-white p-4 flex items-center justify-between shadow-lg relative z-20">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/unicentroma.png') }}" alt="Unicentro MA" class="h-12 drop-shadow-md">
        </div>
        <h1 class="text-center text-xl md:text-2xl flex-grow font-semibold tracking-tight">
            Inscrição na Pós-Graduação
        </h1>
        <a href="{{ route('login') }}"
            class="text-sm font-medium text-blue-200 hover:text-white hover:underline transition hidden md:block">Portal
            do Aluno</a>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 md:p-8 relative overflow-hidden">

        <!-- Efeitos de Fundo (Glow) -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-500/20 dark:bg-blue-600/20 blur-[120px] rounded-full pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500/20 dark:bg-blue-600/20 blur-[120px] rounded-full pointer-events-none">
        </div>

        <div class="w-full max-w-4xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl shadow-slate-200/50 dark:shadow-slate-900/50 ring-1 ring-slate-200 dark:ring-slate-700 overflow-hidden relative z-10"
            x-data="{
                step: 1,
                maxStep: 3,
                formaPagamento: 'boleto',
            
                cursosData: [],
                selectedCursoId: '',
                selectedOfertaId: '',
            
                async loadCursos() {
                    try {
                        const response = await fetch('/api/ofertas-disponiveis');
                        this.cursosData = await response.json();
                    } catch (error) {
                        console.error('Erro ao buscar cursos:', error);
                    }
                },
            
                get cursoResumo() {
                    if (!this.selectedCursoId) return null;
                    return this.cursosData.find(c => c.id == this.selectedCursoId);
                },
            
                get ofertaResumo() {
                    if (!this.selectedOfertaId || !this.cursoResumo) return null;
                    return this.cursoResumo.ofertas.find(o => o.id == this.selectedOfertaId);
                },
            
                init() {
                    this.loadCursos();
                },
            
                validateStep1() {
                    const reqFields = ['nome', 'cpf', 'email', 'data_nascimento', 'sexo', 'telefone_celular'];
                    return reqFields.every(id => document.getElementById(id).value.trim() !== '');
                },
                validateStep2() {
                    const reqFields = ['cep', 'endereco', 'bairro'];
                    return reqFields.every(id => document.getElementById(id).value.trim() !== '');
                }
            }">

            <!-- Header do Stepper -->
            <div
                class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 p-6 sm:p-8 relative">
                <div class="flex items-center justify-between max-w-2xl mx-auto relative">
                    <!-- Linha de Fundo -->
                    <div class="absolute inset-0 flex items-center justify-between z-0"
                        style="top: 50%; transform: translateY(-50%); padding: 0 10%;">
                        <div class="h-1 w-full bg-slate-200 dark:bg-slate-700 rounded-full"></div>
                    </div>
                    <!-- Linha de Progresso -->
                    <div class="absolute inset-0 flex items-center justify-between z-0"
                        style="top: 50%; transform: translateY(-50%); padding: 0 10%;">
                        <div class="h-1 bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-500 ease-out"
                            :style="'width: ' + ((step - 1) / (maxStep - 1)) * 100 + '%'"></div>
                    </div>

                    <!-- Passos -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 shadow-md"
                            :class="step >= 1 ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' :
                                'bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400'">
                            <i class="fa-solid fa-user" x-show="step < 2"></i>
                            <i class="fa-solid fa-check" x-show="step >= 2"></i>
                        </div>
                        <span class="text-xs sm:text-sm mt-3 font-semibold transition-colors uppercase tracking-wider"
                            :class="step >= 1 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'">Dados
                            Pessoais</span>
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 shadow-md"
                            :class="step >= 2 ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' :
                                'bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400'">
                            <i class="fa-solid fa-location-dot" x-show="step < 3"></i>
                            <i class="fa-solid fa-check" x-show="step >= 3"></i>
                        </div>
                        <span class="text-xs sm:text-sm mt-3 font-semibold transition-colors uppercase tracking-wider"
                            :class="step >= 2 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'">Endereço</span>
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 shadow-md"
                            :class="step >= 3 ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' :
                                'bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400'">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="text-xs sm:text-sm mt-3 font-semibold transition-colors uppercase tracking-wider"
                            :class="step >= 3 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-slate-400'">Curso
                            e Financeiro</span>
                    </div>
                </div>
            </div>

            <!-- Avisos -->
            @if (session('success'))
                <div
                    class="mx-6 mt-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="mx-6 mt-6 p-5 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        <p class="font-bold text-lg">Atenção!</p>
                    </div>
                    <p class="mb-2">Verifique os erros abaixo e tente novamente:</p>
                    <ul class="list-disc ml-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário -->
            <form action="{{ route('inscricao.store') }}" method="POST" autocomplete="off" class="p-6 sm:p-10">
                @csrf

                <!-- ================= ETAPA 1: Dados Pessoais ================= -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Informações Pessoais</h2>
                        <p class="text-slate-500 dark:text-slate-400">Precisamos de alguns dados essenciais para o seu
                            registro acadêmico.</p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <div class="md:col-span-2">
                            <label for="nome"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nome
                                Completo *</label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors uppercase px-4 py-2.5"
                                oninput="this.value = this.value.toUpperCase();">
                        </div>

                        <div>
                            <label for="cpf"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">CPF
                                *</label>
                            <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required
                                x-mask="999.999.999-99"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5"
                                placeholder="000.000.000-00">
                        </div>

                        <div>
                            <label for="telefone_celular"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">WhatsApp
                                /
                                Celular *</label>
                            <input type="tel" id="telefone_celular" name="telefone_celular"
                                value="{{ old('telefone_celular') }}" required x-mask="(99) 99999-9999"
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5"
                                placeholder="(00) 00000-0000">
                        </div>

                        <div class="md:col-span-2">
                            <label for="email"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">E-mail
                                Principal *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5"
                                oninput="document.getElementById('login').value = this.value;">
                        </div>

                        <div>
                            <label for="data_nascimento"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nascimento
                                *</label>
                            <input type="date" id="data_nascimento" name="data_nascimento"
                                value="{{ old('data_nascimento') }}" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5">
                        </div>

                        <div>
                            <label for="sexo"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sexo
                                *</label>
                            <select id="sexo" name="sexo" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5 cursor-pointer">
                                <option value="">Selecione</option>
                                <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino
                                </option>
                                <option value="feminino" {{ old('sexo') == 'feminino' ? 'selected' : '' }}>Feminino
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="estado_civil"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Estado
                                Civil *</label>
                            <select id="estado_civil" name="estado_civil" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5 cursor-pointer">
                                <option value="">Selecione</option>
                                <option value="solteiro" {{ old('estado_civil') == 'solteiro' ? 'selected' : '' }}>
                                    Solteiro(a)</option>
                                <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>
                                    Casado(a)</option>
                                <option value="divorciado"
                                    {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                <option value="viuvo" {{ old('estado_civil') == 'viuvo' ? 'selected' : '' }}>Viúvo(a)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="cor_raca"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Etnia
                                *</label>
                            <select id="cor_raca" name="cor_raca" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5 cursor-pointer">
                                <option value="">Selecione</option>
                                <option value="branca" {{ old('cor_raca') == 'branca' ? 'selected' : '' }}>Branca
                                </option>
                                <option value="preta" {{ old('cor_raca') == 'preta' ? 'selected' : '' }}>Preta
                                </option>
                                <option value="parda" {{ old('cor_raca') == 'parda' ? 'selected' : '' }}>Parda
                                </option>
                                <option value="indigena" {{ old('cor_raca') == 'indigena' ? 'selected' : '' }}>
                                    Indígena</option>
                                <option value="amarela" {{ old('cor_raca') == 'amarela' ? 'selected' : '' }}>Amarela
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <label for="ensino_medio"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Formação
                                Acadêmica Atual *</label>
                            <select id="ensino_medio" name="ensino_medio" required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5 cursor-pointer">
                                <option value="">Selecione a sua formação...</option>
                                <option value="superior_completo"
                                    {{ old('ensino_medio') == 'superior_completo' ? 'selected' : '' }}>Superior
                                    Completo</option>
                                <option value="superior_incompleto"
                                    {{ old('ensino_medio') == 'superior_incompleto' ? 'selected' : '' }}>Superior
                                    Incompleto</option>
                                <option value="pos-graduado"
                                    {{ old('ensino_medio') == 'pos-graduado' ? 'selected' : '' }}>Pós-Graduado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ================= ETAPA 2: Endereço ================= -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Informações de Localização
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400">Insira seu endereço de contato.</p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <div class="md:col-span-2 flex gap-4">
                            <div class="w-1/3">
                                <label for="cep"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">CEP
                                    *</label>
                                <input type="text" id="cep" name="cep" value="{{ old('cep') }}"
                                    required x-mask="99999-999"
                                    class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5"
                                    placeholder="00000-000">
                            </div>
                            <div class="flex-grow">
                                <label for="bairro"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Bairro
                                    *</label>
                                <input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}"
                                    required
                                    class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="endereco"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Logradouro
                                (Rua, Número, Comp.) *</label>
                            <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}"
                                required
                                class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors px-4 py-2.5"
                                placeholder="Sua rua e número residêncial">
                        </div>
                    </div>
                </div>

                <!-- ================= ETAPA 3: Curso e Confirmação ================= -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Finalização e Financeiro
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400">Escolha o seu curso e a forma ideal de pagamento.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card do Curso -->
                        <div
                            class="md:col-span-2 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                            <div class="mb-5">
                                <label for="curso_select"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pós-Graduação
                                    Desejada *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-book-open text-blue-500"></i>
                                    </div>
                                    <select id="curso_select" x-model="selectedCursoId" required
                                        class="w-full pl-10 rounded-xl border-blue-200 dark:border-blue-900 bg-blue-50/50 dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 font-medium py-3 cursor-pointer">
                                        <option value="">Selecione seu curso pretendido...</option>
                                        <template x-for="curso in cursosData" :key="curso.id">
                                            <option :value="curso.id" x-text="curso.nome"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div x-show="cursoResumo" x-transition>
                                <label for="oferta_select"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Turma /
                                    Modalidade *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-users text-blue-500"></i>
                                    </div>
                                    <select id="oferta_select" x-model="selectedOfertaId"
                                        :required="cursoResumo !== null"
                                        class="w-full pl-10 rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 cursor-pointer">
                                        <option value="">Selecione o turno e investimento...</option>
                                        <template x-for="oferta in (cursoResumo ? cursoResumo.ofertas : [])"
                                            :key="oferta.id">
                                            <option :value="oferta.id"
                                                x-text="oferta.turno + ' | Mensalidade: R$ ' + oferta.valor_taxa">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Card de Resumo do Curso Selecionado -->
                            <div class="mt-6" x-show="ofertaResumo"
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100">
                                <div
                                    class="p-5 border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-start gap-5 shadow-sm">
                                    <div
                                        class="text-emerald-600 dark:text-emerald-400 mt-1 bg-white dark:bg-slate-900 p-3 rounded-full shadow-sm ring-1 ring-emerald-100 dark:ring-emerald-800">
                                        <i class="fa-solid fa-graduation-cap text-2xl"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-emerald-900 dark:text-emerald-100 text-lg leading-tight"
                                            x-text="cursoResumo ? cursoResumo.nome : ''"></h4>
                                        <div class="mt-3 grid gap-2 text-sm text-slate-700 dark:text-slate-300">
                                            <div class="flex items-center gap-2">
                                                <i
                                                    class="fa-solid fa-clock text-emerald-600 dark:text-emerald-400 w-4"></i>
                                                <span><strong>Turma:</strong> <span
                                                        x-text="ofertaResumo ? ofertaResumo.turno : ''"></span></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i
                                                    class="fa-solid fa-coins text-emerald-600 dark:text-emerald-400 w-4"></i>
                                                <span><strong>Investimento:</strong> 12x de R$ <span
                                                        x-text="ofertaResumo ? ofertaResumo.valor_taxa : ''"
                                                        class="font-bold text-lg text-emerald-700 dark:text-emerald-400"></span>
                                                    / mês</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Formas Pagamento -->
                        <div class="md:col-span-2 mt-2" x-show="ofertaResumo" x-transition>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Forma de
                                Pagamento da Mensalidade (Carnê de 12 meses) *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Boleto -->
                                <label
                                    class="cursor-pointer relative flex flex-col items-center justify-center p-5 border-2 rounded-2xl transition-all duration-300 shadow-sm"
                                    :class="formaPagamento === 'boleto' ?
                                        'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-2 ring-blue-500/30 ring-offset-2 dark:ring-offset-slate-800' :
                                        'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/80 bg-white dark:bg-slate-900'">
                                    <input type="radio" name="_forma_pagamento_ui" value="boleto"
                                        x-model="formaPagamento" class="sr-only">
                                    <i class="fa-solid fa-barcode text-4xl mb-3"
                                        :class="formaPagamento === 'boleto' ? 'text-blue-600 dark:text-blue-400' :
                                            'text-slate-400 dark:text-slate-500'"></i>
                                    <span class="font-bold tracking-wide"
                                        :class="formaPagamento === 'boleto' ? 'text-blue-900 dark:text-blue-100' :
                                            'text-slate-600 dark:text-slate-400'">Boleto</span>
                                </label>

                                <!-- Pix -->
                                <label
                                    class="cursor-pointer relative flex flex-col items-center justify-center p-5 border-2 rounded-2xl transition-all duration-300 shadow-sm"
                                    :class="formaPagamento === 'pix' ?
                                        'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 ring-2 ring-emerald-500/30 ring-offset-2 dark:ring-offset-slate-800' :
                                        'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/80 bg-white dark:bg-slate-900'">
                                    <input type="radio" name="_forma_pagamento_ui" value="pix"
                                        x-model="formaPagamento" class="sr-only">
                                    <i class="fa-brands fa-pix text-4xl mb-3"
                                        :class="formaPagamento === 'pix' ? 'text-emerald-600 dark:text-emerald-400' :
                                            'text-slate-400 dark:text-slate-500'"></i>
                                    <span class="font-bold tracking-wide"
                                        :class="formaPagamento === 'pix' ? 'text-emerald-900 dark:text-emerald-100' :
                                            'text-slate-600 dark:text-slate-400'">Pix</span>
                                </label>


                                <!-- Mensagem Explicativa -->
                                <div
                                    class="col-span-1 sm:col-span-2 mt-2 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 shadow-sm border border-blue-100 dark:border-blue-800 flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                        <i class="fa-solid fa-receipt text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-blue-900 dark:text-blue-100">Faturamento
                                            via Carnê Digital</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                            A sua mensalidade será faturada no formato de carnê mensal (12 parcelas). O
                                            1º vencimento ocorrerá 5 dias após a confirmação da sua matrícula. Você
                                            poderá acompanhar e emitir a segunda via de pagamentos pelo seu Portal do
                                            Aluno.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inputs Hidden/ReadOnly compatíveis com o backend -->
                        <input type="hidden" name="pos_graduacao" :value="cursoResumo ? cursoResumo.nome : ''">
                        <input type="hidden" name="oferta_id" :value="ofertaResumo ? ofertaResumo.id : ''">
                        <input type="hidden" name="forma_pagamento" :value="formaPagamento">
                        <input type="hidden" name="tipo_aluno" value="1"> <!-- Padrao Exigido Pelo Form -->
                    </div>

                    <!-- Card de Acesso ao Sistema -->
                    <div
                        class="bg-blue-50/80 dark:bg-blue-900/10 p-6 md:p-8 rounded-2xl border border-blue-100 dark:border-blue-900/50 mt-8 relative overflow-hidden shadow-inner">
                        <div
                            class="absolute right-0 top-0 -mt-6 -mr-6 text-blue-500 dark:text-blue-400 opacity-10 dark:opacity-5 transition-transform transform rotate-12">
                            <i class="fa-solid fa-fingerprint text-[150px]"></i>
                        </div>
                        <h3
                            class="font-bold text-blue-900 dark:text-blue-100 mb-5 relative z-10 text-lg flex items-center gap-2">
                            <i class="fa-solid fa-lock text-blue-600 dark:text-blue-400"></i> Crie seu Acesso ao
                            Portal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
                            <div class="md:col-span-2">
                                <label for="login"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Seu
                                    Login (E-mail Principal)</label>
                                <input type="text" id="login" name="login" value="{{ old('login') }}"
                                    readonly required
                                    class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 cursor-not-allowed px-4 py-2.5">
                                <p
                                    class="text-xs text-blue-600 dark:text-blue-400 ml-1 mt-1.5 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info"></i> Preenchido automaticamente com seu e-mail.
                                </p>
                            </div>

                            <div>
                                <label for="senha"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sua
                                    Senha *</label>
                                <div class="relative">
                                    <input type="password" id="senha" name="senha" required
                                        autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10">
                                    <button type="button" tabindex="-1"
                                        onclick="const p=document.getElementById('senha'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="senha_confirmation"
                                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Confirme
                                    a Senha *</label>
                                <div class="relative">
                                    <input type="password" id="senha_confirmation" name="senha_confirmation" required
                                        autocomplete="new-password" placeholder="Repita a senha"
                                        class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10">
                                    <button type="button" tabindex="-1"
                                        onclick="const p=document.getElementById('senha_confirmation'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= CONTROLES ================= -->
                <div
                    class="mt-10 flex justify-between items-center sm:bg-slate-50/50 sm:dark:bg-slate-800/30 sm:border sm:border-slate-100 sm:dark:border-slate-700 sm:rounded-2xl sm:p-4 gap-4 flex-col-reverse sm:flex-row">
                    <button type="button" x-show="step > 1" @click="step--"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Voltar Etapa
                    </button>
                    <!-- Preenche espaco se botao voltar escondido para alinhar o next na direita na tela web -->
                    <div x-show="step === 1" class="hidden sm:block"></div>

                    <button type="button" x-show="step < maxStep"
                        @click="if(step === 1 && !validateStep1()) { alert('Preencha todos os campos obrigatórios (*)'); return; } if(step === 2 && !validateStep2()) { alert('Preencha os dados de residência (*)'); return; } step++"
                        class="w-full sm:w-auto px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-lg shadow-blue-600/30 sm:ml-auto flex justify-center items-center gap-2 transform hover:-translate-y-0.5">
                        Continuar <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <button type="submit" x-show="step === maxStep"
                        class="w-full sm:w-auto px-10 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg shadow-emerald-600/30 sm:ml-auto flex justify-center items-center gap-2 transform hover:-translate-y-0.5 relative overflow-hidden group">
                        <span
                            class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <i class="fa-solid fa-check relative z-10 text-lg"></i> <span class="relative z-10">Finalizar
                            Inscrição Segura</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>

</html>
