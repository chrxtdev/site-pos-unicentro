<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Inscrição</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            /* Cinza claro */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 700px;
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 150px;
            /* Tamanho máximo da logo */
            margin-bottom: 15px;
            /* Espaçamento abaixo da logo */
        }

        .header h2 {
            color: #1d4ed8;
            /* Azul */
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }

        .details {
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .details p {
            margin: 5px 0;
            font-size: 1rem;
            color: #374151;
            /* Cinza escuro */
        }

        .details strong {
            font-weight: bold;
            color: #111827;
            /* Preto */
        }

        .highlight {
            background-color: #f0f9ff;
            padding: 10px;
            border-left: 4px solid #1d4ed8;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .buttons a {
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 6px;
            display: inline-block;
            margin: 10px;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .download-btn {
            background-color: #1e3a8a;
            /* Azul escuro */
            color: white;
        }

        .download-btn:hover {
            background-color: #2563eb;
            /* Azul claro */
            transform: scale(1.05);
        }

        .boleto-btn {
            background-color: #16a34a;
            /* Verde */
            color: white;
        }

        .boleto-btn:hover {
            background-color: #22c55e;
            /* Verde claro */
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header h2 {
                font-size: 1.5rem;
            }

            .details p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Cabeçalho com Logo e Título -->
        <div class="header">
            <img src="{{ public_path('images/unicentroma.png') }}" alt="Logo da Instituição" style="max-width: 150px;">
            <!-- Substitua o caminho da logo -->
            <h2>Comprovante de Inscrição</h2>
        </div>

        <!-- Mensagem em destaque -->
        <div class="highlight">
            <p>Parabéns, <strong>{{ $inscricao->nome }}</strong>! Sua inscrição no curso de
                <strong>{{ $inscricao->pos_graduacao }}</strong> foi realizada com sucesso.</p>
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
    </div>
</body>

</html>
