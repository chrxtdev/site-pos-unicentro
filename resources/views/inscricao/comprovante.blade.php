<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Inscrição</title>
    @vite('resources/css/app.css') <!-- Tailwind CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: #1e40af;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .details {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .details p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #374151;
        }

        .details strong {
            color: #111827;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .buttons button,
        .buttons a {
            text-decoration: none;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease-in-out;
            font-size: 1rem;
            text-align: center;
        }

        .download-btn {
            background-color: #2563eb;
            color: #ffffff;
        }

        .download-btn:hover {
            background-color: #1d4ed8;
        }

        .boleto-btn {
            background-color: #22c55e;
            color: #ffffff;
        }

        .boleto-btn:hover {
            background-color: #16a34a;
        }

        @media (max-width: 768px) {
            .details p {
                font-size: 1rem;
            }

            .buttons button,
            .buttons a {
                font-size: 0.9rem;
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <img src="{{ asset('images/unicentroma.png') }}" alt="Logo da Instituição">
            <h2>Comprovante de Inscrição</h2>
        </div>

        <!-- Dados do Comprovante -->
        <div class="details">
            <p><strong>Nome:</strong> {{ $inscricao->nome }}</p>
            <p><strong>E-mail:</strong> {{ $inscricao->email }}</p>
            <p><strong>Data de Nascimento:</strong>
                {{ \Carbon\Carbon::parse($inscricao->data_nascimento)->format('d/m/Y') }}</p>
            <p><strong>Sexo:</strong> {{ ucfirst($inscricao->sexo) }}</p>
            <p><strong>Estado Civil:</strong> {{ ucfirst($inscricao->estado_civil) }}</p>
            <p><strong>Curso:</strong> {{ $inscricao->pos_graduacao }}</p>
            <p><strong>Valor da Mensalidade:</strong> R$ {{ number_format($inscricao->valor_mensalidade, 2, ',', '.') }}
            </p>
        </div>
        <h2 class="text-center text-xl font-semibold text-blue-800 my-6">
            Para Finalizar sua Inscrição Clique em Gerar Boleto
        </h2>
        <!-- Botões -->
        <div class="buttons">

            <!-- Botão para Gerar Boleto -->
            <a href="{{ route('boleto.gerar', ['id' => $inscricao->id]) }}" class="boleto-btn" id="gerar-boleto-btn">
                Gerar Boleto
            </a>

            <!-- Botão para abrir o modal -->
            <button onclick="openModal('{{ route('inscricao.downloadComprovante', ['id' => $inscricao->id]) }}')"
                class="download-btn">
                Visualizar Comprovante
            </button>


        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-4/5 md:w-3/5 lg:w-2/5">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700">Comprovante de Inscrição</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-800 text-lg font-semibold">
                    Fechar
                </button>
            </div>
            <div class="p-6">
                <iframe id="comprovante-iframe" src="" class="w-full h-96 rounded-lg"></iframe>
            </div>
            <div class="p-6 border-t flex justify-center">
                <button onclick="printComprovante()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Imprimir
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openModal(url) {
            const modal = document.getElementById('modal');
            const iframe = document.getElementById('comprovante-iframe');

            iframe.src = url; // Define o URL do comprovante no iframe
            modal.classList.remove('hidden'); // Exibe o modal
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            const iframe = document.getElementById('comprovante-iframe');

            modal.classList.add('hidden'); // Esconde o modal
            iframe.src = ''; // Limpa o conteúdo do iframe
        }

        function printComprovante() {
            const iframe = document.getElementById('comprovante-iframe');
            iframe.contentWindow.print(); // Dispara a impressão do conteúdo no iframe
        }
    </script>
</body>

</html>
