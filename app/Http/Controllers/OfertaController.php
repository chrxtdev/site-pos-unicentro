<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Oferta;
use App\Models\ProcessoSeletivo;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function index(Request $request)
    {
        $processoId = $request->input('processo_seletivo_id') ?? $request->input('processo');

        $ofertas = Oferta::with('curso')
            ->when($processoId, function ($query) use ($processoId) {
                $query->where('processo_seletivo_id', $processoId);
            })
            ->paginate(15) // Aumentando um pouco o padrão
            ->withQueryString();
            
        $processosSeletivos = ProcessoSeletivo::select('id', 'nome')->get();
        $cursos = \App\Models\Curso::select('id', 'nome')->get();

        return view('ofertas.index', compact('ofertas', 'processosSeletivos', 'cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'processo_seletivo_id' => 'required|exists:processo_seletivos,id',
            'curso_id' => 'required|exists:cursos,id', // Alterado para validar ID de curso
            'turno' => 'required|string|max:255',
            'quantidade_vagas' => 'required|integer|min:1',
            'locais_prova' => 'required|string|max:255',
            'valor_taxa' => 'nullable|numeric|min:0',
            'data_vencimento_taxa' => 'nullable|date',
            'conta_recebimento' => 'nullable|string|max:255',
        ]);

        Oferta::create($request->validated());

        return redirect()->route('ofertas.index')->with('success', 'Oferta criada com sucesso!');
    }

    public function edit(Oferta $oferta)
    {
        $processosSeletivos = ProcessoSeletivo::select('id', 'nome')->get();
        $ofertas = Oferta::with('curso')->paginate(5);
        $cursos = Curso::select('id', 'nome')->get();
        return view('ofertas.index', compact('cursos', 'oferta', 'ofertas', 'processosSeletivos'));
    }

    public function update(Request $request, Oferta $oferta)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id', // Verifica se o curso selecionado existe na tabela cursos
            'processo_seletivo_id' => 'required|exists:processo_seletivos,id', // Verifica se o processo seletivo existe
            'turno' => 'required|string|max:255',
            'quantidade_vagas' => 'required|integer|min:1',
            'locais_prova' => 'required|string|max:255',
            'valor_taxa' => 'nullable|numeric|min:0',
            'data_vencimento_taxa' => 'nullable|date',
            'conta_recebimento' => 'nullable|string|max:255',
        ]);

        $oferta->update($request->validated());

        return redirect()->route('ofertas.index')->with('success', 'Oferta atualizada com sucesso!');
    }

    public function destroy(Oferta $oferta)
    {
        $oferta->delete();

        return redirect()->route('ofertas.index')->with('success', 'Oferta excluída com sucesso!');
    }

    public function duplicate(Oferta $oferta)
    {
        Oferta::create($oferta->replicate()->toArray());

        return redirect()->route('ofertas.index')->with('success', 'Oferta duplicada com sucesso!');
    }
}