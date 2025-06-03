<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('tamanho')->nullable();
            $table->string('cor')->nullable();
            $table->enum('genero', ['masculino', 'feminino', 'unissex'])->default('unissex');
            $table->string('marca')->nullable();
            $table->string('material')->nullable();
            $table->string('modelo')->nullable(); // código do fabricante
            $table->string('colecao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            //
        });
    }
};
