<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = ['nome', 'sigla', 'tipo'];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class);
    }
}
