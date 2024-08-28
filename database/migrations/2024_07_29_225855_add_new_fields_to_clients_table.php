<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('executive')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('billing_closure')->nullable();
            $table->text('commercial_conditions')->nullable();
            $table->boolean('proforma_invoice')->default(false);
            $table->enum('payment_method', [1, 2])->default(1);
            $table->integer('payment_day')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'executive', 'address', 'city', 'billing_closure', 
                'commercial_conditions', 'proforma_invoice', 
                'payment_method', 'payment_day', 'status'
            ]);
        });
    }
}
