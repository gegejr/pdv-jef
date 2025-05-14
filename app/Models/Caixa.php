<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Caixa extends Model
{
    protected $fillable = [
        'user_id',
        'nome',
        'valor_inicial',
        'valor_final',
        'aberto_em',
        'fechado_em',
    ];

    // Define que os campos de data são do tipo Carbon
    protected $dates = ['aberto_em', 'fechado_em'];

    // Relacionamento: Cada caixa pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento: Cada caixa pode ter muitas vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    // Método para abrir o caixa
    public function abrirCaixa($valor_inicial, $user_id)
    {
        $caixa = Caixa::create([
            'user_id' => $user_id,
            'valor_inicial' => $valor_inicial,
            'aberto_em' => now(),
        ]);

        return $caixa;
    }

    // Método para fechar o caixa
    public function fecharCaixa($caixa_id)
    {
        $caixa = Caixa::find($caixa_id);

        if ($caixa) {
            $caixa->valor_final = $this->calcularValorFinal($caixa);
            $caixa->fechado_em = now();
            $caixa->save();
        }
    }

    // Método para calcular o valor final do caixa
    public function calcularValorFinal()
    {
        $total_dinheiro = 0;

        foreach ($this->vendas as $venda) {
            $total_dinheiro += $venda->pagamentos()->where('tipo', 'dinheiro')->sum('valor');
        }

        return $this->valor_inicial + $total_dinheiro;
    }

        public function getAbertoEmAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    public function getFechadoEmAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    public function sangrias()
    {
    return $this->hasMany(Sangria::class);
    }
}
