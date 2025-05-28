<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cnae extends Model
{
    protected $fillable = [
        'codigo',
        'descricao',
    ];


    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}