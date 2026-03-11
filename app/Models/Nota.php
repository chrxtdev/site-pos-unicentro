<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_disciplina_id',
        'b1_t1', 'b1_t2', 'b1_t3', 'b1_aval', 'b1_total',
        'b2_t1', 'b2_t2', 'b2_t3', 'b2_aval', 'b2_total',
        'media_final'
    ];

    protected $casts = [
        'b1_t1' => 'float', 'b1_t2' => 'float', 'b1_t3' => 'float', 'b1_aval' => 'float', 'b1_total' => 'float',
        'b2_t1' => 'float', 'b2_t2' => 'float', 'b2_t3' => 'float', 'b2_aval' => 'float', 'b2_total' => 'float',
        'media_final' => 'float',
    ];

    public function matricula()
    {
        return $this->belongsTo(MatriculaDisciplina::class, 'matricula_disciplina_id');
    }
}
