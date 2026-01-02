<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // El código que escribe el usuario (ej: PARRILLA2025)
            $table->enum('type', ['fixed', 'percent']); // Tipo: Monto fijo ($) o Porcentaje (%)
            $table->decimal('value', 10, 2); // Cuánto descuenta
            
            // Restricciones Creativas
            $table->decimal('min_purchase', 10, 2)->nullable(); // Mínimo de compra para que funcione
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // Solo aplica a esta categoría
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete(); // Solo aplica a este producto
            
            $table->timestamp('expires_at')->nullable(); // Fecha de vencimiento
            $table->boolean('is_active')->default(true); // Interruptor general
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
