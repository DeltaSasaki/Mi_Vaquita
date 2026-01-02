{{-- SOLUCIÓN 1: Apuntamos correctamente a tu archivo layout --}}
@extends('layouts.front') 

@section('title', $product->name . ' | Mi Vaquita')

@section('content')

{{-- PEQUEÑO AJUSTE CSS PARA MÓVIL --}}
<style>
    .product-img-main { height: 450px; object-fit: cover; }
    /* En pantallas pequeñas (móviles), reducimos la altura de la imagen */
    @media (max-width: 768px) {
        .product-img-main { height: 300px; }
    }
</style>

<div class="container py-4 py-md-5">
    
    <nav aria-label="breadcrumb" class="mb-3 mb-md-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}#productos" class="text-decoration-none text-muted">Productos</a></li>
            <li class="breadcrumb-item active text-danger fw-bold" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-center">
        
        <div class="col-md-6">
            <div class="card border-0 shadow-lg overflow-hidden rounded-3">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="img-fluid w-100 product-img-main" alt="{{ $product->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light product-img-main">
                        <i class="fas fa-drumstick-bite fa-5x text-secondary"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold mb-2 text-uppercase display-5" style="font-family: 'Oswald', sans-serif;">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <span class="badge bg-danger fs-6 me-2">{{ $product->category->name ?? 'Corte General' }}</span>
                @if($product->stock > 0)
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check-circle me-1"></i>Disponible: {{ $product->stock }} Kg
                    </span>
                @else
                    <span class="badge bg-secondary fs-6">Agotado</span>
                @endif
            </div>

            <div class="d-flex align-items-baseline mb-4">
                <h2 class="text-danger fw-bold display-4 me-3">${{ number_format($product->price, 2) }}</h2>
                <span class="text-muted fs-5">/ Kg</span>
            </div>

            <p class="text-muted mb-5 lead" style="font-size: 1.1rem;">
                {{ $product->description ?? 'Corte de carne fresca de la mejor calidad, seleccionado especialmente para ti.' }}
            </p>

            @if($product->stock > 0)
                <div class="p-4 bg-light rounded-3 border">
                    <label class="form-label fw-bold mb-2"><i class="fas fa-balance-scale me-2"></i>¿Cuántos Kilos deseas?</label>
                    
                    <div class="input-group input-group-lg">
                        <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                        <input type="number" id="quantity" class="form-control text-center fw-bold border-secondary" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                        
                        {{-- Botón rojo más ancho --}}
                        <button onclick="addToCartDetail()" class="btn btn-danger px-4 px-md-5 fw-bold text-uppercase">
                            <i class="fas fa-shopping-cart me-2"></i> <span class="d-none d-sm-inline">Agregar</span>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">* Máximo disponible: {{ $product->stock }} Kg</small>
                    
                    {{-- Botón móvil full width adicional si se prefiere (opcional) --}}
                    <button onclick="addToCartDetail()" class="btn btn-danger w-100 fw-bold text-uppercase mt-3 d-block d-sm-none">
                        <i class="fas fa-shopping-cart me-2"></i> Agregar al Carrito
                    </button>
                </div>
            @else
                <div class="alert alert-warning border-warning d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <strong>Producto Agotado</strong><br>
                        Lo sentimos, este corte no está disponible por el momento.
                    </div>
                </div>
            @endif

            <div class="mt-4 d-flex gap-4">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div>
                        <small class="fw-bold d-block text-uppercase">Delivery</small>
                        <small class="text-muted">A todo Barinas</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <small class="fw-bold d-block text-uppercase">Calidad</small>
                        <small class="text-muted">Garantizada</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="mb-4 fw-bold text-uppercase" style="font-family: 'Oswald', sans-serif;">
            <i class="fas fa-fire text-danger me-2"></i>También te puede interesar
        </h3>
        
        {{-- SOLUCIÓN RESPONSIVE:
             row-cols-1: 1 columna en móvil (se ven grandes)
             row-cols-sm-2: 2 columnas en tablets
             row-cols-md-4: 4 columnas en PC
        --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($relatedProducts as $related)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    {{-- SOLUCIÓN URL: Usamos 'slug' en lugar de 'id' --}}
                    <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none">
                        <img src="{{ asset($related->image) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body text-center d-flex flex-column">
                        <h6 class="card-title fw-bold text-dark text-truncate">{{ $related->name }}</h6>
                        <p class="text-danger fw-bold mb-2">${{ number_format($related->price, 2) }}</p>
                        
                        {{-- SOLUCIÓN URL: Usamos 'slug' aquí también --}}
                        <a href="{{ route('products.show', $related->slug) }}" class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto">Ver Detalle</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function incrementQty() {
        let input = document.getElementById('quantity');
        let max = parseInt(input.getAttribute('max'));
        if(parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
    }
    function decrementQty() {
        let input = document.getElementById('quantity');
        if(parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }

    function addToCartDetail() {
        let qty = document.getElementById('quantity').value;
        let productId = {{ $product->id }};
        
        let btn = event.currentTarget;
        let originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch('/add-to-cart/' + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                quantity: qty 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let desktopCount = document.getElementById('cart-count');
                let mobileCount = document.getElementById('cart-count-mobile');
                
                if(desktopCount) desktopCount.innerText = data.cartCount;
                if(mobileCount) mobileCount.innerText = data.cartCount;
                
                // Usamos Alert nativo o Toast si lo tienes configurado
                alert('¡' + data.message + '!'); 
            } else {
                alert('Error al agregar el producto.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error de conexión.');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endpush