@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Gestión de Usuarios</h2>
        <span class="badge bg-dark fs-6">Total: {{ $users->total() }}</span>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="fw-bold text-muted"><i class="fas fa-filter me-1"></i> Filtrar:</label>
                </div>
                <div class="col-auto flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre, cédula o correo..." 
                               value="{{ $query }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-dark">Buscar</button>
                    @if($query)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Usuario</th>
                            <th>Identificación / Contacto</th> <th>Rol</th>
                            <th>Registro</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 text-muted small">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                @if($user->cedula)
                                    <span class="d-block fw-bold text-dark">{{ $user->cedula }}</span>
                                @else
                                    <span class="d-block text-muted small">Sin Cédula</span>
                                @endif
                                
                                @if($user->phone)
                                    <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $user->phone }}</small>
                                @endif
                            </td>

                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger"><i class="fas fa-crown me-1"></i> Admin</span>
                                @else
                                    <span class="badge bg-light text-dark border">Cliente</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        @if($user->is_admin)
                                            <button class="btn btn-sm btn-outline-secondary" title="Quitar Admin">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-success" title="Hacer Admin">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('¿Eliminar a {{ $user->name }}?')" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                No se encontraron usuarios.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection