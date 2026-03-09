<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>UNICENTROMA - Portal do Aluno</title>
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
                        "background-light": "#f8fafc",
                        "background-dark": "#0f172a",
                        "surface-dark": "#1e293b",
                        "sidebar-dark": "#020617",
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

        <!-- Sidebar Aluno -->
        <aside class="hidden lg:flex w-72 flex-col bg-sidebar-dark border-r border-slate-800 h-full flex-shrink-0">
            <a href="{{ route('aluno.portal') }}"
                class="flex flex-col gap-3 px-6 py-8 items-center border-b border-slate-800/50">
                <div class="bg-white px-4 py-2 rounded-xl w-full flex justify-center shadow-inner">
                    <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo Unicentro"
                        class="h-10 w-auto object-contain">
                </div>
                <div class="flex flex-col items-center mt-2">
                    <p class="text-emerald-400 text-xs font-semibold uppercase tracking-widest">Portal do Aluno</p>
                </div>
            </a>

            <nav class="flex-1 flex flex-col gap-2 px-4 py-4 overflow-y-auto">
                <a class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('aluno.portal') ? 'bg-emerald-500/10 text-emerald-400' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group transition-all duration-200"
                    href="{{ route('aluno.portal') }}">
                    <i class="fa-solid fa-shapes text-lg"></i>
                    <span class="text-sm font-semibold">Meu Painel</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900 border border-slate-800">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 flex-1 overflow-hidden hover:opacity-80 transition-opacity">
                        <div class="relative w-10 h-10 rounded-full overflow-hidden bg-slate-700 flex-shrink-0">
                            <img alt="User Profile" class="w-full h-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random">
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-slate-400 truncate">Aluno</span>
                        </div>
                    </a>

                    @if (session('impersonator_id') || auth()->user()->is_admin)
                        <!-- Return to Admin if Impersonating or is Admin manually viewing -->
                        <a href="{{ session('impersonator_id') ? route('admin.impersonate.leave') : route('admin.dashboard') }}"
                            class="text-amber-500 hover:text-amber-400 font-bold flex items-center justify-center gap-2 transition-colors ml-auto pl-2 border-l border-slate-800"
                            title="Voltar para Admin">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </a>
                    @else
                        <!-- Normal Logout -->
                        <form method="POST" action="{{ route('logout') }}"
                            class="ml-auto pl-2 border-l border-slate-800">
                            @csrf
                            <button type="submit"
                                class="text-slate-400 hover:text-red-400 flex items-center justify-center transition-colors cursor-pointer bg-transparent border-0"
                                title="Sair">
                                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    @endif
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
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">@yield('title', 'Área do Aluno')</h2>
                </div>

                @if (session()->has('impersonator_id') || auth()->user()->is_admin)
                    <a href="{{ session()->has('impersonator_id') ? route('admin.impersonate.leave') : route('admin.dashboard') }}"
                        class="flex items-center gap-2 bg-amber-500 border border-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-amber-600 transition-colors animate-pulse">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                        {{ session()->has('impersonator_id') ? 'MODO ADMIN: CLIQUE PARA VOLTAR E SAIR DA VISUALIZAÇÃO' : 'VOLTAR PARA O PAINEL ADMIN' }}
                    </a>
                @endif

            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                @yield('content')
            </main>
        </div>

    </div>
</body>

</html>
