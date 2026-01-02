@extends('layouts.front')

@section('title', 'Resultados de búsqueda: ' . $query)

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h2 class="m-0">Resultados para: <span class="text-danger">"{{ $query }}"</span></h2>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-warning text-center py-5 shadow-sm border-0">
            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
            <h4>No encontramos productos que coincidan.</h4>
            <p class="text-muted">Intenta verificar la ortografía o usa términos más generales (ej: carne, pollo).</p>
            <a href="{{ route('home') }}" class="btn btn-cta mt-3">Ver todo el catálogo</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="card product-card h-100">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/800x600?text=Sin+Imagen' }}" 
                             class="card-img-top product-img" 
                             alt="{{ $product->name }}">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted small flex-grow-1">
                                {{ Str::limit($product->description, 50) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price">${{ number_format($product->price, 2) }}</span>
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            
                            <button onclick="addToCart({{ $product->id }})" class="btn btn-add-cart">
                                <i class="fas fa-cart-plus me-2"></i>Añadir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection