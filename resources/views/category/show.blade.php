@extends('layouts.front')

@section('title', $category->name . ' | Carnicería Mi Vaquita')

@push('styles')
<style>
    /* === HEADER DE CATEGORÍA === */
    .category-header {
        /* Usamos asset() y un fallback por si no hay imagen */
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('{{ $category->image ? asset($category->image) : "https://via.placeholder.com/1200x400?text=Categoria" }}');
        background-size: cover;
        background-position: center;
        height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* Ajuste de altura en móvil */
    @media (max-width: 768px) {
        .category-header { height: 30vh; }
        .category-header h1 { font-size: 2rem; }
    }

    /* === PRODUCTOS (Mismos estilos del Home para consistencia) === */
    .product-card { 
        border: 1px solid #f0f0f0; 
        border-radius: 10px; 
        transition: 0.3s; 
        background: white; 
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* IMAGEN BLINDADA PC */
    .product-img { height: 180px; width: 100%; object-fit: cover; }
    
    /* AJUSTES MÓVIL (2 Columnas) */
    @media (max-width: 768px) {
        .product-img { height: 140px; }
        .card-body { padding: 10px; }
        .card-title { font-size: 1rem; margin-bottom: 5px; }
        .price { font-size: 1rem; }
        .btn-add-cart { font-size: 0.8rem; padding: 5px; }
    }

    .price { color: #8B0000; font-weight: 800; }
    
    .btn-add-cart { 
        background-color: #1a1a1a; color: white; width: 100%; border: none; 
        padding: 8px 0; border-radius: 5px; transition: 0.3s; 
    }
    .btn-add-cart:hover { background-color: #8B0000; }

    /* BOTÓN CTA (Por si la categoría está vacía) */
    .btn-cta {
        background-color: #8B0000; color: white; border: none; font-weight: bold;
        padding: 10px 25px; border-radius: 30px; text-decoration: none; display: inline-block;
    }
    .btn-cta:hover { background-color: #600000; color: white; }

</style>
@endpush

@section('content')
    <header class="category-header">
        <div class="container">
            <h1 class="display-4 fw-bold text-uppercase">{{ $category->name }}</h1>
            <p class="lead d-none d-md-block">{{ $category->description }}</p>
        </div>
    </header>

    <section class="container my-5">
        <div class="d-flex align-items-center mb-4 border-bottom pb-3">
            <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm me-3 rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <h4 class="m-0 fw-bold">Cortes Disponibles</h4>
        </div>

        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100">
                        <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/800x600?text=Producto' }}" 
                             class="card-img-top product-img"
                             alt="{{ $product->name }}">
                             
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-truncate">{{ $product->name }}</h5>
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
                    <div class="text-muted mb-4">
                        <i class="fas fa-search fa-3x mb-3 text-secondary"></i>
                        <h3>No hay productos en esta categoría aún.</h3>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-cta shadow">
                        Ver otros productos
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection