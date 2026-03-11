<?php

namespace App\Services;

use App\Models\Signin;
use App\Models\Curso;

class GeradorMatriculaService
{
    /**
     * Gera a matrícula acadêmica num formato SUAP (ex: 20261.DIR001)
     * 
     * @param Signin $signin
     * @return string
     */
    public function gerarMatricula(Signin $signin): string
    {
        // Se já tem, não gera novamente
        if ($signin->matricula) {
            return $signin->matricula;
        }

        $anoAtual = date('Y');
        $mesAtual = date('n');
        $semestre = $mesAtual <= 6 ? 1 : 2;
        $prefixoAnoSemestre = $anoAtual . $semestre;

        // Tenta descobrir a sigla pelo curso do aluno (pos_graduacao varchar)
        // Isso é complexo porque o registro original só gravou a string 'pos_graduacao'.
        // Vamos buscar no BD Cursos pela correspondência de nome.
        $curso = Curso::where('nome', $signin->pos_graduacao)->first();
        
        // Fallback pra sigla caso não encontre (ex: antigos ou nome manual)
        if ($curso && $curso->sigla) {
            $sigla = strtoupper(trim($curso->sigla));
        } else {
            // Se falhou, pega as primeiras 3 letras da profissão/pos
            $cleanName = preg_replace('/[^A-Za-z]/', '', $signin->pos_graduacao);
            $sigla = strtoupper(substr($cleanName, 0, 3));
            if (empty($sigla)) {
                $sigla = 'POS';
            }
        }

        // Base para busca do sequencial
        $baseParaBusca = $prefixoAnoSemestre . '.' . $sigla;

        // Pega a última matrícula gerada para este curso/semestre
        $ultimaMatricula = Signin::where('matricula', 'like', $baseParaBusca . '%')
                                 ->orderBy('matricula', 'desc')
                                 ->first();

        $proximoNumero = 1;

        if ($ultimaMatricula) {
            // Extrai a parte numérica final (001 -> 1)
            $stringMatricula = $ultimaMatricula->matricula;
            $numerosAcumulados = str_replace($baseParaBusca, '', $stringMatricula);
            
            if (is_numeric($numerosAcumulados)) {
                $proximoNumero = (int) $numerosAcumulados + 1;
            }
        }

        // Monta a matrícula formatada (001, 002, 003...)
        $matriculaFormatada = $baseParaBusca . str_pad($proximoNumero, 3, '0', STR_PAD_LEFT);

        return $matriculaFormatada;
    }
}
