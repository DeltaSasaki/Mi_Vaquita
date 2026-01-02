@extends('admin.layout')

@section('content')
{{-- Estilos para el mapa (Solo si el pedido tiene coordenadas) --}}
@if($order->latitude && $order->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style> #admin-map { height: 250px; width: 100%; border-radius: 8px; z-index: 1; } </style>
@endif

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle del Pedido #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-box-open me-2 text-secondary"></i>Productos Solicitados
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th>Precio Unit.</th>
                                <th>Cantidad</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">{{ $item->product_name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end pe-4 fw-bold text-dark">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end pt-3">Subtotal:</td>
                                <td class="text-end pe-4 pt-3 fw-bold">${{ number_format($order->total + $order->discount_amount, 2) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr class="text-success">
                                <td colspan="3" class="text-end border-0">Cupón ({{ $order->coupon_code }}):</td>
                                <td class="text-end pe-4 border-0 fw-bold">-${{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end fw-bold pt-3 fs-5 text-uppercase">Total Cobrado:</td>
                                <td class="text-end pe-4 fw-bold text-danger fs-4 pt-3">
                                    ${{ number_format($order->total, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($order->shipping_type == 'delivery' && $order->latitude)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-map-marked-alt me-2 text-danger"></i>Ubicación de Entrega
                </div>
                <div class="card-body p-2">
                    <div id="admin-map"></div>
                    <div class="mt-2 text-center">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Abrir en Google Maps
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            
            <div class="card shadow-sm border-0 border-top border-4 border-primary mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-tasks me-2 text-primary"></i>Gestionar Estado
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf @method('PUT')

                        <label class="form-label small text-muted fw-bold">Estado Actual:</label>
                        <select name="status" class="form-select mb-3">
                            <option value="pendiente" {{ $order->status == 'pendiente' ? 'selected' : '' }}>🟡 Pendiente</option>
                            <option value="completado" {{ $order->status == 'completado' ? 'selected' : '' }}>🟢 Completado</option>
                            <option value="cancelado" {{ $order->status == 'cancelado' ? 'selected' : '' }}>🔴 Cancelado</option>
                        </select>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fas fa-save me-2"></i>ACTUALIZAR
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-money-check-alt me-2 text-success"></i>Detalles del Pago
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Método:</strong> 
                        <span class="badge bg-dark text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </p>
                    
                    @if($order->payment_reference)
                        <div class="alert alert-warning py-2 mb-0 mt-3">
                            <small class="text-uppercase fw-bold text-muted">Referencia / Comprobante:</small><br>
                            <span class="fs-5 fw-bold text-dark font-monospace">
                                {{ $order->payment_reference }}
                            </span>
                        </div>
                    @else
                        <p class="text-muted small mb-0 mt-2">Pago en efectivo o punto al recibir.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-user me-2 text-secondary"></i>Datos del Cliente
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> {{ $order->contact_name }}</p>
                    <p class="mb-1"><strong>C.I:</strong> {{ $order->user->cedula ?? 'N/A' }}</p>
                    
                    <p class="mb-2">
                        <strong>Teléfono:</strong> 
                        <a href="https://wa.me/58{{ substr(preg_replace('/[^0-9]/', '', $order->contact_phone), 1) }}" target="_blank" class="text-success text-decoration-none fw-bold">
                            <i class="fab fa-whatsapp"></i> {{ $order->contact_phone }}
                        </a>
                    </p>
                    
                    <hr>
                    
                    <p class="mb-1 fw-bold text-secondary small text-uppercase">
                        {{ $order->shipping_type == 'delivery' ? 'Dirección de Entrega' : 'Retiro en Tienda' }}
                    </p>
                    
                    @if($order->shipping_type == 'delivery')
                        <div class="alert alert-secondary p-2 m-0 small border-0">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $order->address }}
                        </div>
                    @else
                        <div class="alert alert-info p-2 m-0 small">
                            El cliente pasará buscando el pedido.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script para renderizar el mapa solo si hay coordenadas --}}
@if($order->latitude && $order->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = {{ $order->latitude }};
        var lng = {{ $order->longitude }};
        
        var map = L.map('admin-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>Ubicación del Cliente</b><br>{{ $order->address }}")
            .openPopup();
    });
</script>
@endif

@endsection