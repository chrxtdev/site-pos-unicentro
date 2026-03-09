<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Signin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    public function handleAsaas(Request $request)
    {
        // 1. Validar access_token do Asaas (autenticação do webhook)
        $tokenRecebido = $request->header('asaas-access-token');
        $tokenEsperado = config('services.asaas.webhook_token');

        if (!$tokenEsperado || $tokenRecebido !== $tokenEsperado) {
            Log::warning('Webhook Asaas: token de autenticação inválido ou ausente.');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 2. Extrair payload
        $content = $request->getContent();
        $payload = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($payload)) {
            $payload = $request->all();
        }

        $event = $payload['event'] ?? null;
        $paymentId = $payload['payment']['id'] ?? null;
        $status = $payload['payment']['status'] ?? null;

        // 3. Log sanitizado (apenas IDs e status, sem dados pessoais)
        Log::info('Webhook Asaas recebido.', [
            'event' => $event,
            'payment_id' => $paymentId,
            'status' => $status,
        ]);

        $eventosValidos = ['PAYMENT_RECEIVED', 'PAYMENT_CONFIRMED'];

        if ($event && in_array($event, $eventosValidos) && $paymentId) {
            $inscricao = Signin::where('asaas_payment_id', $paymentId)->first();

            if ($inscricao) {
                $inscricao->status_pagamento = 'pago';
                $inscricao->save();
                Log::info('Pagamento confirmado.', ['signin_id' => $inscricao->id, 'payment_id' => $paymentId]);
            } else {
                Log::warning('Nenhuma inscrição encontrada para este payment_id.', ['payment_id' => $paymentId]);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}