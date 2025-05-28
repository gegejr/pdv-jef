<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
            'nome',
            'cpf_cnpj',
            'tipo_pessoa',
            'razao_social',
            'nome_fantasia',
            'atividade_economica',
            'cnae_id',
            'data_nascimento',
            'email',
            'telefone',
            'cep',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento',
            'ie',
            'im',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
    
    public function cnae()
    {
        return $this->belongsTo(Cnae::class, 'cnae_id');
    }
}


