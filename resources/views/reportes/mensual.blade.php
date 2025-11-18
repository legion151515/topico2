@extends('layouts.app')

@section('page_title', 'Reporte Mensual')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Reporte Mensual de Atenciones</h3>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fas fa-calendar-alt" style="font-size: 60px; color: #3498db;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 16px;">
                    Selecciona el mes y año para generar el reporte detallado de todas las atenciones médicas.
                </p>
            </div>

            <form action="{{ route('reportes.mensual.reporte') }}" method="GET">
                <div class="form-group">
                    <label for="mes">Mes</label>
                    <select name="mes" id="mes" class="form-control" required>
                        <option value="">Seleccionar mes...</option>
                        <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Diciembre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="anio">Año</label>
                    <select name="anio" id="anio" class="form-control" required>
                        <option value="">Seleccionar año...</option>
                        @php
                            $anioActual = date('Y');
                            for ($i = $anioActual; $i >= 2020; $i--) {
                                echo "<option value='$i' " . ($i == $anioActual ? 'selected' : '') . ">$i</option>";
                            }
                        @endphp
                    </select>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
