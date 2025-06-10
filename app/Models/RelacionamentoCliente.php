<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelacionamentoCliente extends Model
{
    protected $fillable = [
            'cliente_id',
            'tem_whatsapp',
            'ultima_interacao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
