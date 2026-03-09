<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AtividadeController extends Controller
{
    public function index(Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum') && $disciplina->professor_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $atividades = $disciplina->atividades()->latest()->get();
        return view('professor.atividades.index', compact('disciplina', 'atividades'));
    }

    public function store(Request $request, Disciplina $disciplina)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240', // max 10MB
            'link_externo' => 'nullable|url',
            'data_limite' => 'nullable|date',
        ]);

        $atividade = new Atividade($validated);
        $atividade->disciplina_id = $disciplina->id;
        $atividade->professor_id = auth()->id();

        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('atividades', 'public');
            $atividade->arquivo_path = $path;
        }

        $atividade->save();

        return redirect()->back()->with('success', 'Atividade postada no mural!');
    }

    public function update(Request $request, Atividade $atividade)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'link_externo' => 'nullable|url',
            'data_limite' => 'nullable|date',
        ]);

        $atividade->update($validated);

        if ($request->hasFile('arquivo')) {
            $request->validate(['arquivo' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240']);
            if ($atividade->arquivo_path) {
                Storage::disk('public')->delete($atividade->arquivo_path);
            }
            $atividade->arquivo_path = $request->file('arquivo')->store('atividades', 'public');
            $atividade->save();
        }

        return redirect()->back()->with('success', 'Atividade atualizada!');
    }

    public function destroy(Atividade $atividade)
    {
        if ($atividade->arquivo_path) {
            Storage::disk('public')->delete($atividade->arquivo_path);
        }
        $atividade->delete();

        return redirect()->back()->with('success', 'Atividade removida do mural.');
    }
}
