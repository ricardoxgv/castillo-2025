<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToEmpresasAndMediosPagoTables extends Migration
{
    public function up()
    {
        // A empresas
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('activo');
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });

        // A medios_de_pago
        Schema::table('medios_de_pago', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('activo');
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::table('medios_de_pago', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
}
