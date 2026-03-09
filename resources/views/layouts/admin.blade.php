<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>UNICENTROMA - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#10b774",
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-dark": "#1e293b", // slate-800
                        "sidebar-dark": "#020617", // slate-950
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display transition-colors duration-200 antialiased overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden lg:flex w-72 flex-col bg-sidebar-dark border-r border-slate-800 h-full flex-shrink-0">
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col gap-3 px-6 py-8 items-center border-b border-slate-800/50">
                <div class="bg-white px-4 py-2 rounded-xl w-full flex justify-center shadow-inner">
                    <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo Unicentro"
                        class="h-10 w-auto object-contain">
                </div>
                <div class="flex flex-col items-center mt-2">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Admin Portal</p>
                </div>
            </a>
            <nav class="flex-1 flex flex-col gap-2 px-4 py-4 overflow-y-auto">
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group transition-all duration-200"
                    href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined filled">dashboard</span>
                    <span class="text-sm font-semibold">Dashboard</span>
                </a>
                
                @can('view_notas')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('professor.disciplinas.*') || request()->routeIs('professor.notas.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('professor.disciplinas.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">history_edu</span>
                    <span class="text-sm font-medium">Diário de Turma</span>
                </a>
                @endcan

                
                @can('view_alunos')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('alunos.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('alunos.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">group</span>
                    <span class="text-sm font-medium">Alunos</span>
                </a>
                @endcan
                @hasanyrole('admin_master|financeiro|admin_comum')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('processos.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('processos.index') }}">
                    <i
                        class="fa-solid fa-list-check text-[20px] group-hover:text-primary transition-colors text-center w-6"></i>
                    <span class="text-sm font-medium">Processos Seletivos</span>
                </a>
                @endhasanyrole

                @can('view_cursos')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('cursos.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('cursos.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">school</span>
                    <span class="text-sm font-medium">Cursos</span>
                </a>
                @endcan
                @hasanyrole('admin_master|financeiro')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('financeiro.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('financeiro.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">account_balance</span>
                    <span class="text-sm font-medium">Gestão Financeira</span>
                </a>

                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('ofertas.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('ofertas.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">payments</span>
                    <span class="text-sm font-medium">Ofertas / Valores</span>
                </a>
                @endhasanyrole

                @can('manage_configuracoes')
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('configuracoes.*') ? 'bg-primary/10 text-primary' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 group"
                    href="{{ route('configuracoes.index') }}">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                    <span class="text-sm font-medium">Configurações</span>
                </a>
                @endcan
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl mt-4 border border-slate-700 text-amber-400 hover:text-white hover:bg-slate-800 transition-all duration-200 group"
                    href="{{ route('aluno.portal') }}">
                    <span
                        class="material-symbols-outlined group-hover:text-amber-300 transition-colors">laptop_mac</span>
                    <span class="text-sm font-medium">Ver Portal do Aluno</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900 border border-slate-800">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 flex-1 overflow-hidden hover:opacity-80 transition-opacity">
                        <div class="relative w-10 h-10 rounded-full overflow-hidden bg-slate-700 flex-shrink-0">
                            <img alt="Admin User Profile" class="w-full h-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random">
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-slate-400 truncate">Perfil Admin </span>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-auto pl-2 border-l border-slate-800">
                        @csrf
                        <button type="submit"
                            class="text-slate-400 hover:text-red-400 flex items-center justify-center transition-colors"
                            title="Sair">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 h-full overflow-hidden bg-background-light dark:bg-background-dark">
            <!-- Header -->
            <header
                class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark z-10">
                <div class="flex items-center gap-4">
                    <button
                        class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">@yield('title', 'Visão Global')</h2>
                </div>
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400">search</span>
                        </div>
                        <input
                            class="bg-slate-100 dark:bg-slate-800 border-none text-sm rounded-xl block w-64 pl-10 p-2.5 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-slate-900 transition-all"
                            placeholder="Buscar alunos, cursos..." type="text">
                    </div>
                    <button
                        class="relative p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span
                            class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-background-dark"></span>
                    </button>
                </div>
            </header>
            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
