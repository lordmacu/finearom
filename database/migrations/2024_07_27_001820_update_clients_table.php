<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Eliminar columnas
            $table->dropColumn('tax_id');
            $table->dropColumn('email');
            $table->dropColumn('client_type');

            // Agregar nuevas columnas
            $table->string('client_name')->after('id');
            $table->string('nit')->unique()->after('client_name');
            $table->string('client_type')->unique()->after('nit');
            $table->enum('client_type', ['pareto', 'balance'])->after('nit');
            $table->enum('payment_type', ['cash', 'credit'])->after('client_type');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Revertir cambios
            $table->string('tax_id')->unique()->after('email');
            $table->string('email')->unique()->after('client_name');

            $table->dropColumn('client_name');
            $table->dropColumn('nit');
            $table->dropColumn('client_type');
            $table->dropColumn('payment_type');
        });
    }
}
