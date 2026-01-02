@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Administrar Pedidos</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#Orden</th>
                            <th>Cliente</th>
                            <th>Pago / Envío</th> {{-- Nueva Columna Combinada --}}
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                            <td>
                                {{ $order->user->name }}<br>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                {{-- Método de Pago --}}
                                <div class="small mb-1">
                                    <i class="fas fa-money-bill-wave text-secondary me-1"></i>
                                    {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                                </div>
                                {{-- Tipo de Envío --}}
                                <div class="small">
                                    @if($order->shipping_type == 'delivery')
                                        <span class="badge bg-info text-dark"><i class="fas fa-motorcycle me-1"></i>Delivery</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-store me-1"></i>Retiro</span>
                                    @endif
                                </div>
                            </td>
                            <td class="fw-bold text-success">${{ number_format($order->total, 2) }}</td>
                            <td>
                                @if($order->status == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($order->status == 'completado')
                                    <span class="badge bg-success">Completado</span>
                                @else
                                    <span class="badge bg-danger">Cancelado</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection