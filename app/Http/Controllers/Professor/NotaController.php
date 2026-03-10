<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\MatriculaDisciplina;
use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Se for admin master/comum, ve todas as disciplinas. Se for professor, ve so as dele.
        if ($user->hasRole('admin_master') || $user->hasRole('admin_comum')) {
            $disciplinas = Disciplina::with('curso')->withCount('matriculas')->get();
        } else {
            $disciplinas = Disciplina::where('professor_id', $user->id)->with('curso')->withCount('matriculas')->get();
        }

        return view('professor.disciplinas.index', compact('disciplinas'));
    }

    public function show(Disciplina $disciplina)
    {
        $user = auth()->user();
        // Proteção de acesso
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum') && $disciplina->professor_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        $matriculas = $disciplina->matriculas()->with(['aluno', 'notas'])->get();

        return view('professor.notas.show', compact('disciplina', 'matriculas'));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        if ($disciplina->status === 'fechado') {
            return redirect()->back()->with('error', 'O diário desta disciplina está fechado e não pode mais ser alterado.');
        }

        $notasData = $request->input('notas', []);

        foreach ($notasData as $matriculaId => $dados) {
            $matricula = MatriculaDisciplina::where('id', $matriculaId)
                ->where('disciplina_id', $disciplina->id)
                ->first();

            if ($matricula) {
                // Parse das notas (formato BR pra EN se necessario)
                $b1_t1 = isset($dados['b1_t1']) ? (float)str_replace(',', '.', $dados['b1_t1']) : null;
                $b1_t2 = isset($dados['b1_t2']) ? (float)str_replace(',', '.', $dados['b1_t2']) : null;
                $b1_t3 = isset($dados['b1_t3']) ? (float)str_replace(',', '.', $dados['b1_t3']) : null;
                $b1_aval = isset($dados['b1_aval']) ? (float)str_replace(',', '.', $dados['b1_aval']) : null;
                
                $b1_total = array_sum(array_filter([$b1_t1, $b1_t2, $b1_t3, $b1_aval], fn($v) => $v !== null));

                $b2_t1 = isset($dados['b2_t1']) ? (float)str_replace(',', '.', $dados['b2_t1']) : null;
                $b2_t2 = isset($dados['b2_t2']) ? (float)str_replace(',', '.', $dados['b2_t2']) : null;
                $b2_t3 = isset($dados['b2_t3']) ? (float)str_replace(',', '.', $dados['b2_t3']) : null;
                $b2_aval = isset($dados['b2_aval']) ? (float)str_replace(',', '.', $dados['b2_aval']) : null;
                
                $b2_total = array_sum(array_filter([$b2_t1, $b2_t2, $b2_t3, $b2_aval], fn($v) => $v !== null));

                // Media final é a media dos dois bimestres
                $temNota = false;
                $media_final = 0;
                
                if ($b1_total > 0 || $b2_total > 0) {
                    $media_final = ($b1_total + $b2_total) / 2;
                    $temNota = true;
                }

                Nota::updateOrCreate(
                    ['matricula_disciplina_id' => $matricula->id],
                    [
                        'b1_t1' => $b1_t1, 'b1_t2' => $b1_t2, 'b1_t3' => $b1_t3, 'b1_aval' => $b1_aval, 'b1_total' => $b1_total > 0 ? $b1_total : null,
                        'b2_t1' => $b2_t1, 'b2_t2' => $b2_t2, 'b2_t3' => $b2_t3, 'b2_aval' => $b2_aval, 'b2_total' => $b2_total > 0 ? $b2_total : null,
                        'media_final' => $temNota ? $media_final : null,
                    ]
                );

                // Automação do status da disciplina
                if ($temNota && $media_final >= 7.0 && $b1_total > 0 && $b2_total > 0) {
                    $matricula->update(['status' => 'aprovado']);
                } elseif ($temNota && $media_final < 7.0 && $b1_total > 0 && $b2_total > 0) {
                    $matricula->update(['status' => 'reprovado']);
                } else {
                    $matricula->update(['status' => 'cursando']);
                }
            }
        }

        return redirect()->back()->with('success', 'Notas atualizadas com sucesso!');
    }

    public function fechar(Request $request, Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum') && $disciplina->professor_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        if ($disciplina->status === 'fechado') {
            return redirect()->back()->with('error', 'O diário já está fechado.');
        }

        $matriculas = $disciplina->matriculas()->with('notas')->get();
        if ($matriculas->isEmpty()) {
            return redirect()->back()->with('error', 'Não há alunos matriculados para fechar o diário.');
        }

        foreach ($matriculas as $matricula) {
            if (!$matricula->notas || $matricula->notas->media_final === null) {
                return redirect()->back()->with('error', "O aluno {$matricula->aluno->nome} não possui média final calculada. Todas as notas essenciais devem ser lançadas.");
            }
        }

        $disciplina->update(['status' => 'fechado']);

        return redirect()->back()->with('success', 'Diário fechado com sucesso! Nenhuma nota poderá ser alterada daqui em diante.');
    }
}
