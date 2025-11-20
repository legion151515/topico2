@extends('layouts.app')

@section('page_title', 'Nuevo Medicamento')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Registrar Nuevo Medicamento</h2>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('medicamentos.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre del Medicamento *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento"
                               class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                               value="{{ old('fecha_vencimiento') }}">
                        @error('fecha_vencimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                          rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Indicaciones, presentación, etc.</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo de Unidad *</label>
                        <select name="tipo_unidad" id="tipo_unidad" class="form-control @error('tipo_unidad') is-invalid @enderror" required>
                            <option value="unidad" {{ old('tipo_unidad') == 'unidad' ? 'selected' : '' }}>Unidades (pastillas, tabletas, cápsulas)</option>
                            <option value="ml" {{ old('tipo_unidad') == 'ml' ? 'selected' : '' }}>Mililitros (ml) - Líquidos</option>
                            <option value="gr" {{ old('tipo_unidad') == 'gr' ? 'selected' : '' }}>Gramos (gr) - Pomadas, cremas</option>
                            <option value="ampolla" {{ old('tipo_unidad') == 'ampolla' ? 'selected' : '' }}>Ampollas</option>
                            <option value="sobre" {{ old('tipo_unidad') == 'sobre' ? 'selected' : '' }}>Sobres</option>
                            <option value="otros" {{ old('tipo_unidad') == 'otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        @error('tipo_unidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">¿En qué se mide este medicamento?</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Presentación</label>
                        <input type="text" name="presentacion" class="form-control @error('presentacion') is-invalid @enderror"
                               value="{{ old('presentacion') }}" placeholder="Ej: 1000ml, 500mg, 100 unidades">
                        @error('presentacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Contenido del envase o presentación comercial</small>
                    </div>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, rgba(29, 112, 184, 0.1) 0%, rgba(13, 110, 253, 0.1) 100%); padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #1D70B8;">
                <p style="margin: 0; color: #1D70B8; font-weight: 600;"><i class="fas fa-info-circle"></i> Información Importante sobre Stock:</p>
                <p style="margin: 5px 0 0 0; font-size: 14px;" id="stock_info_text">
                    Ingresa la cantidad en <strong>unidades</strong>. Ejemplo: si tienes 50 pastillas, ingresa 50.
                </p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cantidad en Stock *</label>
                        <input type="number" name="cantidad_stock" id="cantidad_stock"
                               class="form-control @error('cantidad_stock') is-invalid @enderror"
                               value="{{ old('cantidad_stock', 0) }}" min="0" step="any" required>
                        @error('cantidad_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted" id="stock_unit_label">En unidades</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Stock Mínimo de Alerta *</label>
                        <input type="number" name="stock_minimo_alerta"
                               class="form-control @error('stock_minimo_alerta') is-invalid @enderror"
                               value="{{ old('stock_minimo_alerta', 10) }}" min="0" required>
                        @error('stock_minimo_alerta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Se alertará cuando el stock esté por debajo de este valor</small>
                    </div>
                </div>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Medicamento
            </button>
            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoUnidadSelect = document.getElementById('tipo_unidad');
    const stockInfoText = document.getElementById('stock_info_text');
    const stockUnitLabel = document.getElementById('stock_unit_label');

    const textos = {
        'unidad': {
            info: 'Ingresa la cantidad en <strong>unidades</strong>. Ejemplo: si tienes 50 pastillas, ingresa 50.',
            label: 'En unidades'
        },
        'ml': {
            info: 'Ingresa la cantidad en <strong>mililitros (ml)</strong>. Ejemplo: si tienes una botella de 1000ml y tienes 3 botellas completas, ingresa 3000. Si usaste 100ml, te quedan 2900ml.',
            label: 'En mililitros (ml)'
        },
        'gr': {
            info: 'Ingresa la cantidad en <strong>gramos (gr)</strong>. Ejemplo: si tienes un tubo de 50gr y tienes 2 tubos, ingresa 100.',
            label: 'En gramos (gr)'
        },
        'ampolla': {
            info: 'Ingresa la cantidad en <strong>ampollas</strong>. Ejemplo: si tienes 20 ampollas, ingresa 20.',
            label: 'En ampollas'
        },
        'sobre': {
            info: 'Ingresa la cantidad en <strong>sobres</strong>. Ejemplo: si tienes 30 sobres, ingresa 30.',
            label: 'En sobres'
        },
        'otros': {
            info: 'Ingresa la cantidad en la unidad correspondiente.',
            label: 'En la unidad correspondiente'
        }
    };

    tipoUnidadSelect.addEventListener('change', function() {
        const tipo = this.value;
        if (textos[tipo]) {
            stockInfoText.innerHTML = textos[tipo].info;
            stockUnitLabel.textContent = textos[tipo].label;
        }
    });
});
</script>
@endsection
