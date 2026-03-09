<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaDisciplina extends Model
{
    use HasFactory;

    protected $fillable = ['signin_id', 'disciplina_id', 'status'];

    public function aluno()
    {
        return $this->belongsTo(\App\Models\Signin::class, 'signin_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function notas()
    {
        return $this->hasOne(Nota::class);
    }
}
