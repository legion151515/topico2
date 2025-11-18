@extends('layouts.app')

@section('page_title', 'Reporte Anual - ' . $anio)

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Reporte Anual - {{ $anio }}</h3>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('reportes.anual') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <form action="{{ route('reportes.anual.pdf') }}" method="GET" style="display: inline;">
                <input type="hidden" name="anio" value="{{ $anio }}">
                <button type="submit" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </form>
            <form action="{{ route('reportes.anual.excel') }}" method="GET" style="display: inline;">
                <input type="hidden" name="anio" value="{{ $anio }}">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($atenciones->count() > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                <strong><i class="fas fa-info-circle"></i> Total de atenciones:</strong>
                <span class="badge badge-primary badge-lg">{{ $atenciones->count() }}</span>
            </div>

            <div style="overflow-x: auto;">
                <table class="table table-striped" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Carrera</th>
                            <th>Semestre</th>
                            <th>Edad</th>
                            <th>Motivo de Consulta</th>
                            <th>Medicamentos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($atenciones as $index => $atencion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($atencion->fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($atencion->hora_entrada)->format('H:i') }}</td>
                                <td>
                                    @if($atencion->paciente && $atencion->paciente->carrera)
                                        <span class="badge badge-info">{{ $atencion->paciente->carrera->acronimo }}</span>
                                    @elseif($atencion->paciente && $atencion->paciente->nivel)
                                        @if($atencion->paciente->nivel->nivel_escuela)
                                            <span class="badge badge-success">Escuela</span>
                                        @elseif($atencion->paciente->nivel->otros_especificacion)
                                            <span class="badge badge-warning">Otros</span>
                                        @else
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($atencion->paciente && $atencion->paciente->semestre)
                                        {{ $atencion->paciente->semestre }}°
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($atencion->paciente && $atencion->paciente->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($atencion->paciente->fecha_nacimiento)->age }} años
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($atencion->motivo)
                                        {{ $atencion->motivo->nombre }}
                                    @elseif($atencion->motivo_otro)
                                        {{ $atencion->motivo_otro }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($atencion->medicamentos && $atencion->medicamentos->count() > 0)
                                        <ul style="margin: 0; padding-left: 20px;">
                                            @foreach($atencion->medicamentos as $med)
                                                <li>{{ $med->nombre }} ({{ $med->pivot->cantidad }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">Sin medicamentos</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No hay atenciones registradas para el año {{ $anio }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
