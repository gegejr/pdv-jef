<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoPerdasTable extends Migration
{
    public function up()
    {
        Schema::create('produto_perdas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->decimal('valor', 10, 2);
            $table->enum('motivo', ['quebra', 'descarte', 'perda']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produto_perdas');
    }
}