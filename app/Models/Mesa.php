<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Mesa extends Model
{
    protected $fillable = ['numero', 'reserva_nome', 'status'];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    // apenas a última venda (pedido atual)
    public function ultimaVenda()
    {
        return $this->hasOne(\App\Models\Venda::class)
                    ->where('status', 'ocupada')
                    ->latest(); // ordena pela mais recente
    }
}