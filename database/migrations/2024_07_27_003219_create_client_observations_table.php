<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientObservationsTable extends Migration
{
    public function up()
    {
        Schema::create('client_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->boolean('requires_physical_invoice')->default(false);
            $table->integer('packaging_unit')->nullable();
            $table->boolean('requires_appointment')->default(false);
            $table->boolean('is_in_free_zone')->default(false);
            $table->string('billing_closure_date')->nullable();
            $table->integer('reteica')->default(0);
            $table->integer('retefuente')->default(0);
            $table->integer('reteiva')->default(0);
            $table->text('additional_observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_observations');
    }
}
