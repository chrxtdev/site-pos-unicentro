<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MatriculaDisciplina;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['curso_id', 'professor_id', 'nome', 'carga_horaria', 'status'];

    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class);
    }

    public function professor()
    {
        return $this->belongsTo(\App\Models\User::class, 'professor_id');
    }

    public function matriculas()
    {
        return $this->hasMany(MatriculaDisciplina::class);
    }

    public function atividades()
    {
        return $this->hasMany(\App\Models\Atividade::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }
}
