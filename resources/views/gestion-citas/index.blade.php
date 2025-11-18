@extends('layouts.app')

@section('page_title', 'Gestión de Citas Médicas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Gestión de Citas Médicas</h3>
        <a href="{{ route('gestion-citas.calendario') }}" class="btn" style="background: white; color: #1e3c72;">
            <i class="fas fa-calendar"></i> Ver Calendario
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Estadísticas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; text-align: center;">
                <i class="fas fa-clock" style="font-size: 30px; color: #856404;"></i>
                <h3 style="margin: 10px 0; color: #856404;">{{ $estadisticas['pendientes'] }}</h3>
                <p style="margin: 0; color: #856404;">Pendientes</p>
            </div>
            <div style="background: #d4edda; padding: 20px; border-radius: 8px; text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 30px; color: #155724;"></i>
                <h3 style="margin: 10px 0; color: #155724;">{{ $estadisticas['aprobadas'] }}</h3>
                <p style="margin: 0; color: #155724;">Aprobadas</p>
            </div>
            <div style="background: #d1ecf1; padding: 20px; border-radius: 8px; text-align: center;">
                <i class="fas fa-calendar-day" style="font-size: 30px; color: #0c5460;"></i>
                <h3 style="margin: 10px 0; color: #0c5460;">{{ $estadisticas['hoy'] }}</h3>
                <p style="margin: 0; color: #0c5460;">Citas de Hoy</p>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <select name="estado" class="form-control" style="width: auto;">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobadas</option>
                <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazadas</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completadas</option>
                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
            </select>
            <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control" style="width: auto;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->hasAny(['estado', 'fecha']))
                <a href="{{ route('gestion-citas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>

        <!-- Tabla de Citas -->
        @if($citas->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estudiante</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                <td>
                                    <strong>{{ $cita->estudiante->name }}</strong><br>
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
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $citas->links() }}
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-calendar-times" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No hay citas{{ request()->hasAny(['estado', 'fecha']) ? ' que coincidan con los filtros' : '' }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
