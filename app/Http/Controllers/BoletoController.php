<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BoletoController extends Controller
{
    public function gerar($id, \App\Services\AsaasService $asaasService)
    {
        $inscricao = Signin::findOrFail($id);
        $this->autorizarAcesso($inscricao);

        if ($inscricao->asaas_payment_id) {
            return $this->recuperarBoletoExistente($inscricao, $id);
        }

        try {
            $boleto = $asaasService->gerarBoletoParaInscricao($inscricao);

            if (!$boleto) {
                return redirect()->back()->with('error', 'Erro ao gerar o boleto.');
            }

            $telefone = preg_replace('/[^0-9]/', '', $inscricao->telefone_celular);
            if (strlen($telefone) === 11) {
                $telefone = '+55' . $telefone;
                $this->enviarWhatsApp($telefone, $boleto['bankSlipUrl'], $inscricao->nome);
            }

            return redirect()->route('boleto.mostrar', ['id' => $id, 'url' => $boleto['bankSlipUrl']])
                ->with('success', 'Boleto gerado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar boleto (Controller):', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao gerar o boleto. Por favor, tente novamente ou entre em contato com a secretaria.');
        }
    }

    public function mostrarBoleto(Request $request, $id)
    {
        $inscricao = Signin::findOrFail($id);
        $this->autorizarAcesso($inscricao);
        $linkBoleto = $request->query('url');

        if (!$linkBoleto) {
            return redirect()->back()->with('error', 'URL do boleto não encontrada.');
        }

        return view('boleto.mostrar', compact('inscricao', 'linkBoleto'));
    }

    private function recuperarBoletoExistente(Signin $inscricao, $id)
    {
        Log::info('Cobrança existente encontrada.', ['payment_id' => $inscricao->asaas_payment_id]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => config('services.asaas.key'),
        ])->get(config('services.asaas.url') . '/payments/' . $inscricao->asaas_payment_id);

        $boleto = $response->json();

        // O Asaas converte PIX e CARTÃO em links de Invoice (fatura), enquanto Boleto pode ter ambos
        $urlDePagamento = $boleto['bankSlipUrl'] ?? $boleto['invoiceUrl'] ?? null;

        if ($urlDePagamento) {
            return redirect()->route('boleto.mostrar', ['id' => $id, 'url' => $urlDePagamento]);
        }

        return redirect()->back()->with('error', 'Não foi possível recuperar o boleto existente.');
    }


    private function enviarWhatsApp(string $telefone, string $linkBoleto, string $nome): bool
    {
        $url = config('services.cloudservo.url');

        try {
            $mensagemSecretaria = "Olá $nome, seja bem-vindo(a) à sua nova jornada acadêmica na Pós-Graduação! 🎓\n\n" .
                "Estamos felizes por tê-lo conosco. Caso precise de ajuda com documentação ou informações, estamos à disposição na Secretaria Acadêmica.\n\n" .
                "Desejamos muito sucesso!";

            Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.cloudservo.token'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                'number' => $telefone,
                'body' => $mensagemSecretaria,
            ]);

            $mensagemFinanceiro = "Olá $nome, segue o link do seu boleto referente à matrícula da Pós-Graduação:\n" .
                "$linkBoleto\n\n" .
                "Qualquer dúvida financeira, entre em contato com o setor responsável. 📞";

            Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.cloudservo.token2'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                'number' => $telefone,
                'body' => $mensagemFinanceiro,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagens pelo WhatsApp', [
                'telefone' => $telefone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function autorizarAcesso(Signin $inscricao): void
    {
        $user = auth()->user();

        if ($user->is_admin) {
            return;
        }

        if ($user->email !== $inscricao->email) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar este recurso.');
        }
    }
}
