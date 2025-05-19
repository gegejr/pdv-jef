<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SistemaConfiguracao;

class SistemaConfiguracoesSeeder extends Seeder
{
    public function run()
    {
        $configuracoes = [
            ['chave' => 'imprimir_pedido', 'categoria' => 'restaurante'],
            ['chave' => 'adicionar_mesa', 'categoria' => 'restaurante'],
            ['chave' => 'registrar_mesa', 'categoria' => 'restaurante'],
            ['chave' => 'listar_mesa', 'categoria' => 'restaurante'],
            ['chave' => 'tamanho', 'categoria' => 'loja_roupa'],
            ['chave' => 'cor', 'categoria' => 'loja_roupa'],
            ['chave' => 'marca', 'categoria' => 'loja_roupa'],
            ['chave' => 'categoria', 'categoria' => 'loja_roupa'],
        ];

        foreach ($configuracoes as $config) {
            SistemaConfiguracao::create($config);
        }
    }
}
