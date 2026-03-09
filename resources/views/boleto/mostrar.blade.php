<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Cobrança - Unicentro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col font-sans">

    <!-- Navbar simples -->
    <nav class="bg-white border-b border-gray-200 shadow-sm relative z-10 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <span class="font-bold text-xl text-gray-800 tracking-tight">Unicentro <span class="text-blue-600">Pós</span></span>
                </div>
                <div>
                     <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Voltar ao Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-start pt-8 pb-12 px-4 sm:px-6 lg:px-8 w-full">
        
        <div class="w-full max-w-4xl text-center mb-8">
            @php
                $tipoPgt = $inscricao->forma_pagamento ?? 'boleto';
                $titulo = 'Finalize seu Pagamento';
                $icone = 'fa-solid fa-file-invoice-dollar';
                $corTema = 'text-blue-600';
                $corBgIcone = 'bg-blue-100';
                
                if($tipoPgt === 'pix') {
                    $titulo = 'Pagamento via Pix';
                    $icone = 'fa-brands fa-pix';
                    $corTema = 'text-emerald-600';
                    $corBgIcone = 'bg-emerald-100';
                } elseif($tipoPgt === 'cartao') {
                    $titulo = 'Pagamento com Cartão';
                    $icone = 'fa-regular fa-credit-card';
                    $corTema = 'text-purple-600';
                    $corBgIcone = 'bg-purple-100';
                }
            @endphp
            
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $corBgIcone }} {{ $corTema }} mb-4 shadow-sm ring-4 ring-white">
                <i class="{{ $icone }} text-3xl"></i>
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">
                {{ $titulo }}
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Olá, <strong>{{ explode(' ', $inscricao->nome)[0] }}</strong>! Aqui está o ambiente para pagamento da sua especialização em <strong class="text-gray-800">{{ $inscricao->pos_graduacao }}</strong>.
            </p>
        </div>

        <!-- Área Principal de Ação -->
        @if($tipoPgt === 'cartao' || $tipoPgt === 'pix')
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-gray-200 p-8 text-center flex flex-col items-center justify-center gap-6">
            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-2">
                <i class="fa-solid fa-lock text-4xl text-slate-400"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Ambiente de Pagamento Seguro</h2>
            <p class="text-gray-600 max-w-md">Por diretrizes de segurança bancária, o seu pagamento via {{ ucfirst($tipoPgt) }} deve ser realizado diretamente no ambiente criptografado do banco parceiro (Asaas).</p>
            
            <a href="{{ $linkBoleto }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-8 py-4 text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all w-full md:w-auto focus:ring-4 focus:ring-blue-200 mb-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Acessar Portal de Pagamento
            </a>
            <p class="text-sm text-gray-400 mt-2">Uma nova aba segura será aberta em seu navegador.</p>
        </div>
        @else
        <!-- Área de Iframe/Boleto para Boletos Padrão (BankSlip) -->
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden flex flex-col">
            
            <!-- Barra de ferramentas superior do frame -->
            <div class="bg-gray-50 border-b border-gray-200 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-gray-600 text-sm font-medium">
                    <i class="fa-solid fa-shield-halved text-emerald-500 text-lg"></i>
                    Ambiente Autenticado Asaas
                </div>
                
                <a href="{{ $linkBoleto }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-2 text-sm font-bold rounded-xl text-white bg-gray-800 hover:bg-gray-900 shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Abrir em Nova Aba
                </a>
            </div>
            
            <!-- Iframe Container com pseudo skeleton -->
            <div class="relative w-full bg-slate-100 flex items-center justify-center overflow-hidden" style="height: 75vh; min-height: 700px;">
                <!-- Loader placeholder -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 z-0">
                    <i class="fa-solid fa-circle-notch fa-spin text-4xl mb-3 text-blue-400"></i>
                    <p class="font-medium animate-pulse">Carregando ambiente de pagamento...</p>
                </div>
                
                <iframe 
                    src="{{ $linkBoleto }}" 
                    title="Visualizar Cobrança" 
                    class="w-full h-full relative z-10 border-0"
                    onload="this.previousElementSibling.style.display='none';"
                ></iframe>
            </div>
            
        </div>
        @endif
        
        <!-- Footer info -->
        <div class="mt-8 text-center text-gray-500 text-sm flex flex-col sm:flex-row items-center justify-center gap-2">
            <i class="fa-solid fa-lock text-gray-400"></i> Seus dados estão protegidos com criptografia de ponta a ponta.
        </div>
        
    </main>

</body>

</html>