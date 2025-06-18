<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'duracao',
        'ativo',
        'user_id', // <- aqui sim, depois que a coluna existir
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}