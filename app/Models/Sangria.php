<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sangria extends Model
{
    protected $fillable = [
        'caixa_id',
        'valor',
        'observacao',
    ];

    // Relacionamento: Cada sangria pertence a um caixa
    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }
    
    public function registrarSangria($caixa_id, $valor, $observacao)
    {
        // Criando uma sangria para o caixa
        $sangria = Sangria::create([
            'caixa_id' => $caixa_id,
            'valor' => $valor,
            'observacao' => $observacao,
        ]);
    
        // Subtraindo o valor da sangria do caixa
        $caixa = Caixa::find($caixa_id);
        if ($caixa) {
            $caixa->valor_inicial -= $valor;  // Atualize o valor do caixa
            $caixa->save();
        }
    
        return $sangria;
    }
    
}
