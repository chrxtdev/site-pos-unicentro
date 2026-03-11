<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class Aula extends Model
{
    use HasFactory; // Added this line

    protected $fillable = ['disciplina_id', 'data', 'hora', 'conteudo', 'qtd_aulas']; // Added this line

    public function disciplina() // Added this method
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function presencas() // Added this method
    {
        return $this->hasMany(AulaAluno::class);
    }
}
