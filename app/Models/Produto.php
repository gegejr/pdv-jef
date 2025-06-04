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
        'ncm',
        'valor',
        'preco_custo',
        'estoque',
        'unidade_medida',
        'imagem',
        'desconto_padrao',
        'categoria_id',
        'status',
        'tamanho',
        'cor',
        'genero',
        'marca',
        'material',
        'modelo',
        'colecao',

        // Campos fiscais
        'cst_icms',
        'icms_rate',
        'cst_ipi',
        'ipi_rate',
        'cst_pis',
        'pis_rate',
        'cst_cofins',
        'cofins_rate',
    ];

    /**
     * Relacionamento: produto pertence a uma categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function vendasNoMes()
    {
        return $this->hasMany(\App\Models\ItemVenda::class)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantidade'); // Ou use ->count() se quiser contar transações
    }
}