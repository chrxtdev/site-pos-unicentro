<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class Nota extends Model
{
    use HasFactory; // Added this line

    protected $fillable = [
        'matricula_disciplina_id',
        'b1_t1', 'b1_t2', 'b1_t3', 'b1_aval', 'b1_total',
        'b2_t1', 'b2_t2', 'b2_t3', 'b2_aval', 'b2_total',
        'media_final'
    ]; // Added this block

    public function matricula()
    {
        return $this->belongsTo(MatriculaDisciplina::class, 'matricula_disciplina_id');
    } // Added this method
}
