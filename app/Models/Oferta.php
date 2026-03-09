<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $fillable = [
        'processo_seletivo_id',
        'curso_id',
        'turno',
        'quantidade_vagas',
        'locais_prova',
        'valor_taxa',
        'data_vencimento_taxa',
        'conta_recebimento',
    ];
    
    // Relacionamento com processo seletivo
    public function processoSeletivo()
    {
        return $this->belongsTo(ProcessoSeletivo::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
