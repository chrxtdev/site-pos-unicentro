<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body
    class="font-display text-slate-900 dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-slate-950">
        
        <!-- Background Premium com Overlays -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/auth/login-bg.png') }}" alt="Background" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-transparent to-slate-950/80"></div>
        </div>

        <!-- Efeitos de Brilho -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none z-0"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

        <div class="relative z-10 mb-8 transform hover:scale-105 transition-all duration-500">
            <a href="/" class="block bg-white/90 backdrop-blur-md px-8 py-5 rounded-2xl shadow-2xl ring-1 ring-white/30">
                <img src="{{ asset('images/unicentroma-horizontal.png') }}" alt="Logo UNICENTROMA"
                    class="w-auto h-16 sm:h-20 object-contain">
            </a>
        </div>

        <div
            class="w-full sm:max-w-md px-10 py-10 bg-white/10 dark:bg-slate-900/40 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] ring-1 ring-white/10 overflow-hidden sm:rounded-3xl relative z-10 transition-all border border-white/5">
            {{ $slot }}
        </div>

        <div class="mt-8 text-sm text-slate-400 dark:text-slate-500 relative z-10 font-medium tracking-wide">
            &copy; {{ date('Y') }} UNICENTROMA/FCMA. Todos os direitos reservados.
        </div>
    </div>
</body>

</html>
