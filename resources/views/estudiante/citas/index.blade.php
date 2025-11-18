@extends('layouts.estudiante')

@section('page_title', 'Mis Citas Médicas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Mis Citas Médicas</h3>
        <a href="{{ route('estudiante.citas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agendar Nueva Cita
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

        @if($citas->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-notes-medical"></i> Motivo</th>
                            <th><i class="fas fa-info-circle"></i> Estado</th>
                            <th><i class="fas fa-user-md"></i> Atendido Por</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</strong></td>
                                <td><span class="badge badge-secondary">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</span></td>
                                <td>{{ \Str::limit($cita->motivo, 50) }}</td>
                                <td>
                                    @if($cita->estado === 'pendiente')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>
                                    @elseif($cita->estado === 'aprobada')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Aprobada</span>
                                    @elseif($cita->estado === 'rechazada')
                                        <span class="badge badge-danger"><i class="fas fa-times"></i> Rechazada</span>
                                    @elseif($cita->estado === 'completada')
                                        <span class="badge badge-info"><i class="fas fa-check-double"></i> Completada</span>
                                    @elseif($cita->estado === 'cancelada')
                                        <span class="badge badge-secondary"><i class="fas fa-ban"></i> Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cita->personalAtendio)
                                        {{ $cita->personalAtendio->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($cita->estado, ['pendiente', 'aprobada']))
                                        <form action="{{ route('estudiante.citas.cancelar', $cita) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif

                                    @if($cita->observaciones_personal)
                                        <button type="button" class="btn btn-sm btn-info" onclick="alert('{{ addslashes($cita->observaciones_personal) }}')">
                                            <i class="fas fa-comment"></i> Observaciones
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-calendar-times" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No tienes citas agendadas
                </p>
                <a href="{{ route('estudiante.citas.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                    <i class="fas fa-plus"></i> Agendar Mi Primera Cita
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
