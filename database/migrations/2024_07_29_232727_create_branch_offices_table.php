<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchOfficesTable extends Migration
{
    public function up()
    {
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nit');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('branch');
            $table->string('contact');
            $table->string('contact_portfolio');
            $table->string('delivery_address');
            $table->string('delivery_city');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('phone');
            $table->text('shipping_observations')->nullable();
            $table->text('general_observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_offices');
    }
}
