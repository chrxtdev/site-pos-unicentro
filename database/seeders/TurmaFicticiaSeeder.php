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
        // 1. Limpar MBA Fictício anterior
        \App\Models\Curso::where('nome', 'MBA em Engenharia de Software de Alta Performance')->delete();

        // 1. Criar Professor Fictício
        $professor = \App\Models\User::firstOrCreate(
            ['email' => 'professor.ficticio@unicentro.br'],
            [
                'name' => 'Dr. Ricardo Silva',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // 2. Criar Admin Master (Chris)
        $master = \App\Models\User::firstOrCreate(
            ['email' => 'chris@admin.com'],
            [
                'name' => 'Chris Admin Master',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );
        
        // Garantir que a role admin_master existe
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin_master']);
        $master->assignRole('admin_master');

        // 3. Pegar o primeiro aluno do banco (usuário de teste)
        $aluno = \App\Models\Signin::first();

        if (!$aluno) {
            return;
        }

        // 4. Identificar o Curso Real do Aluno (pelo campo pos_graduacao ou oferta vinculada)
        // No sistema atual, o curso está no campo 'pos_graduacao' do Signin
        $cursoNome = $aluno->pos_graduacao;
        $curso = \App\Models\Curso::where('nome', $cursoNome)->first();

        if (!$curso) {
            // Se o curso não existir na tabela cursos, criamos ele
            $curso = \App\Models\Curso::create([
                'nome' => $cursoNome,
                'sigla' => 'DPP',
                'tipo' => 'Pós-Graduação'
            ]);
        }

        $disciplinas = [
            'Fundamentos de Direito Previdenciário',
            'Cálculos Previdenciários Avançados',
            'Processo Administrativo e Judicial',
            'Regime Geral de Previdência Social',
            'Previdência Complementar',
            'Ética e Prática na Advocacia Previdenciária',
        ];

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
            // Criar Avisos Extras como Atividades (Unificado)
            $avisosExtras = [
                ['titulo' => 'Sejam Bem Vindos!', 'descricao' => "Olá turma, bem vindos a disciplina de $nome. O cronograma completo já está disponível no portal."],
                ['titulo' => 'Apostila Completa em PDF', 'descricao' => "Segue a apostila com os slides da 1ª e 2ª aula reunidos. Bom estudo!", 'arquivo_path' => 'atividades/exemplo.pdf'],
            ];

            foreach ($avisosExtras as $av) {
                \App\Models\Atividade::updateOrCreate(
                    [
                        'disciplina_id' => $disciplina->id,
                        'titulo' => $av['titulo']
                    ],
                    [
                        'professor_id' => $professor->id,
                        'descricao' => $av['descricao'],
                        'arquivo_path' => $av['arquivo_path'] ?? null,
                        'created_at' => now()->subDays(rand(1, 10)),
                    ]
                );
            }
        }
    }
}
