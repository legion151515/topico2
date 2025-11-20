@extends('layouts.app')

@section('page_title', 'Reporte de Actividad por Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-md"></i> Reporte de Actividad por Usuario</h2>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.por-usuario.reporte') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="user_id"><i class="fas fa-user"></i> Seleccionar Usuario:</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Seleccione un usuario --</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">
                            {{ $usuario->name }} - {{ ucfirst($usuario->tipo_usuario) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="periodo"><i class="fas fa-calendar"></i> Periodo:</label>
                <select name="periodo" id="periodo" class="form-control" required>
                    <option value="total">Total (Todas las atenciones)</option>
                    <option value="mes">Por Mes</option>
                    <option value="anio">Por Año</option>
                </select>
            </div>

            <div id="campos_mes" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mes">Mes:</label>
                            <select name="mes" id="mes" class="form-control">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="anio_mes">Año:</label>
                            <input type="number" name="anio" id="anio_mes" class="form-control" value="{{ date('Y') }}" min="2020" max="2100">
                        </div>
                    </div>
                </div>
            </div>

            <div id="campos_anio" style="display: none;">
                <div class="form-group">
                    <label for="anio_solo">Año:</label>
                    <input type="number" name="anio" id="anio_solo" class="form-control" value="{{ date('Y') }}" min="2020" max="2100">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-chart-bar"></i> Generar Reporte
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('periodo').addEventListener('change', function() {
    const mes = document.getElementById('campos_mes');
    const anio = document.getElementById('campos_anio');

    mes.style.display = 'none';
    anio.style.display = 'none';

    if (this.value === 'mes') {
        mes.style.display = 'block';
    } else if (this.value === 'anio') {
        anio.style.display = 'block';
    }
});
</script>
@endsection
