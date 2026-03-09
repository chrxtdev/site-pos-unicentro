<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Illuminate\Http\Request;

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
    public function show(string $id)
    {
        $aluno = Signin::findOrFail($id);
        
        // TODO: In the future, fetch dynamic invoices from ASAAS API using $aluno->asaas_payment_id
        $faturas = collect([]);

        return view('admin.financeiro.show', compact('aluno', 'faturas'));
    }
}
