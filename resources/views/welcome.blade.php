<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UNICENTRO Pós-Graduação | Especialize-se</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="fixed w-full top-0 z-50 bg-gray-900/80 backdrop-blur-md border-b border-gray-800 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/"
                        class="flex items-center group bg-white px-5 py-2.5 rounded-2xl shadow-xl transition-all hover:scale-105 hover:shadow-2xl">
                        <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo UNICENTROMA"
                            class="h-10 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-semibold text-gray-300 hover:text-white transition-colors duration-200">
                            Acessar Portal
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-300 hover:text-white transition-colors duration-200 hidden sm:block">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Entrar
                        </a>
                        <a href="{{ route('inscricao.index') }}"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:ring-4 focus:ring-blue-900 transition-all shadow-lg shadow-blue-500/30">
                            Inscreva-se
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-cover bg-[center_top_6rem] bg-no-repeat"
        style="background-image: url('{{ asset('images/inicio-do-site-pos.png') }}');">
        <!-- Overlay escuro com desfoque moderado refinado -->
        <div
            class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/80 to-gray-900/50 backdrop-blur-[2px] z-0">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium mb-8">
                <span class="flex h-2 w-2 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Matrículas Abertas 2026
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                Especialize-se com a <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-blue-500">UNICENTRO
                    Pós-Graduação</span>
            </h1>

            <p class="mt-6 text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-10">
                Impulsione sua carreira com cursos de especialização de alto nível, ministrados por mestres e doutores
                renomados no mercado. O seu futuro começa hoje.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('inscricao.index') }}"
                    class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-500 focus:ring-4 focus:ring-blue-900 transition-all shadow-xl shadow-blue-500/30 flex items-center justify-center gap-2">
                    Garantir Minha Vaga <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#cursos"
                    class="w-full sm:w-auto px-8 py-4 text-base font-bold text-gray-300 bg-gray-800 border border-gray-700 rounded-xl hover:bg-gray-700 hover:text-white transition-all flex items-center justify-center gap-2">
                    Conhecer os Cursos
                </a>
            </div>

        </div>
    </section>

    <!-- Cursos em Destaque Section -->
    <section id="cursos" class="py-20 bg-gray-900/50 border-t border-gray-800 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Cursos em Destaque</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Áreas com alta demanda de mercado e matriz curricular
                    atualizada com as mais recentes práticas do setor.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($cursosDestaque as $curso)
                    {{-- Card Curso --}}
                    <div
                        class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden hover:border-gray-500 hover:-translate-y-1 transition-all duration-300 shadow-lg group flex flex-col h-full">
                        <div class="p-8 flex-grow">
                            <div
                                class="w-14 h-14 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 mb-6 group-hover:scale-110 group-hover:bg-emerald-500/20 transition-all">
                                <i class="fa-solid fa-graduation-cap text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 leading-snug">{{ $curso->nome }}</h3>
                            <p class="text-gray-300 text-sm mb-6">Pós-Graduação Latu Sensu. Matriz curricular atualizada
                                com as práticas mais recentes do mercado atual.</p>
                            <div class="flex items-center gap-4 text-sm font-medium text-gray-300">
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-clock text-gray-400"></i>
                                    Especialização</span>
                                <span class="flex items-center gap-1.5"><i
                                        class="fa-solid fa-desktop text-gray-400"></i> EAD / Presencial</span>
                            </div>
                        </div>
                        <div class="p-6 border-t border-gray-700/50 bg-gray-800/50">
                            <a href="{{ route('inscricao.index') }}"
                                class="block w-full text-center py-2.5 bg-gray-700 hover:bg-emerald-600 text-white font-semibold rounded-lg transition-colors border border-gray-600 hover:border-emerald-500">
                                Matricule-se
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('inscricao.index') }}"
                    class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 font-semibold transition-colors">
                    Ver catálogo completo na Inscrição <i class="fa-solid fa-arrow-right-long text-sm"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 py-12 border-t border-gray-800 mt-auto">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">

            <p class="text-gray-500 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} UNICENTROMA/FCMA. Todos os direitos reservados.
            </p>

            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-500 hover:text-white transition-colors"><i
                        class="fa-brands fa-instagram text-xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-white transition-colors"><i
                        class="fa-brands fa-whatsapp text-xl"></i></a>
            </div>

        </div>
    </footer>

</body>

</html>
