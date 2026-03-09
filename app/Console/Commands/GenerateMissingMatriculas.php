<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Signin;
use App\Models\Curso;
use App\Services\GeradorMatriculaService;

class GenerateMissingMatriculas extends Command
{
    protected $signature = 'academic:generate-matriculas';
    protected $description = 'Gera matrículas estilo SUAP para alunos que ainda não possuem (Retroativo)';

    public function handle(GeradorMatriculaService $geradorService)
    {
        $this->info('Iniciando geração de matrículas retroativas...');

        // 1. Definir siglas pra quem não tem (ex: base nos cursos existentes)
        $cursos = Curso::whereNull('sigla')->orWhere('sigla', '')->get();
        foreach ($cursos as $curso) {
            $curso->sigla = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $curso->nome), 0, 3));
            $curso->save();
        }

        // 2. Gerar matrícula para alunos (signins) "pagos" que ainda não têm matrícula
        // Ou seja, alunos reais
        $alunos = Signin::whereNull('matricula')
            ->where('status_pagamento', 'pago')
            ->orderBy('id', 'asc') // garante ordem correta do mais velho pro mais novo
            ->get();

        $gerados = 0;
        foreach ($alunos as $aluno) {
            $matriculaGerada = $geradorService->gerarMatricula($aluno);
            if ($matriculaGerada) {
                // Ao salvar, ignora os mutators de cpf já que não estamos iterando pelos campos sensiveis
                $aluno->matricula = $matriculaGerada;
                $aluno->save();
                $this->line("Matrícula gerada para {$aluno->nome}: {$matriculaGerada}");
                $gerados++;
            }
        }

        $this->info("Concluído. Matrículas geradas: {$gerados}");
    }
}
