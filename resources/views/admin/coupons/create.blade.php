@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0">Crear Nuevo Cupón</h5>
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

                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        
                        {{-- Código y Tipo --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Código del Cupón</label>
                                <input type="text" name="code" class="form-control text-uppercase" placeholder="EJ: OFERTA2026" required>
                                <small class="text-muted">Se guardará en mayúsculas automáticamente.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Descuento</label>
                                <select name="type" class="form-select" required>
                                    <option value="percent">Porcentaje (%)</option>
                                    <option value="fixed">Monto Fijo ($)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Valor y Mínimo --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Valor del Descuento</label>
                                <input type="number" step="0.01" name="value" class="form-control" placeholder="Ej: 10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Compra Mínima (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="min_purchase" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        {{-- Restricciones Específicas --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Solo para Categoría (Opcional)</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Aplica a todas --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Solo para Producto (Opcional)</label>
                                <select name="product_id" class="form-select">
                                    <option value="">-- Aplica a todos --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Fecha de Vencimiento --}}
                        <div class="mb-4">
                            <label class="form-label">Fecha de Vencimiento</label>
                            <input type="datetime-local" name="expires_at" class="form-control">
                            <small class="text-muted">Dejar vacío si no tiene vencimiento.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger px-4">Guardar Cupón</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection