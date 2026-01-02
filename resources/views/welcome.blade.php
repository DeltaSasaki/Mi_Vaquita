@extends('layouts.front')

@section('title', 'Inicio | Carnicería Mi Vaquita')

@push('styles')
<style>
    /* === HERO SECTION (Banner Principal) === */
    .hero-header {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=1470&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* Ajuste Hero para Móvil */
    @media (max-width: 768px) {
        .hero-header { height: 50vh; }
        .hero-header h1 { font-size: 2.5rem; }
    }

    /* === BOTÓN DE ACCIÓN (CTA) === */
    .btn-cta {
        background-color: #8B0000 !important;
        color: white !important;
        border: 2px solid #8B0000 !important;
        font-weight: 800;
        text-transform: uppercase;
        padding: 12px 30px;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        transition: all 0.3s ease;
    }
    
    .btn-cta:hover {
        background-color: #600000 !important;
        border-color: #600000 !important;
        transform: translateY(-3px);
    }
    
    /* === CATEGORÍAS (Corrección del Scroll) === */
    .categories-wrapper {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 10px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch; /* Suavidad en iPhone */
    }
    .categories-wrapper::-webkit-scrollbar { display: none; }

    /* ESTA CLASE ARREGLA EL "APLASTAMIENTO" */
    .category-link {
        flex: 0 0 160px; /* Ancho fijo: 160px. "0 0" impide que se encoja */
        display: block;
        text-decoration: none;
    }

    .category-card { 
        border: none; 
        border-radius: 15px; 
        overflow: hidden; 
        position: relative; 
        width: 100%;     /* Ocupa todo el ancho del enlace padre */
        height: 100px;   /* Altura fija móvil */
        transition: transform 0.2s;
    }
    
    /* Versión PC: Reseteamos para Grid */
    @media (min-width: 768px) {
        .categories-wrapper { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            overflow: visible; 
        }
        .category-link {
            flex: unset; /* Quitamos el ancho fijo en PC */
        }
        .category-card { 
            height: 200px; /* Altura PC */
        }
    }

    .category-img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; /* Blindaje de imagen */
        filter: brightness(0.7); 
    }
    
    .category-title { 
        position: absolute; 
        bottom: 0; left: 0; right: 0; 
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: white; 
        padding: 10px; 
        font-size: 1rem; 
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* === PRODUCTOS (Grid Compacto) === */
    .product-card { 
        border: 1px solid #f0f0f0; 
        border-radius: 10px; 
        transition: 0.3s; 
        background: white; 
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .product-img { height: 180px; width: 100%; object-fit: cover; }
    
    @media (max-width: 768px) {
        .product-img { height: 140px; }
        .card-body { padding: 10px; }
        .card-title { font-size: 1rem; margin-bottom: 5px; }
        .price { font-size: 1rem; }
        .btn-add-cart { font-size: 0.8rem; padding: 5px; }
    }

    .price { color: #8B0000; font-weight: 800; }
    
    .btn-add-cart { 
        background-color: #1a1a1a; 
        color: white; 
        width: 100%; 
        border: none; 
        padding: 8px 0; 
        border-radius: 5px;
        transition: 0.3s; 
    }
    .btn-add-cart:hover { background-color: #8B0000; }

    /* === NOTIFICACIÓN TOAST (Snackbar) === */
    #snackbar {
        visibility: hidden;
        min-width: 250px;
        margin-left: -125px; /* Mitad del ancho para centrar */
        background-color: #198754; /* Verde Éxito (Bootstrap success) */
        color: #fff;
        text-align: center;
        border-radius: 50px;
        padding: 16px;
        position: fixed;
        z-index: 9999;
        left: 50%;
        bottom: 30px;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        opacity: 0;
        transition: opacity 0.3s, bottom 0.3s;
    }

    #snackbar.show {
        visibility: visible;
        opacity: 1;
        bottom: 50px; /* Efecto de deslizar hacia arriba */
    }

</style>
@endpush

@push('scripts')
<script>
    function addToCart(id) {
        $.ajax({
            url: "{{ url('add-to-cart') }}/" + id,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    // 1. Mostrar notificación sutil en lugar de alert()
                    showToast(response.message);
                    
                    // 2. Actualizar contador del carrito en tiempo real
                    // Nota: Asegúrate de que en tu layout.blade.php el badge tenga id="cart-count"
                    $('#cart-count').text(response.cartCount);
                }
            },
            error: function (xhr) {
                console.log('Error al agregar al carrito');
            }
        });
    }

    // Función para mostrar el mensaje flotante
    function showToast(message) {
        var x = document.getElementById("snackbar");
        x.innerText = message;
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000); // Se oculta a los 3 segundos
    }
</script>
@endpush

@section('content')
    <div id="snackbar"></div>

    <header class="hero-header">
        <div class="container px-4">
            <h1 class="display-4 fw-bold mb-3 text-white">Cortes Premium</h1>
            <p class="lead mb-4 d-none d-md-block text-white">Calidad garantizada del campo a tu mesa.</p>
            
            <a href="#productos" class="btn btn-cta">Ver Catálogo</a>
        </div>
    </header>

    <section class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 fw-bold">Categorías</h4>
            <small class="text-muted d-md-none"><i class="fas fa-arrow-right"></i> Desliza</small>
        </div>

        <div class="categories-wrapper">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="category-link">
                    <div class="category-card shadow-sm">
                        <img src="{{ $category->image ?? 'https://via.placeholder.com/800x600?text=Carne' }}" 
                             class="category-img" 
                             alt="{{ $category->name }}">
                        <div class="category-title">{{ $category->name }}</div>
                    </div>
                </a>
            @endforeach

            @if($categories->isEmpty())
                <div class="col-12 text-center w-100">
                    <p class="text-muted">Próximamente.</p>
                </div>
            @endif
        </div>
    </section>

    <section id="productos" class="container my-5">
        <h3 class="text-center mb-4 fw-bold">Productos <span style="color: #8B0000;">Destacados</span></h3>
        
        <div class="row g-3"> 
            @foreach($products as $product)
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100">
                        {{-- ENLACE A DETALLE DE PRODUCTO EN LA IMAGEN --}}
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/800x600?text=Producto' }}" 
                                 class="card-img-top product-img" 
                                 alt="{{ $product->name }}">
                        </a>
                        
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                {{-- ENLACE A DETALLE DE PRODUCTO EN EL TÍTULO --}}
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                                </a>
                                <p class="card-text text-muted small d-none d-md-block">
                                    {{ Str::limit($product->description, 40) }}
                                </p>
                            </div>
                            
                            <div class="mt-2">
                                <div class="mb-2">
                                    <span class="price">${{ number_format($product->price, 2) }}</span>
                                    <small class="text-muted">/kg</small>
                                </div>
                                
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-add-cart">
                                    <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Añadir</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($products->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-drumstick-bite fa-3x mb-3"></i>
                        <p>No hay productos disponibles por el momento.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection