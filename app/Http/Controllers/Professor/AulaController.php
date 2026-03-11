<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\AulaAluno;
use App\Models\Disciplina;
use App\Models\MatriculaDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaController extends Controller
{
    public function index(Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        $aulas = $disciplina->aulas()->latest('data')->get();
        
        $totalHorasDadas = $aulas->sum('qtd_aulas');
        $progresso = $disciplina->carga_horaria > 0 ? ($totalHorasDadas / $disciplina->carga_horaria) * 100 : 0;

        return view('professor.aulas.index', compact('disciplina', 'aulas', 'totalHorasDadas', 'progresso'));
    }

    public function create(Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        $matriculas = $disciplina->matriculas()->with('aluno')->get();

        return view('professor.aulas.create', compact('disciplina', 'matriculas'));
    }

    public function store(Request $request, Disciplina $disciplina)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'data' => 'required|date',
            'hora' => 'nullable',
            'conteudo' => 'nullable|string',
            'qtd_aulas' => 'required|integer|min:1',
            'chamada' => 'required|array', // signin_id => status (1: presente, 0: falta)
        ]);

        DB::transaction(function () use ($request, $disciplina) {
            $aula = Aula::create([
                'disciplina_id' => $disciplina->id,
                'data' => $request->data,
                'hora' => $request->hora,
                'conteudo' => $request->conteudo,
                'qtd_aulas' => $request->qtd_aulas,
            ]);

            foreach ($request->chamada as $signinId => $status) {
                AulaAluno::create([
                    'aula_id' => $aula->id,
                    'signin_id' => $signinId,
                    'presente' => $status == 1,
                ]);

                $this->sincronizarFaltasDoAluno($signinId, $disciplina);
            }
        });

        return redirect()->route('professor.aulas.index', $disciplina)->with('success', 'Aula e chamada registradas com sucesso!');
    }

    public function edit(Aula $aula)
    {
        $disciplina = $aula->disciplina;
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        $matriculas = $disciplina->matriculas()->with('aluno')->get();
        $presencas = $aula->presencas->pluck('presente', 'signin_id')->toArray();

        return view('professor.aulas.edit', compact('aula', 'disciplina', 'matriculas', 'presencas'));
    }

    public function update(Request $request, Aula $aula)
    {
        $disciplina = $aula->disciplina;
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'data' => 'required|date',
            'hora' => 'nullable',
            'conteudo' => 'nullable|string',
            'qtd_aulas' => 'required|integer|min:1',
            'chamada' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $aula, $disciplina) {
            $aula->update([
                'data' => $request->data,
                'hora' => $request->hora,
                'conteudo' => $request->conteudo,
                'qtd_aulas' => $request->qtd_aulas,
            ]);

            // Atualiza presenças
            foreach ($request->chamada as $signinId => $status) {
                AulaAluno::updateOrCreate(
                    ['aula_id' => $aula->id, 'signin_id' => $signinId],
                    ['presente' => $status == 1]
                );

                $this->sincronizarFaltasDoAluno($signinId, $disciplina);
            }
        });

        return redirect()->route('professor.aulas.index', $disciplina)->with('success', 'Aula e chamada atualizadas com sucesso!');
    }

    public function destroy(Aula $aula)
    {
        $disciplina = $aula->disciplina;
        $user = auth()->user();
        if (!$user->is_admin && !$user->hasRole(['admin_master', 'admin_comum']) && $disciplina->professor_id !== $user->id) {
            abort(403);
        }

        DB::transaction(function () use ($aula, $disciplina) {
            $alunosAfetados = $aula->presencas->pluck('signin_id')->toArray();
            $aula->delete();

            foreach ($alunosAfetados as $signinId) {
                $this->sincronizarFaltasDoAluno($signinId, $disciplina);
            }
        });

        return redirect()->back()->with('success', 'Aula removida.');
    }

    private function sincronizarFaltasDoAluno($signinId, $disciplina)
    {
        $matricula = MatriculaDisciplina::where('signin_id', $signinId)
            ->where('disciplina_id', $disciplina->id)
            ->first();

        if ($matricula) {
            // Calcula faltas: soma das qtd_aulas onde o aluno NÃO estava presente
            $faltas = Aula::where('disciplina_id', $disciplina->id)
                ->whereHas('presencas', function ($query) use ($signinId) {
                    $query->where('signin_id', $signinId)->where('presente', false);
                })->sum('qtd_aulas');

            $presencas = max(0, $disciplina->carga_horaria - $faltas);
            
            // Recalcula status
            $freqPercent = $disciplina->carga_horaria > 0 ? ($presencas / $disciplina->carga_horaria) : 1;
            $aprovadoPorFalta = $freqPercent >= 0.75;
            
            $nota = $matricula->notas;
            $b1_total = $nota->b1_total ?? 0;
            $b2_total = $nota->b2_total ?? 0;
            $media_final = $nota->media_final ?? 0;
            $aprovadoPorNota = ($media_final >= 7.0 && $b1_total > 0 && $b2_total > 0);

            $novoStatus = 'cursando';
            if ($b1_total > 0 && $b2_total > 0) {
                $novoStatus = ($aprovadoPorNota && $aprovadoPorFalta) ? 'aprovado' : 'reprovado';
            }

            $matricula->update([
                'faltas' => $faltas,
                'presencas' => $presencas,
                'status' => $novoStatus
            ]);
        }
    }
}
