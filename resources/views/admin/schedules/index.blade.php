@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Gestión de Horarios</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.schedules.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- SECCIÓN DE ZONA HORARIA -->
                <div class="mb-4 p-3 bg-light rounded border">
                    <label for="timezone" class="form-label fw-bold"><i class="fas fa-globe-americas me-2"></i>Zona Horaria de la Tienda</label>
                    <div class="input-group">
                        <select name="timezone" id="timezone" class="form-select">
                            @foreach($timezones as $tz)
                                <option value="{{ $tz }}" {{ $tz == $currentTimezone ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-secondary" type="button" disabled>
                            Hora actual: {{ now($currentTimezone)->format('h:i A') }}
                        </button>
                    </div>
                    <small class="text-muted">Selecciona la ubicación geográfica de tu negocio para que los horarios de apertura y cierre funcionen correctamente.</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Día</th>
                                <th>Estado</th>
                                <th>Apertura</th>
                                <th>Cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr class="{{ !$schedule->is_active ? 'table-secondary text-muted' : '' }}">
                                <td class="fw-bold">{{ $schedule->day_name }}</td>
                                
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               name="schedules[{{ $schedule->id }}][is_active]" 
                                               value="1" 
                                               {{ $schedule->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">Abierto</label>
                                    </div>
                                </td>

                                <td>
                                    <input type="time" class="form-control" 
                                           name="schedules[{{ $schedule->id }}][open_time]" 
                                           value="{{ $schedule->open_time }}">
                                </td>

                                <td>
                                    <input type="time" class="form-control" 
                                           name="schedules[{{ $schedule->id }}][close_time]" 
                                           value="{{ $schedule->close_time }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save me-2"></i>Guardar Horarios</button>
            </form>
        </div>
    </div>
</div>
@endsection