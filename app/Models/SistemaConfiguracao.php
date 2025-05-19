<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaConfiguracao extends Model
{
    protected $table = 'sistema_configuracoes'; // nome correto da tabela
    protected $fillable = ['chave', 'categoria', 'valor'];
}