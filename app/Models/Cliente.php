<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'data_nascimento',
        'endereco',
        'telefone',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];
}