<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssinaturasTable extends Migration
{
    public function up()
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id'); // Relaciona ao cliente
            $table->date('data_vencimento');
            $table->enum('status', ['pendente', 'pago', 'atrasado'])->default('pendente');
            $table->timestamps();

            // Chave estrangeira para integridade referencial
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assinaturas');
    }
}
