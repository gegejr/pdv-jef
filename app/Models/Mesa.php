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
    public function ultimaVenda()   // mantenha o nome igual ao que usar no Livewire
    {
        return $this->hasOne(\App\Models\Venda::class)->latestOfMany();
    }
}