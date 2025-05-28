<?php

// app/Models/NotaFiscal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    protected $fillable = [
        'venda_id',
        'nfe_io_id',   // se você criou essa coluna
        'chave',
        'link_pdf',
        'status',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}
