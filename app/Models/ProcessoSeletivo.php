<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessoSeletivo extends Model
{
    protected $fillable = ['nome', 'numero_etapas', 'numero_ofertas', 'situacao'];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class);
    }
}
