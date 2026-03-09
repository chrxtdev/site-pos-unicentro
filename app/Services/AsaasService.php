<?php

namespace App\Services;

use App\Models\Signin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    public function gerarBoletoParaInscricao(Signin $inscricao)
    {
        $telefone = $this->formatarTelefone($inscricao->telefone_celular);
        if (!$telefone) return null;

        $clienteId = $this->criarClienteAsaas($inscricao, $telefone);
        if (!$clienteId) return null;

        $boleto = $this->criarBoletoAsaas($clienteId, $inscricao);
        if (!$boleto) return null;

        $inscricao->asaas_payment_id = $boleto['id'];
        $inscricao->save();

        return $boleto;
    }

    private function formatarTelefone(string $telefone): ?string
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        return strlen($telefone) === 11 ? '+55' . $telefone : null;
    }

    private function criarClienteAsaas(Signin $inscricao, string $telefone): ?string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => config('services.asaas.key'),
        ])->post(config('services.asaas.url') . '/customers', [
            'name' => $inscricao->nome,
            'cpfCnpj' => $inscricao->cpf,
            'email' => $inscricao->email,
            'phone' => $telefone,
        ]);

        $cliente = $response->json();

        if (!isset($cliente['id'])) {
            Log::error('Erro ao criar cliente no ASAAS (Service):', ['response' => $response->body()]);
            return null;
        }

        return $cliente['id'];
    }

    private function criarBoletoAsaas(string $clienteId, Signin $inscricao): ?array
    {
        $billingType = match ($inscricao->forma_pagamento) {
            'pix' => 'PIX',
            'cartao' => 'CREDIT_CARD',
            default => 'BOLETO',
        };

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => config('services.asaas.key'),
        ])->post(config('services.asaas.url') . '/payments', [
            'customer' => $clienteId,
            'billingType' => $billingType,
            'dueDate' => now()->addDays(7)->toDateString(),
            'value' => (float) $inscricao->valor_mensalidade,
            'description' => 'Mensalidade do curso ' . $inscricao->pos_graduacao,
        ]);

        $boleto = $response->json();

        $urlPagamento = $boleto['bankSlipUrl'] ?? $boleto['invoiceUrl'] ?? null;

        if (!isset($boleto['id']) || !$urlPagamento) {
            Log::error('Erro ao gerar cobrança no ASAAS (Service):', ['response' => $response->body()]);
            return null;
        }
        
        // Mantém coesão com código legado injetando a URL recuperada na mesma chave 
        $boleto['bankSlipUrl'] = $urlPagamento;

        return $boleto;
    }
}
