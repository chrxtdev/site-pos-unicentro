<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição Confirmada - Unicentro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-700">
        <!-- Header Ilustrativo -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-9xl"></i>
            </div>
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg text-emerald-600">
                    <i class="fa-solid fa-check text-4xl font-bold"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Inscrição Confirmada!</h1>
                <p class="text-emerald-100 font-medium text-lg">Bem-vindo(a) à Unicentro Pós</p>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="p-8 text-center">
            <p class="text-gray-300 mb-6 text-lg">
                Sua matrícula no curso <strong>{{ $inscricao->pos_graduacao }}</strong> foi processada com sucesso.
            </p>

            <div class="space-y-4">
                <a href="{{ route('dashboard') }}" class="w-full block py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/50 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-graduate"></i> Acessar Portal do Aluno
                </a>

                @if($inscricao->asaas_payment_id)
                <a href="{{ route('boleto.gerar', ['id' => $inscricao->id]) }}" target="_blank" class="w-full block py-3 px-4 bg-gray-700 hover:bg-gray-600 text-gray-200 border border-gray-600 font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-barcode"></i> Visualizar Boleto
                </a>
                @endif
            </div>
            
            <p class="text-gray-500 text-sm mt-8">
                Um e-mail de confirmação e as instruções de acesso também foram enviados para <strong>{{ $inscricao->email }}</strong>.
            </p>
        </div>
    </div>

</body>
</html>
