<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ProcessoSeletivoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Professor\NotaController;
use App\Http\Controllers\Professor\AtividadeController;
use App\Http\Controllers\FinanceiroController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $cursosDestaque = \App\Models\Curso::latest()->take(3)->get();
    return view('welcome', compact('cursosDestaque'));
});

// Rotas públicas — formulário de inscrição (aluno não precisa de login)
Route::get('/inscricao', [InscricaoController::class , 'index'])->name('inscricao.index');
Route::post('/inscricao', [InscricaoController::class , 'store'])->middleware('throttle:5,1')->name('inscricao.store');

// Rotas de comprovante protegidas por autenticação (corrige IDOR)
Route::middleware('auth')->group(function () {
    Route::get('/inscricao/comprovante/{id}', [InscricaoController::class , 'comprovante'])->name('inscricao.comprovante');
    Route::get('/inscricao/comprovante/{id}/download', [InscricaoController::class , 'downloadComprovante'])->name('inscricao.downloadComprovante');
    Route::get('/inscricao/sucesso/{id}', [InscricaoController::class , 'sucesso'])->name('inscricao.sucesso');
});

// Rotas protegidas — exigem login
Route::middleware('auth')->group(function () {
    // Logout seguro apenas via POST (definido no auth.php)

    // Redirecionador automático para o painel correto
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

        // Portal do Aluno
        Route::get('/portal', [AdminController::class , 'portal'])->name('aluno.portal');
        Route::get('/portal/financeiro', [AdminController::class , 'portalFinanceiro'])->name('aluno.financeiro');
        Route::get('/portal/notas', [AdminController::class , 'portalNotas'])->name('aluno.notas');
        Route::get('/portal/documentos', [AdminController::class , 'portalDocumentos'])->name('aluno.documentos');
        Route::get('/portal/documentos/matricula', [AdminController::class , 'downloadMatricula'])->name('aluno.documentos.matricula');
        Route::get('/portal/mural/{disciplina?}', [AdminController::class , 'portalMural'])->name('aluno.mural');
        Route::get('/portal/boletim/pdf', [AdminController::class , 'downloadBoletim'])->name('aluno.boletim.pdf');

        // Rota de retorno do Impersonate (fora do grupo admin para o aluno impersonado conseguir acessar)
        Route::get('/impersonate-leave', [AdminController::class , 'leaveImpersonate'])->name('admin.impersonate.leave');

        // Painel Administrativo
        Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
            Route::get('/dashboard', [AdminController::class , 'index'])->name('admin.dashboard');

            Route::resource('/processos', ProcessoSeletivoController::class);
            Route::resource('cursos', CursoController::class);

            // Acesso como Aluno (Impersonate)
            Route::get('/impersonate/{id}', [AdminController::class , 'impersonate'])->name('admin.impersonate');

            Route::get('/ofertas', [OfertaController::class , 'index'])->name('ofertas.index');
            Route::post('/ofertas', [OfertaController::class , 'store'])->name('ofertas.store');
            Route::get('/ofertas/{oferta}/edit', [OfertaController::class , 'edit'])->name('ofertas.edit');
            Route::put('/ofertas/{oferta}', [OfertaController::class , 'update'])->name('ofertas.update');
            Route::delete('/ofertas/{oferta}', [OfertaController::class , 'destroy'])->name('ofertas.destroy');
            Route::post('/ofertas/{oferta}/duplicate', [OfertaController::class , 'duplicate'])->name('ofertas.duplicate');

            Route::get('/alunos', [AlunoController::class , 'index'])->name('alunos.index');
            Route::get('/alunos/{id}/edit', [AlunoController::class , 'edit'])->name('alunos.edit');
            Route::put('/alunos/{id}', [AlunoController::class , 'update'])->name('alunos.update');

            // Configurações Globais e Usuários (Apenas Master ou Admin Comum)
            Route::group(['middleware' => ['role:admin_master|admin_comum']], function () {
                Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
                Route::post('/configuracoes/admin', [ConfiguracaoController::class, 'storeAdmin'])->name('configuracoes.storeAdmin');
                Route::put('/configuracoes/admin/{admin}', [ConfiguracaoController::class, 'updateAdmin'])->name('configuracoes.updateAdmin');
                Route::delete('/configuracoes/admin/{admin}', [ConfiguracaoController::class, 'destroyAdmin'])->name('configuracoes.destroyAdmin');
            });
            
            // Configurações do App (apenas master suporta para proteger asaas_tokens)
            Route::post('/configuracoes/app', [ConfiguracaoController::class, 'storeAppConfig'])
                ->middleware(['role:admin_master'])->name('configuracoes.storeAppConfig');

            // Setor Financeiro (Apenas administradores com cargo Master ou Financeiro)
            Route::group(['middleware' => ['role:admin_master|financeiro']], function () {
                Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');
                Route::get('/financeiro/{id}', [FinanceiroController::class, 'show'])->name('financeiro.show');
            });
        }
        );

        // Painel Acadêmico (Professor e Admins)
        Route::group(['prefix' => 'admin/academico', 'middleware' => ['admin']], function () {
            // Notas (Diário de Turma)
            Route::get('/disciplinas', [NotaController::class, 'index'])->name('professor.disciplinas.index')->middleware('can:view_notas');
            Route::get('/disciplinas/{disciplina}/notas', [NotaController::class, 'show'])->name('professor.notas.show')->middleware('can:view_notas');
            Route::post('/disciplinas/{disciplina}/notas', [NotaController::class, 'update'])->name('professor.notas.update')->middleware('can:manage_notas');
            Route::post('/disciplinas/{disciplina}/fechar', [NotaController::class, 'fechar'])->name('professor.notas.fechar')->middleware('can:manage_notas');
            
            // Atividades (Mural)
            Route::get('/disciplinas/{disciplina}/atividades', [AtividadeController::class, 'index'])->name('professor.atividades.index')->middleware('can:view_atividades');
            Route::post('/disciplinas/{disciplina}/atividades', [AtividadeController::class, 'store'])->name('professor.atividades.store')->middleware('can:manage_atividades');
            Route::put('/atividades/{atividade}', [AtividadeController::class, 'update'])->name('professor.atividades.update')->middleware('can:manage_atividades');
            Route::delete('/atividades/{atividade}', [AtividadeController::class, 'destroy'])->name('professor.atividades.destroy')->middleware('can:manage_atividades');
        });

        // Boletos
        Route::get('/boleto/{id}', [BoletoController::class , 'gerar'])->name('boleto.gerar');
        Route::get('/boleto/mostrar/{id}', [BoletoController::class , 'mostrarBoleto'])->name('boleto.mostrar');
        Route::get('/boleto/reimprimir/{paymentId}', [AlunoController::class , 'reimprimirBoleto'])->name('boleto.reimprimir');

        // Perfil
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
    });

require __DIR__ . '/auth.php';