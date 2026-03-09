<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProcessoSeletivo;
use App\Models\Curso;
use App\Models\Oferta;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar um Processo Seletivo padrão para vincular às ofertas
        $processo = ProcessoSeletivo::firstOrCreate(
            ['nome' => 'Processo Seletivo Pós-Graduação 2026'],
            [
                'numero_etapas' => 1,
                'numero_ofertas' => 3,
                'situacao' => 'Ativo'
            ]
        );

        // 2. Definir os cursos solicitados
        $cursosNomes = [
            'Direito e Processo Previdenciário',
            'Gestão em Saúde e Administração Hospitalar',
            'Urgência, Emergência e Terapia Intensiva'
        ];

        foreach ($cursosNomes as $nome) {
            // Cria ou recupera o curso
            $curso = Curso::firstOrCreate(
                ['nome' => $nome],
                ['tipo' => 'Especialização'] // ou outro tipo padrão caso tenha no schema
            );

            // Cria uma Oferta padrão para este curso
            Oferta::firstOrCreate(
                [
                    'curso_id' => $curso->id,
                    'processo_seletivo_id' => $processo->id,
                ],
                [
                    'turno' => 'Noturno',
                    'quantidade_vagas' => 40,
                    'locais_prova' => 'Online',
                    'valor_taxa' => 250.00,
                    'data_vencimento_taxa' => now()->addDays(7)->toDateString(),
                    'conta_recebimento' => 'Conta Corrente Padrão'
                ]
            );
        }
    }
}
