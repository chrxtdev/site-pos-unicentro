<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\MatriculaDisciplina;
use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Se for admin, vê todos os cursos. Se for professor, vê apenas cursos que têm disciplinas dele
        if ($user->hasRole('admin_master') || $user->hasRole('admin_comum')) {
            $cursos = Curso::withCount('disciplinas')->get();
        } else {
            $cursos = Curso::whereHas('disciplinas', function ($query) use ($user) {
                $query->where('professor_id', $user->id);
            })->withCount(['disciplinas' => function ($query) use ($user) {
                $query->where('professor_id', $user->id);
            }])->get();
        }

        return view('professor.disciplinas.index', compact('cursos'));
    }

    public function cursoDisciplinas(Curso $curso)
    {
        $user = auth()->user();
        
        // Proteção de acesso
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum')) {
            $temAcesso = $curso->disciplinas()->where('professor_id', $user->id)->exists();
            if (!$temAcesso) {
                abort(403, 'Você não possui disciplinas vinculadas a esta Turma.');
            }
            $disciplinas = $curso->disciplinas()->where('professor_id', $user->id)->withCount('matriculas')->get();
        } else {
            $disciplinas = $curso->disciplinas()->withCount('matriculas')->get();
        }

        return view('professor.disciplinas.materias', compact('curso', 'disciplinas'));
    }

    public function storeFast(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum')) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'professor_id' => 'nullable|exists:users,id',
        ]);

        $disciplina = Disciplina::create([
            'curso_id' => $request->curso_id,
            'nome' => $request->nome,
            'carga_horaria' => $request->carga_horaria,
            'professor_id' => $request->professor_id,
        ]);

        return redirect()->back()->with('success', 'Disciplina criada e vinculada a turma com sucesso!');
    }

    public function updateFast(Request $request, Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin_master') && !$user->hasRole('admin_comum')) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'professor_id' => 'nullable|exists:users,id',
        ]);

        $disciplina->update([
            'nome' => $request->nome,
            'carga_horaria' => $request->carga_horaria,
            'professor_id' => $request->professor_id,
        ]);

        return redirect()->back()->with('success', 'Disciplina atualizada com sucesso!');
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

                if (isset($dados['faltas']) && is_numeric($dados['faltas'])) {
                    $faltasForm = (int) $dados['faltas'];
                    $faltasFinal = max(0, min($faltasForm, $disciplina->carga_horaria));
                } else {
                    $faltasFinal = $matricula->faltas ?? 0;
                }

                $presencasFinal = $disciplina->carga_horaria - $faltasFinal;

                $aprovadoPorNota = ($temNota && $media_final >= 7.0 && $b1_total > 0 && $b2_total > 0);
                $freqPercent = $disciplina->carga_horaria > 0 ? ($presencasFinal / $disciplina->carga_horaria) : 1;
                $aprovadoPorFalta = $freqPercent >= 0.75;
                
                $novoStatus = 'cursando';

                if ($b1_total > 0 && $b2_total > 0) {
                    if ($aprovadoPorNota && $aprovadoPorFalta) {
                        $novoStatus = 'aprovado';
                    } else {
                        $novoStatus = 'reprovado';
                    }
                }

                $matricula->update([
                    'faltas' => $faltasFinal,
                    'presencas' => $presencasFinal,
                    'status' => $novoStatus
                ]);
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
