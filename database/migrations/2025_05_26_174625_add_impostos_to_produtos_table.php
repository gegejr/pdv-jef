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
            $table->string('cst_icms')->nullable();
            $table->decimal('icms_rate', 5, 2)->nullable();
            $table->string('cst_ipi')->nullable();
            $table->decimal('ipi_rate', 5, 2)->nullable();
            $table->string('cst_pis')->nullable();
            $table->decimal('pis_rate', 5, 2)->nullable();
            $table->string('cst_cofins')->nullable();
            $table->decimal('cofins_rate', 5, 2)->nullable();
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
