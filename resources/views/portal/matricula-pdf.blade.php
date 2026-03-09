<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #10b774; padding-bottom: 20px; margin-bottom: 40px; }
        .logo { height: 60px; margin-bottom: 10px; }
        .title { font-size: 22px; font-weight: bold; text-transform: uppercase; color: #1e293b; }
        .content { margin-bottom: 50px; text-align: justify; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .signature { margin-top: 80px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 300px; margin: 0 auto 10px auto; }
        .data-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .data-box div { margin-bottom: 5px; font-size: 14px; }
        .data-box strong { color: #10b774; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Declaração de Matrícula</div>
        <div style="font-size: 12px; color: #666;">Unicentro - Pós-Graduação</div>
    </div>

    <div class="content">
        <p>Declaramos, para os devidos fins, que o(a) estudante abaixo identificado(a) encontra-se regularmente matriculado(a) nesta instituição de ensino:</p>

        <div class="data-box">
            <div><strong>Nome:</strong> {{ $inscricao->nome }}</div>
            <div><strong>Registro Acadêmico (RA):</strong> {{ $inscricao->matricula }}</div>
            <div><strong>CPF:</strong> {{ $inscricao->cpf }}</div>
            <div><strong>Curso:</strong> {{ $inscricao->pos_graduacao }}</div>
            <div><strong>Status:</strong> {{ strtoupper($inscricao->status_pagamento == 'pago' ? 'Ativo' : 'Pendente') }}</div>
            <div><strong>Data de Inscrição:</strong> {{ $inscricao->created_at->format('d/m/Y') }}</div>
        </div>

        <p style="margin-top: 30px;">A referida pós-graduação é realizada na modalidade presencial/EAD, em conformidade com as diretrizes do Ministério da Educação (MEC).</p>
    </div>

    <div style="text-align: right; margin-top: 50px;">
        {{ now()->translatedFormat('d \d\e F \d\e Y') }}
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <strong>Secretaria Acadêmica Unicentro</strong><br>
        <em>Validado eletronicamente via Portal do Aluno</em>
    </div>

    <div class="footer">
        Este documento foi gerado automaticamente pelo sistema Unicentro Pós em {{ now()->format('d/m/Y H:i:s') }}.<br>
        A autenticidade deste documento pode ser verificada no portal oficial da instituição.
    </div>
</body>
</html>
