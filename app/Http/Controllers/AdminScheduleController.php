<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Setting; // <--- Importante

class AdminScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('day_number')->get();
        
        // Obtener configuración actual y lista de zonas horarias del sistema
        $currentTimezone = Setting::where('key', 'timezone')->value('value') ?? 'America/Caracas';
        $timezones = \DateTimeZone::listIdentifiers(); // Lista oficial de PHP (ej: Europe/Madrid, America/Bogota)

        return view('admin.schedules.index', compact('schedules', 'currentTimezone', 'timezones'));
    }

    public function update(Request $request)
    {
        // 1. Guardar la Zona Horaria seleccionada
        if ($request->has('timezone')) {
            Setting::updateOrCreate(['key' => 'timezone'], ['value' => $request->timezone]);
        }

        // 2. Guardar los Horarios
        $data = $request->schedules; // Array de horarios

        foreach ($data as $id => $values) {
            $schedule = Schedule::findOrFail($id);
            $schedule->open_time = $values['open_time'];
            $schedule->close_time = $values['close_time'];
            $schedule->is_active = isset($values['is_active']) ? 1 : 0;
            $schedule->save();
        }

        return back()->with('success', 'Horario actualizado correctamente.');
    }
}