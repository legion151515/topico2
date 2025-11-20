@extends('layouts.app')

@section('page_title', 'Reporte de Actividad - ' . $usuario->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-md"></i> Reporte de Actividad - {{ $usuario->name }}</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('reportes.por-usuario') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <form action="{{ route('reportes.por-usuario.pdf') }}" method="GET" style="display: inline;">
                <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                <input type="hidden" name="periodo" value="{{ $periodo }}">
                @if($periodo === 'mes')
                    <input type="hidden" name="mes" value="{{ request('mes') }}">
                    <input type="hidden" name="anio" value="{{ request('anio') }}">
                @elseif($periodo === 'anio')
                    <input type="hidden" name="anio" value="{{ request('anio') }}">
                @endif
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <!-- Información del Usuario y Periodo -->
        <div style="background: linear-gradient(135deg, rgba(233, 30, 99, 0.1) 0%, rgba(194, 24, 91, 0.1) 100%); padding: 25px; border-radius: 16px; margin-bottom: 30px; border-left: 4px solid #E91E63;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div>
                    <strong style="color: #E91E63;"><i class="fas fa-user"></i> Usuario:</strong><br>
                    <span style="font-size: 18px;">{{ $usuario->name }}</span>
                </div>
                <div>
                    <strong style="color: #E91E63;"><i class="fas fa-id-badge"></i> Tipo:</strong><br>
                    <span class="badge badge-info" style="font-size: 14px;">{{ ucfirst($usuario->tipo_usuario) }}</span>
                </div>
                <div>
                    <strong style="color: #E91E63;"><i class="fas fa-calendar"></i> Periodo:</strong><br>
                    <span style="font-size: 18px;">{{ $nombrePeriodo }}</span>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 35px;">
            <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #20C997 0%, #17A2B8 100%); color: white; padding: 30px;">
                <i class="fas fa-notes-medical" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">{{ $totalAtenciones }}</div>
                <p style="margin: 0; font-size: 16px; opacity: 0.95;">Total de Atenciones</p>
            </div>

            <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%); color: white; padding: 30px;">
                <i class="fas fa-users" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
                <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">{{ $pacientesUnicos }}</div>
                <p style="margin: 0; font-size: 16px; opacity: 0.95;">Pacientes Únicos</p>
            </div>
        </div>

        @if(count($medicamentosUsados) > 0)
            <h4><i class="fas fa-pills"></i> Medicamentos Más Utilizados</h4>
            <div class="table-responsive" style="margin-bottom: 30px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Cantidad Total Usada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($medicamentosUsados, 0, 10, true) as $nombre => $cantidad)
                            <tr>
                                <td><strong>{{ $nombre }}</strong></td>
                                <td><span class="badge badge-primary">{{ $cantidad }} unidades</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(count($motivosFrecuentes) > 0)
            <h4><i class="fas fa-heartbeat"></i> Motivos de Consulta Más Frecuentes</h4>
            <div class="table-responsive" style="margin-bottom: 30px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Motivo</th>
                            <th>Cantidad de Atenciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motivosFrecuentes->take(10) as $motivo => $cantidad)
                            <tr>
                                <td><strong>{{ $motivo }}</strong></td>
                                <td><span class="badge badge-success">{{ $cantidad }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h4><i class="fas fa-list"></i> Detalle de Atenciones</h4>
        @if($atenciones->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-user"></i> Paciente</th>
                            <th><i class="fas fa-notes-medical"></i> Motivo</th>
                            <th><i class="fas fa-pills"></i> Medicamentos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($atenciones as $atencion)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($atencion->fecha)->format('d/m/Y') }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($atencion->hora_entrada)->format('H:i') }}</td>
                                <td>
                                    @if($atencion->paciente)
                                        {{ $atencion->paciente->nombre }} {{ $atencion->paciente->apellido }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $atencion->motivo->nombre ?? $atencion->motivo_otro ?? 'N/A' }}</td>
                                <td><span class="badge badge-primary">{{ $atencion->medicamentos->count() }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No hay atenciones registradas para este usuario en el periodo seleccionado.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
