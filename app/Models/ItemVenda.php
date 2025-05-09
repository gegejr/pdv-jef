<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
        'desconto',
    ];

    // Relacionamento com a venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    
}
