<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ej: 'timezone'
            $table->text('value')->nullable(); // Ej: 'Europe/Madrid'
            $table->timestamps();
        });

        // Insertar configuración por defecto
        DB::table('settings')->insert([
            'key' => 'timezone',
            'value' => 'America/Caracas'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
