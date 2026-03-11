<?php

namespace App\Services;

use App\Models\Signin;
use App\Models\Curso;
use App\Models\MatriculaDisciplina;
use Illuminate\Support\Facades\Log;

class MatriculaService
{
    /**
     * Identifica o curso base do aluno e o vincula a todas as disciplinas (enturmação).
     *
     * @param Signin $aluno
     * @return void
     */
    public function vincularDisciplinas(Signin $aluno): void
    {
        // Garante que não haja tentativa de duplicar a enturmação
        $jaEnturmado = MatriculaDisciplina::where('signin_id', $aluno->id)->exists();
        
        if ($jaEnturmado) {
            Log::info("Aluno ID {$aluno->id} já possui disciplinas vinculadas. Ignorando enturmação automática.");
            return;
        }

        // Tenta descobrir o curso base a partir do nome string 'pos_graduacao'
        $curso = Curso::where('nome', $aluno->pos_graduacao)->with('disciplinas')->first();

        if (!$curso) {
            Log::warning("Enturmação falhou: Curso '{$aluno->pos_graduacao}' não encontrado para o aluno ID {$aluno->id}.");
            return;
        }

        if ($curso->disciplinas->isEmpty()) {
            Log::warning("Enturmação parcial: Curso '{$curso->nome}' não possui disciplinas cadastradas. Aluno ID {$aluno->id} ficou sem vínculos.");
            return;
        }

        $vinculosCriados = 0;

        foreach ($curso->disciplinas as $disciplina) {
            MatriculaDisciplina::create([
                'signin_id' => $aluno->id,
                'disciplina_id' => $disciplina->id,
                'status' => 'cursando',
                // Carga horária é da disciplina, presenças começam zeradas
                'total_aulas' => $disciplina->carga_horaria, 
                'presencas' => 0,
                'faltas' => 0,
            ]);
            $vinculosCriados++;
        }

        Log::info("Enturmação concluída: Aluno ID {$aluno->id} foi vinculado a {$vinculosCriados} disciplinas do curso {$curso->nome}.");
    }
}
