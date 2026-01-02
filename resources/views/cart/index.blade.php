@php use App\Models\Schedule; @endphp
@extends('layouts.front')

@section('title', 'Mi Carrito | Mi Vaquita')

@push('styles')
<!-- Estilos para el Mapa (Leaflet) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 300px; width: 100%; border-radius: 8px; margin-top: 10px; z-index: 1; }
</style>
@endpush

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Tu Canasta de <span class="text-danger">Compras</span></h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
    <div class="row">
        
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="m-0"><i class="fas fa-shopping-bag me-2"></i>Productos Seleccionados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr data-id="{{ $id }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px;" class="me-3">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($details['price'], 2) }}</td>
                                        <td>
                                            <input type="number" value="{{ $details['quantity'] }}" 
                                                   class="form-control quantity-input update-cart" 
                                                   min="1" style="width: 70px;">
                                        </td>
                                        <td class="fw-bold text-danger subtotal-display">
                                            ${{ number_format($details['price'] * $details['quantity'], 2) }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-link p-0 text-danger remove-from-cart" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
            </a>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 position-sticky" style="top: 100px;">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    
                    <!-- SECCIÓN DE CUPONES -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">¿Tienes un cupón?</label>
                        @if(session('coupon'))
                            <div class="alert alert-success d-flex justify-content-between align-items-center p-2">
                                <div>
                                    <i class="fas fa-tag me-1"></i> 
                                    <strong>{{ session('coupon')['code'] }}</strong> aplicado
                                </div>
                                <form action="{{ route('coupon.remove') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('coupon.apply') }}" method="POST" class="input-group">
                                @csrf
                                <input type="text" name="code" class="form-control" placeholder="Ingresa tu código">
                                <button class="btn btn-outline-secondary" type="submit">Aplicar</button>
                            </form>
                        @endif
                    </div>
                    <hr>
                    
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="total" value="{{ $total }}">

                        <!-- TIPO DE ENTREGA -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">¿Cómo deseas recibir tu pedido?</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="shipping_type" id="shipping_pickup" value="pickup" checked>
                                <label class="btn btn-outline-danger" for="shipping_pickup"><i class="fas fa-store me-2"></i>Retiro (Reserva)</label>

                                <input type="radio" class="btn-check" name="shipping_type" id="shipping_delivery" value="delivery">
                                <label class="btn btn-outline-danger" for="shipping_delivery"><i class="fas fa-motorcycle me-2"></i>Delivery</label>
                            </div>
                        </div>

                        <!-- SECCIÓN DE DIRECCIÓN Y MAPA (Oculta por defecto) -->
                        <div id="deliverySection" style="display: none;" class="mb-4 p-3 bg-light rounded border">
                            <h6 class="text-danger"><i class="fas fa-map-marker-alt me-2"></i>Datos de Envío</h6>

                            <!-- Selector de Origen de Dirección -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input delivery-source" type="radio" name="delivery_source" id="source_saved" value="saved" checked>
                                    <label class="form-check-label" for="source_saved">
                                        Usar mi dirección guardada
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input delivery-source" type="radio" name="delivery_source" id="source_map" value="map">
                                    <label class="form-check-label" for="source_map">
                                        Seleccionar otra zona de envío (Mapa)
                                    </label>
                                </div>
                            </div>

                            <!-- Opción A: Dirección Guardada -->
                            <div id="savedAddressContainer" class="mt-2">
                                <div class="alert alert-secondary mb-0">
                                    <small class="text-muted d-block mb-1">Dirección registrada:</small>
                                    <i class="fas fa-home me-2"></i>{{ optional(auth()->user())->address ?? 'No tienes dirección registrada.' }}
                                </div>
                                <!-- Enviamos la dirección del usuario como el campo 'address' -->
                                <input type="hidden" name="address" id="address_input_saved" value="{{ optional(auth()->user())->address ?? '' }}">
                            </div>

                            <!-- Opción B: Mapa y Detalles -->
                            <div id="mapAddressContainer" style="display: none;" class="mt-2">
                                <div class="mb-2">
                                    <label class="form-label small">Ubicación Exacta</label>
                                    <div id="map"></div>
                                    <small class="text-muted" id="gps-status">Arrastra el marcador o usa el GPS.</small>
                                    <!-- Deshabilitados por defecto para no enviarlos si se elige dirección guardada -->
                                    <input type="hidden" name="latitude" id="lat" disabled>
                                    <input type="hidden" name="longitude" id="lng" disabled>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" onclick="getLocation()">
                                    <i class="fas fa-crosshairs me-2"></i>Usar mi ubicación actual
                                </button>

                                <div class="mb-3">
                                    <label class="form-label small">Detalles de la zona de envío</label>
                                    <textarea name="address" id="address_input_map" class="form-control" rows="2" placeholder="Ej: Casa color azul, portón negro..." disabled></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Método de Pago</label>
                            <select name="payment_method" class="form-select" id="paymentMethod" required>
                                <option value="bs_efectivo">🇻🇪 Efectivo (Bolívares)</option>
                                <option value="divisa_efectivo">💵 Efectivo (Dólares)</option>
                                <option value="pago_movil">📱 Pago Móvil</option>
                                <option value="zelle">🇺🇸 Zelle</option>
                                <option value="punto">💳 Punto de Venta (Al recibir)</option>
                            </select>
                        </div>

                        <div id="bankInfo" class="alert alert-secondary p-2 small mb-3" style="display: none;">
                            <div id="info-pago-movil" class="d-none">
                                <strong><i class="fas fa-mobile-alt me-1"></i>Datos Pago Móvil:</strong><br>
                                Banco: Banesco<br>
                                Telf: 0414-1234567<br>
                                C.I: 12.345.678
                            </div>
                            <div id="info-zelle" class="d-none">
                                <strong><i class="fas fa-university me-1"></i>Datos Zelle:</strong><br>
                                Email: pagos@mivaquita.com<br>
                                Titular: Carnicería Mi Vaquita
                            </div>
                        </div>

                        <div class="mb-3" id="referenceField" style="display: none;">
                            <label class="form-label fw-bold small text-muted">Referencia / Comprobante</label>
                            <input type="text" name="payment_reference" class="form-control" placeholder="Ej: 123456 o Últimos 4 dígitos">
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="cart-total-display" class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>

                        @php 
                            $discount = 0;
                            if(session('coupon')) {
                                $discount = session('coupon')['discount'];
                            }
                            $finalTotal = max(0, $total - $discount);
                        @endphp

                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Descuento ({{ session('coupon')['code'] }})</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mb-4">
                            <span>Total a Pagar</span>
                            <span id="final-total-display" class="h4 text-danger fw-bold">${{ number_format($finalTotal, 2) }}</span>
                        </div>
                        
                        @auth
                            @if(Schedule::isOpen())
                                <button type="submit" class="btn btn-cta w-100 py-3" onclick="return confirm('¿Confirmar pedido?')">
                                    <i class="fas fa-paper-plane me-2"></i>ENVIAR PEDIDO
                                </button>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                                    <strong>Tienda Cerrada</strong><br>
                                    {{ Schedule::getNextOpenMessage() }}
                                </div>
                                <button type="button" class="btn btn-secondary w-100 py-3" disabled>
                                    NO DISPONIBLE
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-dark w-100 py-3">
                                <i class="fas fa-user-lock me-2"></i>INICIA SESIÓN PARA PAGAR
                            </a>
                            <div class="text-center mt-2">
                                <small>¿No tienes cuenta? <a href="{{ route('register') }}" class="text-danger fw-bold">Regístrate aquí</a></small>
                            </div>
                        @endauth

                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted"><i class="fas fa-lock me-1"></i>Compra 100% Segura</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
            <h3>Tu carrito está vacío</h3>
            <p class="text-muted">¡Aprovecha nuestros cortes frescos!</p>
            <a href="{{ route('home') }}" class="btn btn-cta mt-3">Ir a Comprar</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Script del Mapa (Leaflet) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script type="text/javascript">
    // 1. Lógica para mostrar/ocultar datos bancarios
    // Actualizamos para incluir los nuevos métodos de efectivo
    document.getElementById('paymentMethod')?.addEventListener('change', function() {
        var method = this.value;
        var bankInfo = document.getElementById('bankInfo');
        var refField = document.getElementById('referenceField');
        var infoPM = document.getElementById('info-pago-movil');
        var infoZelle = document.getElementById('info-zelle');

        // Resetear visualización
        bankInfo.style.display = 'none';
        refField.style.display = 'none';
        infoPM.classList.add('d-none');
        infoZelle.classList.add('d-none');

        // Mostrar según selección
        if(method === 'pago_movil') {
            bankInfo.style.display = 'block';
            infoPM.classList.remove('d-none');
            refField.style.display = 'block';
            document.querySelector('input[name="payment_reference"]').required = true;
        } else if (method === 'zelle') {
            bankInfo.style.display = 'block';
            infoZelle.classList.remove('d-none');
            refField.style.display = 'block';
            document.querySelector('input[name="payment_reference"]').required = true;
        } else {
            // Efectivos o Punto no requieren referencia obligatoria
            document.querySelector('input[name="payment_reference"]').required = false;
        }
    });

    // 2. Lógica de Delivery vs Pickup
    const deliverySection = document.getElementById('deliverySection');
    const mapContainer = document.getElementById('map');
    let map, marker;

    document.querySelectorAll('input[name="shipping_type"]').forEach(elem => {
        elem.addEventListener('change', function(event) {
            if (event.target.value === 'delivery') {
                deliverySection.style.display = 'block';
                // Al mostrar la sección de delivery, verificamos si debemos iniciar el mapa
                // (solo si la opción de mapa ya estaba seleccionada)
                if (document.getElementById('source_map').checked) {
                    setTimeout(initMap, 100);
                }
            } else {
                deliverySection.style.display = 'none';
            }
        });
    });

    // Lógica para alternar entre Dirección Guardada y Mapa (NUEVO)
    const containerSaved = document.getElementById('savedAddressContainer');
    const containerMap = document.getElementById('mapAddressContainer');
    const inputSaved = document.getElementById('address_input_saved');
    const inputMap = document.getElementById('address_input_map');
    const inputLat = document.getElementById('lat');
    const inputLng = document.getElementById('lng');

    document.querySelectorAll('input[name="delivery_source"]').forEach(radio => {
        radio.addEventListener('change', function(event) {
            if (event.target.value === 'saved') {
                containerSaved.style.display = 'block';
                containerMap.style.display = 'none';
                
                inputSaved.disabled = false; // Habilitar input de dirección guardada
                inputMap.disabled = true;    // Deshabilitar textarea del mapa
                inputLat.disabled = true;    // No enviar coordenadas
                inputLng.disabled = true;
            } else { // 'map'
                containerSaved.style.display = 'none';
                containerMap.style.display = 'block';

                inputSaved.disabled = true;  // Deshabilitar input de dirección guardada
                inputMap.disabled = false;   // Habilitar textarea del mapa
                inputLat.disabled = false;   // Enviar coordenadas
                inputLng.disabled = false;
                
                // Inicializar el mapa (con un pequeño delay para asegurar que el div es visible)
                setTimeout(initMap, 100);
            }
        });
    });

    function initMap() {
        if (map) return; // Si ya existe, no hacer nada

        // Coordenadas por defecto (Ej: Centro de Caracas o tu ciudad)
        // Puedes cambiar esto a las coordenadas de tu tienda
        const defaultLat = 10.4806; 
        const defaultLng = -66.9036;

        map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Crear marcador arrastrable
        marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        // Evento al terminar de arrastrar
        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        // Evento al hacer clic en el mapa
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    }

    function getLocation() {
        if (navigator.geolocation) {
            document.getElementById('gps-status').innerText = "Buscando tu ubicación...";
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                updateCoordinates(lat, lng);
                document.getElementById('gps-status').innerText = "Ubicación encontrada.";
            });
        } else {
            alert("Tu navegador no soporta geolocalización.");
        }
    }

    // 3. Actualizar Cantidad via AJAX (Código existente...)
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        var parentRow = ele.parents("tr");

        $.ajax({
            url: '{{ route("cart.update") }}',
            method: "PATCH",
            data: {
                _token: '{{ csrf_token() }}', 
                id: parentRow.attr("data-id"), 
                quantity: ele.val()
            },
            success: function (response) {
                parentRow.find(".subtotal-display").text('$' + response.subtotal);
                $("#cart-total-display").text('$' + response.total);
                $("#final-total-display").text('$' + response.total);
            }
        });
    });

    // 3. Eliminar Producto via AJAX
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        
        if(confirm("¿Estás seguro de quitar este producto?")) {
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endpush