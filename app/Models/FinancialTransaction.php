<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'descricao',
        'tipo',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'categoria',
        'pago',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'pago' => 'boolean',
        'valor' => 'decimal:2',
    ];
}
