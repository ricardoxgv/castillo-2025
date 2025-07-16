<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivoToEmpresasAndMediosPagoTables extends Migration
{
    public function up()
    {
        // Agregar columna 'activo' a la tabla empresas
        Schema::table('empresas', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('representante_legal');
        });

        // Agregar columna 'activo' a la tabla medios_de_pago
        Schema::table('medios_de_pago', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('nombre');
        });
    }

    public function down()
    {
        // Eliminar columna 'activo' de empresas
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        // Eliminar columna 'activo' de medios_de_pago
        Schema::table('medios_de_pago', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
}
