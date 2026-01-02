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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // ESTAS SON LAS COLUMNAS QUE FALTABAN:
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Conecta con la orden
            $table->foreignId('product_id')->constrained(); // Conecta con el producto
            $table->string('product_name'); // Guardamos el nombre por si luego cambia
            $table->integer('quantity');    // Cantidad
            $table->decimal('price', 10, 2); // Precio al momento de comprar
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};