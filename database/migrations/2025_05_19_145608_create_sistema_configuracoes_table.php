<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSistemaConfiguracoesTable extends Migration
{
    public function up()
    {
        Schema::create('sistema_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave');
            $table->string('categoria');
            $table->boolean('ativo')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sistema_configuracoes');
    }
}