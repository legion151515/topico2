@extends('layouts.app')

@section('page_title', 'Reporte Anual')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Reporte Anual de Atenciones</h3>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fas fa-calendar" style="font-size: 60px; color: #2ecc71;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 16px;">
                    Selecciona el año para generar el reporte detallado de todas las atenciones médicas.
                </p>
            </div>

            <form action="{{ route('reportes.anual.reporte') }}" method="GET">
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
