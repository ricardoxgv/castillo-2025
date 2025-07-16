<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['entrada', 'servicio', 'cochera']);
            $table->decimal('costo', 8, 2);
            $table->unsignedBigInteger('user_id'); // creador del ítem
            $table->boolean('estado')->default(true); // activo por defecto
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
