<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Illuminate\Http\Request;
use App\Services\AsaasService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Administradores e role financeiro têm acesso via Middleware
        
        $query = Signin::query();

        // Filtro por termo de busca (nome, cpf, email)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            // Signin encrypts some fields, so we need to fetch and filter in memory if searching in encrypted fields
            // but for 'nome' and 'email' we can query directly
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por status de pagamento
        if ($request->filled('status_pagamento')) {
            $query->where('status_pagamento', $request->status_pagamento);
        }

        // Filtro por curso
        if ($request->filled('curso')) {
            $query->where('pos_graduacao', 'like', "%{$request->curso}%");
        }

        $alunos = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.financeiro.index', compact('alunos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, AsaasService $asaasService)
    {
        $aluno = Signin::findOrFail($id);
        
        $faturasRaw = collect([]);

        if ($aluno->asaas_installment_id) {
            $faturasRaw = $asaasService->buscarFaturasParcelamento($aluno->asaas_installment_id);
        } elseif ($aluno->asaas_payment_id) {
            try {
                $response = Http::withHeaders([
                    'access_token' => config('services.asaas.key'),
                ])->get(config('services.asaas.url') . '/payments/' . $aluno->asaas_payment_id);
                
                if ($response->successful()) {
                    $faturasRaw = collect([$response->json()]);
                }
            } catch (\Exception $e) {
                // Silencioso
            }
        }
        
        // Converter em objetos para que a View possa usar ->status, ->dueDate etc.
        $faturas = $faturasRaw->map(function ($fatura) {
            return (object) $fatura;
        });

        // Auto-Cura (Fallback de Webhook) se verificado no Admin
        if ($aluno->status_pagamento !== 'pago' && $faturasRaw->count() > 0) {
            $faturaPaga = $faturasRaw->firstWhere(function ($fatura) {
                return in_array($fatura['status'], ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH']);
            });

            if ($faturaPaga) {
                $aluno->status_pagamento = 'pago';
                if (!$aluno->matricula) {
                    $geradorService = app(\App\Services\GeradorMatriculaService::class);
                    $aluno->matricula = $geradorService->gerarMatricula($aluno);
                }
                $aluno->save();
                Log::info('Auto-cura Asaas via Visualização Admin: Pagamento detectado como Pago.', ['signin' => $aluno->id]);
            }
        }

        return view('admin.financeiro.show', compact('aluno', 'faturas'));
    }
}
