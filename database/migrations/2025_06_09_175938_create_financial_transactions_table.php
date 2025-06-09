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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('tipo', ['receber', 'pagar']);
            $table->decimal('valor', 10,2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->string('categoria')->nullable(); //alugel, agua, luz etc
            $table->boolean('pago')->default(false);
            $table->foreignId('cliente_id')->nullable()->constrained(); //se for uma venda
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
