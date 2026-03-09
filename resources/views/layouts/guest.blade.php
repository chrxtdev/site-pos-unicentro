<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body
    class="font-sans text-slate-900 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-900 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
        <!-- Efeitos de Background -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/20 dark:bg-indigo-600/20 blur-[120px] rounded-full pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500/20 dark:bg-blue-600/20 blur-[120px] rounded-full pointer-events-none">
        </div>

        <div class="relative z-10 mb-8 transform hover:scale-105 transition-transform duration-300">
            <a href="/" class="block bg-white px-8 py-5 rounded-2xl shadow-xl ring-1 ring-slate-200">
                <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo UNICENTROMA"
                    class="w-auto h-16 sm:h-20 object-contain">
            </a>
        </div>

        <div
            class="w-full sm:max-w-md px-8 py-8 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-2xl shadow-slate-200/50 dark:shadow-slate-900/50 ring-1 ring-slate-200/50 dark:ring-slate-700/50 overflow-hidden sm:rounded-2xl relative z-10 transition-all">
            {{ $slot }}
        </div>

        <div class="mt-8 text-sm text-slate-500 dark:text-slate-400 relative z-10">
            &copy; {{ date('Y') }} UNICENTROMA/FCMA. Todos os direitos reservados.
        </div>
    </div>
</body>

</html>
