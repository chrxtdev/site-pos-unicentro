<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Signin;
use App\Models\Curso;
use App\Services\AsaasService;
use Illuminate\Http\Request;

class TestAsaasProcess extends Command
{
    protected $signature = 'asaas:test-e2e';
    protected $description = 'Testa o fluxo completo de Inscrição -> Asaas -> Webhook';

    public function handle()
    {
        $this->info("Iniciando Teste E2E do Fluxo Asaas...");

        $curso = Curso::first();
        $emailTeste = 'teste.asaas.' . time() . '@exemplo.com';

        $inscricao = Signin::create([
            'nome' => 'Usuário Teste E2E',
            'cpf' => '00000000000',
            'email' => $emailTeste,
            'telefone_celular' => '11999999999',
            'pos_graduacao' => $curso ? $curso->nome : 'Curso Teste',
            'valor_mensalidade' => 1000.00,
            'forma_pagamento' => 'pix',
            'status_pagamento' => 'pendente'
        ]);

        $this->info("1. Inscrição criada: ID {$inscricao->id}");

        $asaasService = app(AsaasService::class);
        $boleto = $asaasService->gerarBoletoParaInscricao($inscricao);

        if (!$boleto || !isset($boleto['id'])) {
            $this->error("FALHA: Não foi possível gerar cobrança no Asaas.");
            $inscricao->delete();
            return Command::FAILURE;
        }

        $inscricao->refresh();
        $this->info("2. Cobrança Asaas gerada! Installment ID: {$inscricao->asaas_installment_id}");

        if (!$inscricao->asaas_installment_id) {
            $this->error("FALHA: Installment ID não foi salvo!");
            $inscricao->delete();
            return Command::FAILURE;
        }

        $payload = [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'id' => 'pay_ficticio_123',
                'installment' => $inscricao->asaas_installment_id,
                'status' => 'RECEIVED'
            ]
        ];

        $request = Request::create('/api/webhooks/asaas', 'POST', [], [], [], [
            'HTTP_asaas-access-token' => config('services.asaas.webhook_token')
        ], json_encode($payload));
        
        $request->headers->set('Content-Type', 'application/json');

        $controller = app(\App\Http\Controllers\Api\AsaasWebhookController::class);
        $response = $controller->handleAsaas($request);

        $this->info("3. Webhook simulado enviado. Status: {$response->getStatusCode()}");

        $inscricao->refresh();

        if ($inscricao->status_pagamento === 'pago' && $inscricao->matricula) {
            $this->info("✅ SUCESSO! Inscrição mudou para 'pago' e matrícula {$inscricao->matricula} foi gerada!");
            $ret = Command::SUCCESS;
        } else {
            $this->error("❌ FALHA: O status não mudou ou a matrícula não foi gerada.");
            $this->line("Status atual: {$inscricao->status_pagamento}");
            $ret = Command::FAILURE;
        }

        $inscricao->delete();
        $this->line("Limpeza: Inscrição de teste apagada.");

        return $ret;
    }
}
