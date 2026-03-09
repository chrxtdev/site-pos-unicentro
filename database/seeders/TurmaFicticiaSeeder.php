<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurmaFicticiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar Professor Fictício
        $professor = \App\Models\User::firstOrCreate(
            ['email' => 'professor.ficticio@unicentro.br'],
            [
                'name' => 'Dr. Ricardo Silva',
                'password' => bcrypt('12345678'),
                'is_admin' => false,
            ]
        );

        // 2. Criar Curso e Disciplinas
        $curso = \App\Models\Curso::firstOrCreate(
            ['nome' => 'MBA em Engenharia de Software de Alta Performance'],
            [
                'sigla' => 'ESP',
                'tipo' => 'Pós-Graduação',
            ]
        );

        $disciplinas = [
            'Arquitetura de Microserviços',
            'DevOps e CI/CD Avançado',
            'Cloud Computing com AWS/Azure',
            'Desenvolvimento Mobile com React Native',
            'Segurança da Informação e LGPD',
            'Gestão Agil de Projetos',
        ];

        // 3. Pegar o aluno logado (ou o primeiro Signin do banco)
        $aluno = \App\Models\Signin::first();

        if (!$aluno) {
            return;
        }

        foreach ($disciplinas as $nome) {
            $disciplina = \App\Models\Disciplina::firstOrCreate(
                ['nome' => $nome, 'curso_id' => $curso->id],
                [
                    'professor_id' => $professor->id,
                    'carga_horaria' => 40
                ]
            );

            // Matricular o aluno
            $matricula = \App\Models\MatriculaDisciplina::firstOrCreate(
                ['signin_id' => $aluno->id, 'disciplina_id' => $disciplina->id],
                [
                    'status' => 'em_andamento',
                    'total_aulas' => 40,
                    'presencas' => rand(30, 40),
                    'faltas' => rand(0, 5),
                ]
            );

            // Gerar Notas
            \App\Models\Nota::updateOrCreate(
                ['matricula_disciplina_id' => $matricula->id],
                [
                    'b1_t1' => rand(7, 10),
                    'b1_t2' => rand(7, 10),
                    'b1_t3' => rand(7, 10),
                    'b1_aval' => rand(7, 10),
                    'b1_total' => rand(8, 10),
                    'b2_t1' => rand(7, 10),
                    'b2_t2' => rand(7, 10),
                    'b2_t3' => rand(7, 10),
                    'b2_aval' => rand(7, 10),
                    'b2_total' => rand(8, 10),
                    'media_final' => rand(8, 10),
                ]
            );

            // Criar Atividades
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\Atividade::updateOrCreate(
                    [
                        'disciplina_id' => $disciplina->id,
                        'titulo' => "Atividade Prática $i - $nome"
                    ],
                    [
                        'professor_id' => $professor->id,
                        'descricao' => "Descrição detalhada da atividade $i que compõe a nota do bimestre.",
                        'data_limite' => now()->addDays(rand(1, 30)),
                    ]
                );
            }
        }
    }
}
