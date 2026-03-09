<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $cursos = Curso::when($search, function ($query) use ($search) {
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('tipo', 'like', "%{$search}%");
        })->paginate(5)->withQueryString();

        return view('cursos.index', compact('cursos', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:Graduação,Pós-Graduação,Mestrado,Doutorado',
        ]);

        $curso = Curso::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'curso' => ['id' => $curso->id, 'nome' => $curso->nome],
            ]);
        }

        return redirect()->route('cursos.index')->with('success', 'Curso criado com sucesso!');
    }

    public function edit(Curso $curso)
    {
        $cursos = Curso::paginate(5); // Listagem com paginação
        return view('cursos.index', compact('curso', 'cursos'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:Graduação,Pós-Graduação,Mestrado,Doutorado',
        ]);

        $curso->update($request->validated());

        return redirect()->route('cursos.index')->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('cursos.index')->with('success', 'Curso excluído com sucesso!');
    }
}
