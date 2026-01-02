@extends('layouts.front')

@section('title', 'Mis Pedidos | Mi Vaquita')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Mis <span class="text-danger">Compras</span></h2>

    @if($orders->isEmpty())
        <div class="alert alert-light text-center py-5 shadow-sm">
            <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
            <h4>Aún no has realizado compras.</h4>
            <a href="{{ route('home') }}" class="btn btn-cta mt-3">Ir al Catálogo</a>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#Pedido</th>
                                <th>Fecha</th>
                                <th>Método</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="text-uppercase small">{{ str_replace('_', ' ', $order->payment_method) }}</td>
                                    <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->status == 'pendiente')
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        @elseif($order->status == 'completado')
                                            <span class="badge bg-success">Recibido</span>
                                        @else
                                            <span class="badge bg-danger">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">
                                            Ver Detalle
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
    @endif
</div>
@endsection