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
        Schema::table('users', function (Blueprint $table) {
            $table->date('subscription_due_date')->nullable()->after('email'); // ou depois do campo que quiser
            $table->boolean('is_subscription_active')->default(true)->after('subscription_due_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subscription_due_date');
            $table->dropColumn('is_subscription_active');
        });
    }

};
