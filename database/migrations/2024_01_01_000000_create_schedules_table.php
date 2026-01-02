<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day_name'); // Lunes, Martes...
            $table->integer('day_number'); // 1=Lunes, 7=Domingo (ISO-8601)
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_active')->default(true); // Si trabaja ese día
            $table->timestamps();
        });

        // Insertar días por defecto
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        foreach ($days as $index => $day) {
            DB::table('schedules')->insert([
                'day_name' => $day,
                'day_number' => $index + 1,
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'is_active' => true
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};