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
        Schema::table('clientes', function (Blueprint $table) {
            $table->enum('tipo_pessoa', ['fisica', 'juridica'])->default('fisica');
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('email')->nullable();
            $table->string('cep')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
           // $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('complemento')->nullable();
            $table->string('ie')->nullable();
            $table->string('im')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropcolumn([
                'tipo_pessoa',
                'razao_social',
                'nome_fantasia',
                'email',
                'cep',
                'numero',
                'bairro',
                //'cidade',
                'uf',
                'complemento',
                'ie',
                'im',
            ]);
        });
    }
};
