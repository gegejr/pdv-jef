<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'codigo_barras',
        'descricao',
        'valor',
        'estoque',
        'imagem',
        'desconto_padrao',
    ];
}
