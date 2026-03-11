<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>UNICENTROMA - Admin Dashboard</title>
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
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-dark": "#1e293b", // slate-800
                        "sidebar-dark": "#020617", // slate-950
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
            <nav class="flex-1 flex flex-col gap-2 px-4 py-6 overflow-y-auto custom-scrollbar">
                <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                        <span class="material-symbols-outlined filled text-sm">dashboard</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Visão Geral</span>
                </a>

                @can('view_notas')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('professor.disciplinas.*') || request()->routeIs('professor.notas.*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('professor.disciplinas.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('professor.disciplinas.*') || request()->routeIs('professor.notas.*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                            <span class="material-symbols-outlined text-sm">history_edu</span>
                        </div>
                        <span class="text-sm font-black tracking-tight">Diário de Turma</span>
                    </a>
                @endcan

                @can('view_alunos')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('alunos.*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('alunos.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('alunos.*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                            <span class="material-symbols-outlined text-sm">group</span>
                        </div>
                        <span class="text-sm font-black tracking-tight">Alunos</span>
                    </a>
                @endcan

                @hasanyrole('admin_master|financeiro|admin_comum')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('processos.*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('processos.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('processos.*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                            <i class="fa-solid fa-list-check text-xs"></i>
                        </div>
                        <span class="text-sm font-black tracking-tight">Processos Seletivos</span>
                    </a>
                @endhasanyrole

                @can('view_cursos')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('cursos.*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('cursos.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('cursos.*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                            <span class="material-symbols-outlined text-sm">school</span>
                        </div>
                        <span class="text-sm font-black tracking-tight">Cursos</span>
                    </a>
                @endcan

                @hasanyrole('admin_master|financeiro')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('financeiro.*') ? 'bg-primary text-white shadow-glow translate-x-1' : 'text-slate-400 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('financeiro.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('financeiro.*') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-primary/20 transition-colors' }}">
                            <span class="material-symbols-outlined text-sm">account_balance</span>
                        </div>
                        <span class="text-sm font-black tracking-tight">Gestão Financeira</span>
                    </a>
                @endhasanyrole

                <div class="my-4 border-t border-slate-800/50 mx-4"></div>

                @can('manage_configuracoes')
                    <a class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('configuracoes.*') ? 'bg-white/10 text-white translate-x-1' : 'text-slate-500 hover:text-white hover:bg-white/5 hover:translate-x-1' }}"
                        href="{{ route('configuracoes.index') }}">
                        <div
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800/50 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-sm">settings</span>
                        </div>
                        <span class="text-sm font-black tracking-tight">Configurações</span>
                    </a>
                @endcan

                @if(auth()->user()->is_admin || auth()->user()->hasAnyRole(['admin_master', 'financeiro', 'admin_comum']))
                <a class="flex items-center gap-4 px-4 py-3 rounded-2xl mt-4 border border-amber-500/30 bg-amber-500/5 text-amber-500 hover:bg-amber-500 hover:text-white transition-all duration-300 group"
                    href="{{ route('aluno.portal') }}">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-500/10 group-hover:bg-white/20 transition-colors">
                        <span class="material-symbols-outlined text-sm">laptop_mac</span>
                    </div>
                    <span class="text-sm font-black tracking-tight">Abrir Portal Aluno</span>
                </a>
                @endif
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
                class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/80 backdrop-blur-xl z-20 sticky top-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button
                        class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">@yield('title', 'Visão Global')</h2>
                </div>
                <div class="flex items-center gap-6">
                    <form method="GET" action="{{ route('alunos.index') }}" class="hidden md:flex relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400">search</span>
                        </div>
                        <input name="search"
                            class="bg-slate-100 dark:bg-slate-800 border-none text-sm rounded-xl block w-64 pl-10 p-2.5 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-slate-900 transition-all"
                            placeholder="Buscar alunos..." type="text">
                        <button type="submit" class="hidden"></button>
                    </form>

                    <div class="relative group">
                        <button
                            class="relative p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors cursor-pointer">
                            <span class="material-symbols-outlined">notifications</span>
                            <span
                                class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-background-dark"></span>
                        </button>

                        <!-- Dropdown panel -->
                        <div
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-surface-dark rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform origin-top-right scale-95 group-hover:scale-100">
                            <div
                                class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Notificações</h3>
                                <span
                                    class="bg-primary/10 text-primary text-[10px] px-2 py-0.5 rounded-full font-bold">Recentes</span>
                            </div>
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                                @php
                                    $ultimasMatriculas = \App\Models\Signin::latest()->take(10)->get();
                                @endphp
                                @forelse ($ultimasMatriculas as $notif)
                                    <a href="{{ route('financeiro.show', $notif->id) }}"
                                        class="flex items-start gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-800/60 border-b border-slate-100 dark:border-white/5 transition-all group/notif">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0 group-hover/notif:scale-110 transition-transform">
                                            <i class="fa-solid fa-user-plus text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white mb-0.5 truncate">
                                                Matrícula Realizada</p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $notif->nome }}</span> acaba de se inscrever.
                                            </p>
                                            <span class="text-[9px] text-slate-400 font-medium flex items-center mt-1.5 uppercase tracking-wider">
                                                <i class="fa-regular fa-clock mr-1 text-[10px]"></i>{{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-8 text-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-bell-slash text-slate-400"></i>
                                        </div>
                                        <p class="text-xs text-slate-500">Nenhuma notificação recente.</p>
                                    </div>
                                @endforelse
                            </div>
                            @hasanyrole('admin_master|financeiro')
                                <div
                                    class="p-2 border-t border-slate-100 dark:border-slate-800 text-center bg-slate-50 dark:bg-slate-900/50 rounded-b-2xl">
                                    <a href="{{ route('financeiro.index') }}"
                                        class="text-[11px] font-bold text-primary hover:text-primary/80 transition-colors p-2 block w-full">Ver
                                        Central Financeira</a>
                                </div>
                            @endhasanyrole
                        </div>
                    </div>
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
