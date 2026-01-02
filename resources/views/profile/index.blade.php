@extends('layouts.front')

@section('title', 'Mi Perfil | Mi Vaquita')

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="m-0">Hola, <span class="text-danger">{{ $user->name }}</span></h2>
        @if($user->is_admin)
            <span class="badge bg-danger ms-3">ADMINISTRADOR</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error) 
                    <li>{{ $error }}</li> 
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="m-0"><i class="fas fa-address-card me-2 text-danger"></i>Mis Datos Personales</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nombre y Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Cédula de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="cedula" class="form-control" placeholder="Ej: V-12345678" value="{{ old('cedula', $user->cedula) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Teléfono / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="Ej: 0412-1234567" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Dirección de Entrega Predeterminada</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="address" class="form-control" rows="3" placeholder="Estado, Ciudad, Calle, Casa #...">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div class="form-text text-muted small ms-1">Esta dirección aparecerá automáticamente al comprar.</div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">
                            <i class="fas fa-save me-2"></i>GUARDAR CAMBIOS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="m-0"><i class="fas fa-lock me-2 text-danger"></i>Seguridad</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-2">
                            <div class="col-12">
                                <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Contraseña Actual" required>
                            </div>
                            <div class="col-6">
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Nueva Contraseña" required>
                            </div>
                            <div class="col-6">
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirmar Nueva" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 mt-3">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="m-0"><i class="fas fa-history me-2"></i>Mis Pedidos Recientes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#ID</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th class="text-end pe-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-3 fw-bold">#{{ $order->id }}</td>
                                        <td class="small">{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                        <td class="text-end pe-3">
                                            @if($order->status == 'pendiente')
                                                <span class="badge bg-warning text-dark rounded-pill">Pendiente</span>
                                            @elseif($order->status == 'completado')
                                                <span class="badge bg-success rounded-pill">Recibido</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Cancelado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-shopping-basket fa-2x mb-3 text-secondary"></i><br>
                                            Aún no has realizado compras.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection