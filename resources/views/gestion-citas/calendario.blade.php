@extends('layouts.app')

@section('page_title', 'Calendario de Citas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Calendario de Citas - {{ \Carbon\Carbon::create($anio, $mes)->locale('es')->isoFormat('MMMM YYYY') }}</h3>
        <a href="{{ route('gestion-citas.index') }}" class="btn" style="background: white; color: #1e3c72;">
            <i class="fas fa-list"></i> Ver Lista
        </a>
    </div>
    <div class="card-body">
        <!-- Navegación de Mes -->
        <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 30px;">
            @php
                $prevMes = $mes == 1 ? 12 : $mes - 1;
                $prevAnio = $mes == 1 ? $anio - 1 : $anio;
                $nextMes = $mes == 12 ? 1 : $mes + 1;
                $nextAnio = $mes == 12 ? $anio + 1 : $anio;
            @endphp

            <a href="{{ route('gestion-citas.calendario', ['mes' => $prevMes, 'anio' => $prevAnio]) }}" class="btn btn-secondary">
                <i class="fas fa-chevron-left"></i> Anterior
            </a>

            <a href="{{ route('gestion-citas.calendario') }}" class="btn btn-primary">
                <i class="fas fa-calendar-day"></i> Mes Actual
            </a>

            <a href="{{ route('gestion-citas.calendario', ['mes' => $nextMes, 'anio' => $nextAnio]) }}" class="btn btn-secondary">
                Siguiente <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        @if($citas->count() > 0)
            @php
                $citasPorFecha = $citas->groupBy(function($cita) {
                    return \Carbon\Carbon::parse($cita->fecha)->format('Y-m-d');
                });
            @endphp

            @foreach($citasPorFecha as $fecha => $citasDia)
                <div style="margin-bottom: 30px; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                    <div style="background: #1e3c72; color: white; padding: 15px;">
                        <h4 style="margin: 0;">
                            <i class="fas fa-calendar-day"></i>
                            {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                        </h4>
                        <small>{{ $citasDia->count() }} cita(s)</small>
                    </div>
                    <div style="padding: 20px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Estudiante</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasDia->sortBy('hora') as $cita)
                                    <tr>
                                        <td><strong>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</strong></td>
                                        <td>
                                            {{ $cita->estudiante->name }}<br>
                                            <small>DNI: {{ $cita->estudiante->dni }}</small>
                                        </td>
                                        <td>{{ \Str::limit($cita->motivo, 40) }}</td>
                                        <td>
                                            @if($cita->estado === 'pendiente')
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif($cita->estado === 'aprobada')
                                                <span class="badge badge-success">Aprobada</span>
                                            @elseif($cita->estado === 'rechazada')
                                                <span class="badge badge-danger">Rechazada</span>
                                            @elseif($cita->estado === 'completada')
                                                <span class="badge badge-info">Completada</span>
                                            @elseif($cita->estado === 'cancelada')
                                                <span class="badge badge-secondary">Cancelada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('gestion-citas.show', $cita) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            @if($cita->estado === 'pendiente')
                                                <form action="{{ route('gestion-citas.aprobar', $cita) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-calendar-times" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No hay citas para {{ \Carbon\Carbon::create($anio, $mes)->locale('es')->isoFormat('MMMM YYYY') }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
