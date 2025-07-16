<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aperturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // Usuario que aperturó
            $table->enum('tipo', ['taquilla', 'cochera']); // Tipo de apertura
            $table->timestamp('apertura_at')->useCurrent();
            $table->timestamp('cierre_at')->nullable();     // Cuando se cierre
            $table->boolean('cerrada')->default(false);     // Cerrada o no
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'tipo', 'apertura_at']); // Evita duplicados diarios
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aperturas');
    }
};
