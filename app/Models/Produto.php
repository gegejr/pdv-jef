<?php

// ============================
// App\Models\Produto (atualizado)
// ============================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome',
        'codigo_barras',
        'sku',
        'descricao',
        'valor',
        'estoque',
        'unidade_medida',
        'imagem',
        'desconto_padrao',
        'categoria_id',
        'status',
    ];

    /**
     * Relacionamento: produto pertence a uma categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}