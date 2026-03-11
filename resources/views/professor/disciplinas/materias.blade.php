@extends('layouts.admin')

@section('title', 'Disciplinas da Turma')

@section('content')
    <div class="max-w-7xl mx-auto flex flex-col gap-8 animate-fade-in">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('professor.disciplinas.index') }}" class="text-slate-400 hover:text-blue-500 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i> Voltar para Turmas
                    </a>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tighter flex items-center gap-4">
                    {{ $curso->nome }}
                    <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-[0.2em] border border-blue-500/20 shadow-glow">
                        Turma Base
                    </span>
                </h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-medium">
                    Gestão de diários e registros de disciplinas vinculadas a este curso.
                </p>
            </div>
            
            @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalNovaDisciplina').classList.remove('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-plus"></i> Nova Disciplina
                </button>
            </div>
            @endif
        </div>

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($disciplinas as $disciplina)
                <div class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-soft overflow-hidden flex flex-col group hover:shadow-2xl transition-all duration-500">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 flex-1 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl group-hover:bg-emerald-500/10 transition-all"></div>
                        
                        @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
                            <div class="absolute top-4 right-4 flex gap-2">
                                <button type="button" onclick="abrirModalEdicao({{ $disciplina->id }}, '{{ addslashes($disciplina->nome) }}', {{ $disciplina->carga_horaria }}, '{{ $disciplina->professor_id ?? '' }}')" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-blue-500 transition-colors" title="Editar Disciplina">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 mb-4 mt-2">
                            <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-500/20">
                                Disciplina
                            </span>
                            @if($disciplina->status === 'fechado')
                                <span class="text-[10px] font-black text-red-500 bg-red-500/10 px-3 py-1 rounded-full uppercase tracking-widest border border-red-500/20">
                                    Fechada
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-4 group-hover:text-emerald-500 transition-colors">
                            {{ $disciplina->nome }}
                        </h3>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-clock text-emerald-500"></i> {{ $disciplina->carga_horaria }} Horas
                            </p>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-users text-emerald-500"></i> {{ $disciplina->matriculas_count }} Alunos Matriculados
                            </p>
                            @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <i class="fa-solid fa-chalkboard-user text-emerald-500"></i> Prof. {{ $disciplina->professor->name ?? 'Não Atribuído' }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 grid grid-cols-2 gap-3">
                        <a href="{{ route('professor.notas.show', $disciplina->id) }}"
                            class="flex justify-center items-center gap-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-glow active:scale-95">
                            <i class="fa-solid fa-clipboard-list"></i> Diário
                        </a>
                        <a href="{{ route('professor.aulas.index', $disciplina->id) }}"
                            class="flex justify-center items-center gap-2 py-3 px-4 bg-primary hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-glow active:scale-95">
                            <i class="fa-solid fa-calendar-check"></i> Aulas
                        </a>
                        <a href="{{ route('professor.atividades.index', $disciplina->id) }}"
                            class="col-span-2 flex justify-center items-center gap-2 py-3 px-4 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-95">
                            <i class="fa-solid fa-bullhorn"></i> Mural de Avisos
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16 px-4 bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-folder-open text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Nenhuma disciplina cadastrada</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">
                            Não há disciplinas vinculadas ao seu perfil para esta turma no momento.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Nova Disciplina -->
    @if(auth()->user()->hasRole('admin_master') || auth()->user()->hasRole('admin_comum'))
    <div id="modalNovaDisciplina" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex justify-center items-center">
        <div class="bg-white dark:bg-slate-900 w-11/12 md:max-w-lg mx-auto rounded-3xl shadow-2xl p-8 border border-slate-200 dark:border-slate-800 animate-slide-up relative">
            <button onclick="document.getElementById('modalNovaDisciplina').classList.add('hidden')" class="absolute top-4 right-4 w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Nova Disciplina</h3>
            
            <form action="{{ route('professor.disciplinas.storeFast') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nome da Disciplina *</label>
                    <input type="text" name="nome" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Carga Horária (Hs) *</label>
                    <input type="number" name="carga_horaria" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Professor (ID)</label>
                    <input type="text" name="professor_id" placeholder="Opcional" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalNovaDisciplina').classList.add('hidden')" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">Criar Matéria</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Disciplina -->
    <div id="modalEditarDisciplina" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex justify-center items-center">
        <div class="bg-white dark:bg-slate-900 w-11/12 md:max-w-lg mx-auto rounded-3xl shadow-2xl p-8 border border-slate-200 dark:border-slate-800 animate-slide-up relative">
            <button onclick="document.getElementById('modalEditarDisciplina').classList.add('hidden')" class="absolute top-4 right-4 w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Editar Disciplina</h3>
            
            <form id="formEditarDisciplina" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nome da Disciplina *</label>
                    <input type="text" name="nome" id="edit_nome" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Carga Horária (Hs) *</label>
                    <input type="number" name="carga_horaria" id="edit_carga_horaria" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Professor (ID)</label>
                    <input type="text" name="professor_id" id="edit_professor_id" placeholder="Opcional" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalEditarDisciplina').classList.add('hidden')" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-colors">Cancelar</button>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalEdicao(id, nome, cargaHoraria, professorId) {
            document.getElementById('edit_nome').value = nome;
            document.getElementById('edit_carga_horaria').value = cargaHoraria;
            document.getElementById('edit_professor_id').value = professorId;
            
            // Corrige a action do form substituindo a base temporaria
            let baseAction = "{{ route('professor.disciplinas.updateFast', ':id') }}";
            let actionText = baseAction.replace(':id', id);
            document.getElementById('formEditarDisciplina').action = actionText;
            
            document.getElementById('modalEditarDisciplina').classList.remove('hidden');
        }
    </script>
    @endif
@endsection
