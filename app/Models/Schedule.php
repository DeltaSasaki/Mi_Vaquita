<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Setting; // <--- Importamos el modelo de configuración

class Schedule extends Model
{
    protected $fillable = ['day_name', 'day_number', 'open_time', 'close_time', 'is_active'];

    /**
     * Verifica si la tienda está abierta en este momento.
     */
    public static function isOpen()
    {
        // Obtenemos la zona horaria de la BD, si no existe usamos Caracas por defecto
        $timezone = Setting::where('key', 'timezone')->value('value') ?? 'America/Caracas';
        $now = Carbon::now($timezone);
        $dayOfWeek = $now->dayOfWeekIso; // 1 (Lunes) a 7 (Domingo)
        $currentTime = $now->format('H:i:s');

        $schedule = self::where('day_number', $dayOfWeek)->first();

        if (!$schedule || !$schedule->is_active) {
            return false;
        }

        return $currentTime >= $schedule->open_time && $currentTime <= $schedule->close_time;
    }

    /**
     * Obtiene el mensaje de cuándo abre.
     */
    public static function getNextOpenMessage()
    {
        $timezone = Setting::where('key', 'timezone')->value('value') ?? 'America/Caracas';
        $now = Carbon::now($timezone);
        $dayOfWeek = $now->dayOfWeekIso;
        $currentTime = $now->format('H:i:s');
        
        // 1. Verificar si abre HOY más tarde (ej: es temprano en la mañana)
        $today = self::where('day_number', $dayOfWeek)->first();

        if ($today && $today->is_active && $currentTime < $today->open_time) {
            return "Abrimos hoy a las " . Carbon::parse($today->open_time)->format('h:i A');
        }

        // 2. Buscar el PRÓXIMO día abierto (Mañana, Pasado, etc.)
        for ($i = 1; $i <= 7; $i++) {
            $nextDayNum = $dayOfWeek + $i;
            if ($nextDayNum > 7) $nextDayNum -= 7; // Si pasa de Domingo (7), vuelve a Lunes (1)

            $nextSchedule = self::where('day_number', $nextDayNum)->where('is_active', true)->first();

            if ($nextSchedule) {
                $hora = Carbon::parse($nextSchedule->open_time)->format('h:i A');
                return $i === 1 ? "Abrimos mañana a las $hora" : "Abrimos el " . $nextSchedule->day_name . " a las $hora";
            }
        }

        return "Cerrado temporalmente.";
    }
}