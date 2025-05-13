<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoPerda extends Model
{
    protected $fillable = [
        'produto_id', 'quantidade', 'valor', 'motivo'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}