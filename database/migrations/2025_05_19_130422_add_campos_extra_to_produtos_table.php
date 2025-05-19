<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->after('id');
            $table->string('sku')->nullable()->after('codigo_barras');
            $table->string('unidade_medida', 10)->nullable()->after('estoque');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo')->after('descricao');


            // Chave estrangeira, se você tiver tabela de categorias
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn(['categoria_id', 'sku', 'unidade_medida', 'status', 'desconto_padrao']);
        });
    }

};
