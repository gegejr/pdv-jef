<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaFiscalErro extends Model
{
    protected $fillable = ['venda_id', 'detalhes'];

    protected $casts = [
        'detalhes' => 'array',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}
