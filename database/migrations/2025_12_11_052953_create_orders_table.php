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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // <--- ESTA ES LA CLAVE
        $table->string('status')->default('pendiente');
        $table->string('payment_method')->default('efectivo');
        $table->decimal('total', 10, 2);
        $table->string('contact_name');
        $table->string('contact_phone');
        $table->text('address');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
