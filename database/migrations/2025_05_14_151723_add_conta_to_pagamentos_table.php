<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Altere o tipo do enum para incluir 'conta'
        DB::statement("ALTER TABLE pagamentos MODIFY COLUMN tipo ENUM('dinheiro', 'debito', 'credito', 'pix', 'conta') NOT NULL");
    }

    public function down(): void
    {
        // Reverte para o enum original
        DB::statement("ALTER TABLE pagamentos MODIFY COLUMN tipo ENUM('dinheiro', 'debito', 'credito', 'pix') NOT NULL");
    }
};
