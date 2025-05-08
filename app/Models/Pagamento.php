<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    // Definir a tabela, caso o nome não seja automaticamente pluralizado
    // protected $table = 'pagamentos';

    // Definir quais campos podem ser preenchidos via mass assignment
    protected $fillable = [
        'venda_id',  // Relacionamento com a venda
        'tipo',      // Tipo do pagamento (dinheiro, débito, crédito, pix)
        'valor',     // Valor do pagamento
    ];

    // Se você usar relacionamento Eloquent, você pode adicionar a função para o relacionamento com Venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}
