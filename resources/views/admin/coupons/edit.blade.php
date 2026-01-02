@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0">Editar Cupón: {{ $coupon->code }}</h5>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Switch Activo/Inactivo --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" {{ $coupon->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Cupón Activo</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Código</label>
                                <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $coupon->code) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select" required>
                                    <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Porcentaje (%)</option>
                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Monto Fijo ($)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Valor</label>
                                <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Compra Mínima</label>
                                <input type="number" step="0.01" name="min_purchase" class="form-control" value="{{ old('min_purchase', $coupon->min_purchase) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Todas --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $coupon->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Producto Específico</label>
                                <select name="product_id" class="form-select">
                                    <option value="">-- Todos --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" {{ $coupon->product_id == $prod->id ? 'selected' : '' }}>
                                            {{ $prod->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Vencimiento</label>
                            <input type="datetime-local" name="expires_at" class="form-control" 
                                value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Actualizar Cupón</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection