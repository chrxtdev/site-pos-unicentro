<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'disciplina_id', 'professor_id', 'titulo', 'descricao', 
        'arquivo_path', 'link_externo', 'data_limite'
    ];

    protected $casts = [
        'data_limite' => 'datetime',
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
