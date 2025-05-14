<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'desconto_total',
        'caixa_id',
        'data_venda',
        'cliente_id', // 
    ];

    // Relacionamento com o usuário (quem fez a venda)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o caixa
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    // Relacionamento com os itens da venda
    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }

    // Relacionamento com os pagamentos
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

       public function itemVendas()
    {
        return $this->hasMany(ItemVenda::class);
    }

        public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

}
