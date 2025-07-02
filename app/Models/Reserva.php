<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{

    protected $fillable = [
            'cliente_id',
            'servico',
            'servico_id',
            'data',
            'hora_inicio',
            'hora_fim',
            'status',
            'observacoes',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
