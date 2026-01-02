<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Res, Cerdo
            $table->string('slug')->unique(); // Ej: res, cerdo (para rutas amigables)
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Guardaremos la URL o path de la imagen
            $table->boolean('is_active')->default(true); // Para ocultar categorías sin borrar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};