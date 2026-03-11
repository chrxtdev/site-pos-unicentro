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
        $inscricao->asaas_customer_id = $clienteId;
        $inscricao->asaas_installment_id = $boleto['installment'] ?? null;
        $inscricao->save();

        return $boleto;
    }

    public function buscarFaturasParcelamento(string $installmentId)
    {
        $response = Http::withHeaders([
            'access_token' => config('services.asaas.key'),
        ])->get(config('services.asaas.url') . '/payments', [
            'installment' => $installmentId,
            'limit' => 100,
        ]);

        if ($response->failed()) {
            Log::error('Erro ao buscar faturas no Asaas:', ['response' => $response->body()]);
            return collect();
        }

        return collect($response->json('data'))->sortBy('dueDate');
    }

    public function buscarPagamentosPorCliente(string $customerId)
    {
        $response = Http::withHeaders([
            'access_token' => config('services.asaas.key'),
        ])->get(config('services.asaas.url') . '/payments', [
            'customer' => $customerId,
            'limit' => 100,
        ]);

        if ($response->failed()) {
            Log::error('Erro ao buscar pagamentos por cliente no Asaas:', ['response' => $response->body()]);
            return collect();
        }

        return collect($response->json('data'))->sortBy('dueDate');
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
            default => 'BOLETO', // Removemos cartão, aceitando BOLETO como padrão
        };

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => config('services.asaas.key'),
        ])->post(config('services.asaas.url') . '/payments', [
            'customer' => $clienteId,
            'billingType' => $billingType,
            'dueDate' => now()->addDays(5)->toDateString(), // 1º Vencimento 5 dias após inscrição
            'installmentCount' => 12, // Carnê com 12 parcelas
            'installmentValue' => (float) $inscricao->valor_mensalidade, // Valor fixo da parcela (Ex: 1000)
            'description' => 'Carnê de Mensalidades (12x) - ' . $inscricao->pos_graduacao,
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
