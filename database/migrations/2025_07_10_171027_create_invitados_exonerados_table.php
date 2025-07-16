<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitadosExoneradosTable extends Migration
{
    public function up()
    {
        Schema::create('invitados_exonerados', function (Blueprint $table) {
            $table->id();  // Identificador único
            $table->foreignId('carrito_id')->constrained('carritos');  // Relación con la tabla de carritos (suponiendo que la tabla de carritos existe)
            $table->string('documento');  // Número de documento
            $table->string('nombres');  // Nombres completos
            $table->string('imagen_documento');  // Imagen del documento
            $table->string('persona_autoriza');  // Persona que autoriza
            $table->string('numero_autoriza');  // Número de la persona que autoriza
            $table->dateTime('fecha_ingreso');  // Cambiado a DATETIME
            $table->timestamps();  // Timestamps de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitados_exonerados');
    }
}
