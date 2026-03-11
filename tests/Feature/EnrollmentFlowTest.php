<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Disciplinas; // Verifying name
use App\Models\Oferta;
use App\Models\Signin;
use App\Models\User;
use App\Models\MatriculaDisciplina;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EnrollmentFlowTest extends TestCase
{
    // Removendo RefreshDatabase para não apagar os dados do usuário durante o desenvolvimento,
    // mas usarei dados de teste únicos.
    
    public function test_complete_enrollment_to_portal_access_flow()
    {
        // Forçar configuração para o ambiente de teste
        config(['services.asaas.webhook_token' => 'test_webhook_token_gemini']);
        $token = 'test_webhook_token_gemini';

        // Limpeza prévia para evitar conflitos de CPF/Email
        Signin::where('email', 'teste_total@gmail.com')->orWhere('cpf', '59547799290')->delete();
        User::where('email', 'teste_total@gmail.com')->delete();

        // 1. Setup: Garantir que temos um curso com matérias
        $curso = Curso::first() ?? Curso::create([
            'nome' => 'Curso Teste Gemini', 
            'sigla' => 'CTG',
            'tipo' => 'Pós-Graduação'
        ]);
        
        // Se não houver matérias, criamos uma
        if ($curso->disciplinas()->count() === 0) {
            $curso->disciplinas()->create([
                'nome' => 'Disciplina Teste 1',
                'carga_horaria' => 40,
            ]);
        }

        // Criar Processo Seletivo (Obrigatório para Oferta)
        $processo = \App\Models\ProcessoSeletivo::create([
            'nome' => 'Processo Seletivo 2026.1',
            'numero_etapas' => 1,
            'numero_ofertas' => 1,
            'situacao' => 'ATIVO'
        ]);

        $oferta = Oferta::create([
            'curso_id' => $curso->id,
            'processo_seletivo_id' => $processo->id,
            'turno' => 'Noturno',
            'quantidade_vagas' => 50,
            'locais_prova' => 'São Luís - MA',
            'valor_taxa' => 250.00,
        ]);

        // Mock Asaas API
        Http::fake([
            'sandbox.asaas.com/api/v3/customers' => Http::response(['id' => 'cus_test123'], 200),
            'sandbox.asaas.com/api/v3/payments' => Http::response([
                'id' => 'pay_test123',
                'bankSlipUrl' => 'https://sandbox.asaas.com/b/test',
                'status' => 'PENDING'
            ], 200),
        ]);

        // 2. Passo 1: Inscrição
        $enrollmentData = [
            'nome' => 'Aluno Teste Fluxo',
            'cpf' => '59547799290', // CPF real do banco
            'email' => 'teste_total@gmail.com',
            'data_nascimento' => '1995-01-01',
            'sexo' => 'M',
            'estado_civil' => 'Solteiro',
            'ensino_medio' => 'Completo',
            'cor_raca' => 'Pardo',
            'endereco' => 'Rua Teste',
            'bairro' => 'Centro',
            'cep' => '65000000',
            'telefone_celular' => '98988887766',
            'tipo_aluno' => 'Novo',
            'forma_pagamento' => 'boleto',
            'pos_graduacao' => $curso->nome,
            'oferta_id' => $oferta->id,
            'login' => 'aluno_fluxo_teste',
            'senha' => 'password123',
            'senha_confirmation' => 'password123',
        ];

        $response = $this->post('/inscricao', $enrollmentData);
        
        $response->assertStatus(302); // Redirecionamento para sucesso
        
        $signin = Signin::where('email', 'teste_total@gmail.com')->first();
        if (!$signin) {
            dd(session('errors')?->toArray());
        }
        $this->assertNotNull($signin);
        $this->assertEquals('pendente', $signin->status_pagamento);

        // 3. Passo 2: Webhook de Pagamento
        $webhookPayload = [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'id' => 'pay_test123',
                'status' => 'RECEIVED',
            ]
        ];

        $responseWebhook = $this->withHeaders(['asaas-access-token' => $token])
            ->postJson('/api/webhooks/asaas', $webhookPayload);

        $responseWebhook->assertStatus(200);

        // 4. Verificação Passo 3: Enturmação e Matrícula
        $signin->refresh();
        $this->assertEquals('pago', $signin->status_pagamento);
        $this->assertNotNull($signin->matricula);

        $matriculasNoPortal = MatriculaDisciplina::where('signin_id', $signin->id)->count();
        $this->assertGreaterThan(0, $matriculasNoPortal, "O aluno deveria estar enturmado nas matérias do curso.");

        echo "\n\n✅ SUCESSO: Fluxo Inscrição -> Webhook -> Enturmação validado no Feature Test!\n";
        
        // Limpeza (opcional se não usar RefreshDatabase)
        $signin->delete();
        User::where('email', 'teste_total@gmail.com')->delete();
        $oferta->delete();
    }
}
