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

    protected $casts = [
        'valor_taxa' => 'decimal:2',
        'data_vencimento_taxa' => 'date',
    ];

    public function processoSeletivo()
    {
        return $this->belongsTo(ProcessoSeletivo::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
