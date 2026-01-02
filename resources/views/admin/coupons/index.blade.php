@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Cupones</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-2"></i>Nuevo Cupón
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Código</th>
                        <th>Descuento</th>
                        <th>Restricción</th>
                        <th>Estado</th>
                        <th>Vencimiento</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td class="ps-4 fw-bold text-danger">{{ $coupon->code }}</td>
                        <td>
                            @if($coupon->type == 'percent')
                                <span class="badge bg-info text-dark">{{ floatval($coupon->value) }}% OFF</span>
                            @else
                                <span class="badge bg-success">${{ floatval($coupon->value) }} OFF</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                @if($coupon->min_purchase) Min: ${{ $coupon->min_purchase }} <br> @endif
                                @if($coupon->category_id) Cat: {{ $coupon->category_id }} <br> @endif
                                @if(!$coupon->min_purchase && !$coupon->category_id) Global @endif
                            </small>
                        </td>
                        <td>
                            @if($coupon->isValid())
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Sin fecha' }}
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este cupón?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection