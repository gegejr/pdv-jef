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
        Schema::table('mesas', function (Blueprint $table) {
            $table->string('status')->default('livre')->after('numero');
            // Ou se preferir ENUM:
            // $table->enum('status', ['livre', 'ocupada', 'finalizada'])->default('livre')->after('numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            //
        });
    }
};
