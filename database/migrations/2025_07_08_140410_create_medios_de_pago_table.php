<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediosDePagoTable extends Migration
{
    public function up()
    {
        Schema::create('medios_de_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // efectivo, tarjeta, yape, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medios_de_pago');
    }
}
