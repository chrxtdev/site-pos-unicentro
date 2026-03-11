<?php

namespace App\Http\Controllers;

use App\Models\ProcessoSeletivo;
use Illuminate\Http\Request;

class ProcessoSeletivoController extends Controller
{
    public function index()
    {
        $processos = ProcessoSeletivo::latest('id')->paginate(10);
        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        return view('processos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'numero_etapas' => 'required|integer|min:1',
            'numero_ofertas' => 'required|integer|min:1',
            'situacao' => 'required|in:ATIVO,INATIVO',
        ]);

        ProcessoSeletivo::create($validated);

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo criado com sucesso!');
    }

    public function edit(ProcessoSeletivo $processo)
    {
        return view('processos.create', compact('processo'));
    }

    public function update(Request $request, ProcessoSeletivo $processo)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'numero_etapas' => 'required|integer|min:1',
            'numero_ofertas' => 'required|integer|min:1',
            'situacao' => 'required|in:ATIVO,INATIVO',
        ]);

        $processo->update($validated);

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo atualizado com sucesso!');
    }

    public function destroy(ProcessoSeletivo $processo)
    {
        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo excluído com sucesso!');
    }
}
