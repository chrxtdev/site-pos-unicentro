<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Boletim Escolar - {{ $inscricao->nome }}</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10b774;
            padding-bottom: 10px;
        }

        .logo {
            height: 50px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #10b774;
            text-transform: uppercase;
            margin: 0;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0 0;
        }

        .info-section {
            margin-bottom: 20px;
            width: 100%;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #555;
            width: 100px;
            display: inline-block;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .grades-table th {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            color: #475569;
            text-transform: uppercase;
            font-size: 9px;
        }

        .grades-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            text-align: center;
        }

        .text-left {
            text-align: left !important;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }

        .status-aprovado {
            color: #10b774;
            font-weight: bold;
        }

        .status-reprovado {
            color: #ef4444;
            font-weight: bold;
        }

        .status-cursando {
            color: #3b82f6;
            font-weight: bold;
        }

        .summary-box {
            margin-top: 20px;
            border: 1px solid #e2e8f0;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 5px;
        }

        .summary-title {
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="title">Boletim Acadêmico Oficial</h1>
        <p class="subtitle">Instituição de Ensino Unicentroma</p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="60%">
                    <span class="label">Aluno:</span> {{ $inscricao->nome }}<br>
                    <span class="label">Matrícula:</span> {{ $inscricao->matricula ?? 'N/A' }}<br>
                    <span class="label">CPF:</span> {{ $inscricao->cpf ?? 'N/A' }}
                </td>
                <td width="40%">
                    <span class="label">Data Emissão:</span> {{ date('d/m/Y H:i') }}<br>
                    <span class="label">Período:</span> {{ date('Y') }}/1<br>
                    <span class="label">Curso:</span> {{ $inscricao->pos_graduacao }}
                </td>
            </tr>
        </table>
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th rowspan="2" class="text-left">Disciplina</th>
                <th rowspan="2" width="40">CH</th>
                <th rowspan="2" width="40">Freq.</th>
                <th colspan="2">Etapa 1</th>
                <th colspan="2">Etapa 2</th>
                <th colspan="2">Exame</th>
                <th rowspan="2" width="40">MFD</th>
                <th rowspan="2" width="70">Situação</th>
            </tr>
            <tr>
                <th width="30">N</th>
                <th width="20">F</th>
                <th width="30">N</th>
                <th width="20">F</th>
                <th width="30">N</th>
                <th width="20">F</th>
            </tr>
        </thead>
        <tbody>
            @php
                $somaMedias = 0;
                $qtdMedias = 0;
            @endphp
            @foreach ($matriculas as $mat)
                @php
                    $percFreq = $mat->total_aulas > 0 ? ($mat->presencas / $mat->total_aulas) * 100 : 0;
                    $mf = $mat->notas->media_final ?? 0;
                    if ($mf > 0) {
                        $somaMedias += $mf;
                        $qtdMedias++;
                    }
                @endphp
                <tr>
                    <td class="text-left font-bold">{{ $mat->disciplina->nome }}</td>
                    <td>{{ $mat->disciplina->carga_horaria }}h</td>
                    <td>{{ number_format($percFreq, 1) }}%</td>

                    {{-- Etapa 1 --}}
                    <td>{{ isset($mat->notas->b1_total) ? number_format($mat->notas->b1_total, 1) : '-' }}</td>
                    <td>0</td>

                    {{-- Etapa 2 --}}
                    <td>{{ isset($mat->notas->b2_total) ? number_format($mat->notas->b2_total, 1) : '-' }}</td>
                    <td>0</td>

                    {{-- Exame --}}
                    <td>-</td>
                    <td>0</td>

                    <td class="font-bold">{{ number_format($mf, 1) }}</td>
                    <td>
                        @if ($mat->status == 'aprovado')
                            <span class="status-aprovado">Aprovado</span>
                        @elseif($mat->status == 'reprovado')
                            <span class="status-reprovado">Reprovado</span>
                        @else
                            <span class="status-cursando">Cursando</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-title">Resumo de Aproveitamento</div>
        <table width="100%">
            <tr>
                <td><strong>Disciplinas:</strong> {{ $matriculas->count() }}</td>
                <td><strong>C.H. Total:</strong>
                    {{ $matriculas->sum(function ($m) {return $m->disciplina->carga_horaria;}) }}h</td>
                <td text-align="right"><strong>Média Geral:</strong>
                    {{ $qtdMedias > 0 ? number_format($somaMedias / $qtdMedias, 2) : '0,00' }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <div style="width: 250px; border-top: 1px solid #333; margin: 0 auto; padding-top: 5px;">
            Secretaria Acadêmica
        </div>
    </div>

    <div class="footer">
        Documento gerado eletronicamente pelo Portal do Aluno Unicentroma em {{ date('d/m/Y H:i:s') }}.<br>
        A autenticidade deste documento pode ser verificada no portal oficial da instituição.
    </div>

</body>

</html>
