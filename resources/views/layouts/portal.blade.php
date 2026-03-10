<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>UNICENTROMA - Portal do Aluno</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "sans": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.625rem",
                        "lg": "0.875rem",
                        "xl": "1.25rem",
                        "2xl": "1.75rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'glow': '0 0 15px -3px rgba(16, 183, 116, 0.3)',
                    }
                },
            },
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display transition-colors duration-200 antialiased overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden relative">

        {{-- Overlay para Mobile --}}
        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

        <!-- Sidebar Aluno -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar-dark border-r border-slate-800 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full flex-shrink-0">
            <a href="{{ route('aluno.portal') }}"
                class="flex flex-col gap-3 px-6 py-8 items-center border-b border-slate-800/50">
                <div class="bg-white px-4 py-2 rounded-xl w-full flex justify-center shadow-inner">
                    <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo Unicentro"
                        class="h-10 w-auto object-contain">
                </div>
                <div class="flex flex-col items-center mt-2 text-center">
                    <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-[0.2em]">Portal do Aluno</p>
                </div>
            </a>

            <nav class="flex-1 flex flex-col gap-2 px-4 py-6 overflow-y-auto custom-scrollbar">
                {{-- Item Meu Curso --}}
                <a href="{{ route('aluno.portal') }}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('aluno.portal') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('aluno.portal') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                        <span class="material-symbols-outlined text-xl">school</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Meu Dashboard</span>
                </a>

                {{-- Item Desempenho --}}
                <a href="{{ route('aluno.notas') }}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('aluno.notas') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('aluno.notas') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                        <span class="material-symbols-outlined text-xl">workspace_premium</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Notas e Frequência</span>
                </a>

                {{-- Item Mural --}}
                <a href="{{ route('aluno.mural') }}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('aluno.mural*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('aluno.mural*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                        <span class="material-symbols-outlined text-xl">campaign</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Mural Acadêmico</span>
                </a>

                <div class="my-4 border-t border-slate-800/50 mx-4"></div>

                {{-- Item Financeiro --}}
                <a href="{{ route('aluno.financeiro') }}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('aluno.financeiro') ? 'bg-white/10 text-white translate-x-1' : 'text-slate-500 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800/50 group-hover:bg-emerald-500/20 transition-colors">
                        <span class="material-symbols-outlined text-xl">payments</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Pagamentos</span>
                </a>

                {{-- Item Documentos --}}
                <a href="{{ route('aluno.documentos') }}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('aluno.documentos') ? 'bg-white/10 text-white translate-x-1' : 'text-slate-500 hover:text-white hover:bg-white/5 hover:translate-x-1' }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800/50 group-hover:bg-orange-500/20 transition-colors">
                        <span class="material-symbols-outlined text-xl">description</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Documentação</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/50 border border-slate-800">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 flex-1 overflow-hidden hover:opacity-80 transition-opacity">
                        <div
                            class="relative w-10 h-10 rounded-full overflow-hidden bg-slate-700 flex-shrink-0 ring-2 ring-primary/20">
                            <img alt="User Profile" class="w-full h-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b774&color=fff">
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Aluno</span>
                        </div>
                    </a>

                    @if (session('impersonator_id') || auth()->user()->is_admin)
                        <a href="{{ session('impersonator_id') ? route('admin.impersonate.leave') : route('admin.dashboard') }}"
                            class="text-amber-500 hover:text-amber-400 font-bold flex items-center justify-center gap-2 transition-colors ml-auto pl-2 border-l border-slate-800"
                            title="Voltar para Admin">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </a>
                    @else
                        <form method="POST" action="{{ route('logout') }}"
                            class="ml-auto pl-2 border-l border-slate-800">
                            @csrf
                            <button type="submit"
                                class="text-slate-500 hover:text-red-400 flex items-center justify-center transition-colors cursor-pointer bg-transparent border-0"
                                title="Sair">
                                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 h-full overflow-hidden bg-background-light dark:bg-background-dark relative">
            <!-- Header -->
            <header
                class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/80 backdrop-blur-xl z-30 sticky top-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()"
                        class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">@yield('title', 'Área do Aluno')</h2>
                </div>

                @if (session()->has('impersonator_id') || auth()->user()->is_admin)
                    <a href="{{ session()->has('impersonator_id') ? route('admin.impersonate.leave') : route('admin.dashboard') }}"
                        class="hidden sm:flex items-center gap-2 bg-amber-500 border border-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg hover:bg-amber-600 transition-all animate-pulse">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                        <span
                            class="truncate">{{ session()->has('impersonator_id') ? 'MODO ADMIN: SAIR DA VISUALIZAÇÃO' : 'VOLTAR PARA ADMIN' }}</span>
                    </a>
                @endif
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                @yield('content')
            </main>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
