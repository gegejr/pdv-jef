<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportCnaes extends Command
{
    protected $signature = 'import:cnaes';
    protected $description = 'Importa os códigos CNAE a partir de um CSV';

    public function handle()
    {
        $file = storage_path('app/private/cnaes.csv');
        $handle = fopen($file, 'r');

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            // Se a linha tiver pelo menos 6 colunas
            if (isset($row[4], $row[5])) {
                $codigo = trim($row[4]);
                $descricao = trim($row[5]);

                // Verifica se é uma subclasse válida (formato CNAE tipo 9999-9/99)
                if (preg_match('/^\d{4}-\d\/\d{2}$/', $codigo)) {
                    DB::table('cnaes')->updateOrInsert(
                        ['codigo' => $codigo],
                        ['descricao' => $descricao, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }

        fclose($handle);
        $this->info('CNAEs importados com sucesso.');
    }

}

