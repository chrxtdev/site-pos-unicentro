<?php

namespace App\Http\Controllers;

use App\Models\ProcessoSeletivo;
use Illuminate\Http\Request;

class ProcessoSeletivoController extends Controller
{
    // Lista os processos seletivos com paginação
    public function index()
    {
        $processos = ProcessoSeletivo::paginate(10); // Paginação de 10 itens por página
        return view('processos.index', compact('processos'));
    }

    // Exibe o formulário para criar um novo processo seletivo
    public function create()
    {
        return view('processos.create'); // View de criação
    }

    // Armazena um novo processo seletivo no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'numero_etapas' => 'required|integer|min:1',
            'numero_ofertas' => 'required|integer|min:1',
        ]);

        ProcessoSeletivo::create($request->only('nome', 'numero_etapas', 'numero_ofertas'));

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo criado com sucesso!');
    }

    // Exibe o formulário para editar um processo seletivo existente
    public function edit(ProcessoSeletivo $processo)
    {
        return view('processos.create', compact('processo')); // View de edição
    }

    // Atualiza um processo seletivo existente
    public function update(Request $request, ProcessoSeletivo $processo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'numero_etapas' => 'required|integer|min:1',
            'numero_ofertas' => 'required|integer|min:1',
        ]);

        $processo->update($request->only('nome', 'numero_etapas', 'numero_ofertas'));

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo atualizado com sucesso!');
    }

    // Exclui um processo seletivo existente
    public function destroy($id)
    {
        $processo = ProcessoSeletivo::find($id);

        if (!$processo) {
            return redirect()->route('processos.index')->with('error', 'Processo Seletivo não encontrado.');
        }

        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo Seletivo excluído com sucesso!');
    }
}
