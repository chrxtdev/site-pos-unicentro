<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class AulaAluno extends Model
{
    use HasFactory; // Added this line

    protected $fillable = ['aula_id', 'signin_id', 'presente']; // Added this line

    public function aula() // Added this method
    {
        return $this->belongsTo(Aula::class);
    }

    public function aluno() // Added this method
    {
        return $this->belongsTo(Signin::class, 'signin_id');
    }
}
