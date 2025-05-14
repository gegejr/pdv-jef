<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPagoToPagamentosTable extends Migration
{
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->boolean('pago')->default(false)->after('tipo');
        });
    }

    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('pago');
        });
    }
}

