@extends('layouts.front')

@section('title', 'Pedido #' . $order->id)

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Pedido <span class="text-danger">#{{ $order->id }}</span></h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Fecha del pedido: {{ $order->created_at->format('d/m/Y h:i A') }}</p>
                    <p class="mb-0">
                        Estado: 
                        @if($order->status == 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente de Despacho</span>
                        @elseif($order->status == 'completado')
                            <span class="badge bg-success">Entregado / Completado</span>
                        @else
                            <span class="badge bg-danger">Cancelado</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <h3 class="mb-0 fw-bold text-success">${{ number_format($order->total, 2) }}</h3>
                    <small class="text-uppercase text-muted">{{ str_replace('_', ' ', $order->payment_method) }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">Productos Comprados</div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th>Precio</th>
                                <th>Cant.</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">{{ $item->product_name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold py-3">Datos de Entrega</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Recibe:</strong> {{ $order->contact_name }}</p>
                    <p class="mb-2"><strong>Teléfono:</strong> {{ $order->contact_phone }}</p>
                    <hr>
                    <p class="mb-1 fw-bold text-muted small">DIRECCIÓN:</p>
                    <div class="alert alert-light border">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $order->address }}
                    </div>
                </div>
            </div>

            @if($order->payment_reference)
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center">
                        <small class="text-muted">Referencia Bancaria:</small>
                        <div class="fw-bold fs-5">{{ $order->payment_reference }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection