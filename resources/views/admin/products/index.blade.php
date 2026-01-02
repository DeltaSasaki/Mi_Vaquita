@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Inventario de Carnes</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-2"></i>Nuevo Corte
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
                        <th class="ps-4">Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="ps-4">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/50' }}" width="50" class="rounded">
                        </td>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->stock < 10)
                                <span class="text-danger fw-bold">{{ $product->stock }} kg (Bajo)</span>
                            @else
                                <span class="text-success">{{ $product->stock }} kg</span>
                            @endif
                        </td>
                        <td>

                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary me-1">
        <i class="fas fa-edit"></i>
    </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Borrar este producto?')">
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
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection