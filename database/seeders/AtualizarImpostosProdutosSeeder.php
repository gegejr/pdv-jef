<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtualizarImpostosProdutosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produtos')->update([
            'cst_icms'     => '00',
            'icms_rate'    => 0,
            'cst_ipi'      => '99',
            'ipi_rate'     => 0,
            'cst_pis'      => '99',
            'pis_rate'     => 0,
            'cst_cofins'   => '99',
            'cofins_rate'  => 0,
        ]);

        $this->command->info('Campos de impostos atualizados com valores padrão para todos os produtos.');
    }
}