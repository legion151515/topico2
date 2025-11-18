@extends('layouts.app')

@section('page_title', 'Detalle de Cita')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detalle de Cita #{{ $cita->id }}</h3>
        <a href="{{ route('gestion-citas.index') }}" class="btn" style="background: white; color: #1e3c72;">
            <i class="fas fa-arrow-left"></i> Volver
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Información de la Cita -->
            <div>
                <h4 style="color: #1e3c72; margin-bottom: 20px;"><i class="fas fa-calendar-alt"></i> Información de la Cita</h4>
                <table class="table">
                    <tr>
                        <th>Fecha:</th>
                        <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Hora:</th>
                        <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
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
                    </tr>
                    <tr>
                        <th>Creada:</th>
                        <td>{{ $cita->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Información del Estudiante -->
            <div>
                <h4 style="color: #1e3c72; margin-bottom: 20px;"><i class="fas fa-user-graduate"></i> Información del Estudiante</h4>
                <table class="table">
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $cita->estudiante->name }}</td>
                    </tr>
                    <tr>
                        <th>DNI:</th>
                        <td>{{ $cita->estudiante->dni }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $cita->estudiante->email }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr style="margin: 30px 0;">

        <!-- Motivo de la Consulta -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #1e3c72; margin-bottom: 15px;"><i class="fas fa-stethoscope"></i> Motivo de la Consulta</h4>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #3498db;">
                {{ $cita->motivo }}
            </div>
        </div>

        <!-- Observaciones del Estudiante -->
        @if($cita->observaciones_estudiante)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #1e3c72; margin-bottom: 15px;"><i class="fas fa-comment"></i> Observaciones del Estudiante</h4>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #f39c12;">
                    {{ $cita->observaciones_estudiante }}
                </div>
            </div>
        @endif

        <!-- Observaciones del Personal -->
        @if($cita->observaciones_personal)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #1e3c72; margin-bottom: 15px;"><i class="fas fa-user-md"></i> Observaciones del Personal</h4>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #e74c3c;">
                    {{ $cita->observaciones_personal }}
                </div>
            </div>
        @endif

        <!-- Atendido por -->
        @if($cita->personalAtendio)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #1e3c72; margin-bottom: 15px;"><i class="fas fa-user-check"></i> Atendido Por</h4>
                <div style="background: #e8f5e9; padding: 15px; border-radius: 8px;">
                    <strong>{{ $cita->personalAtendio->name }}</strong> ({{ ucfirst($cita->personalAtendio->tipo_usuario) }})
                </div>
            </div>
        @endif

        <hr style="margin: 30px 0;">

        <!-- Acciones -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @if($cita->estado === 'pendiente')
                <form action="{{ route('gestion-citas.aprobar', $cita) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar Cita
                    </button>
                </form>

                <button type="button" class="btn btn-danger" onclick="document.getElementById('rechazar-form').style.display='block'">
                    <i class="fas fa-times"></i> Rechazar Cita
                </button>
            @endif

            @if($cita->estado === 'aprobada')
                <button type="button" class="btn btn-info" onclick="document.getElementById('completar-form').style.display='block'">
                    <i class="fas fa-check-circle"></i> Marcar como Completada
                </button>
            @endif

            @if(in_array($cita->estado, ['pendiente', 'aprobada']))
                <button type="button" class="btn btn-warning" onclick="document.getElementById('cancelar-form').style.display='block'">
                    <i class="fas fa-ban"></i> Cancelar Cita
                </button>
            @endif
        </div>

        <!-- Formularios ocultos -->
        @if($cita->estado === 'pendiente')
            <div id="rechazar-form" style="display: none; margin-top: 20px; padding: 20px; background: #f8d7da; border-radius: 8px;">
                <h4>Rechazar Cita</h4>
                <form action="{{ route('gestion-citas.rechazar', $cita) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="observaciones_personal">Motivo del Rechazo *</label>
                        <textarea name="observaciones_personal" id="observaciones_personal" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('rechazar-form').style.display='none'">Cancelar</button>
                </form>
            </div>
        @endif

        @if($cita->estado === 'aprobada')
            <div id="completar-form" style="display: none; margin-top: 20px; padding: 20px; background: #d1ecf1; border-radius: 8px;">
                <h4>Completar Cita</h4>
                <form action="{{ route('gestion-citas.completar', $cita) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="observaciones_personal_completar">Observaciones (opcional)</label>
                        <textarea name="observaciones_personal" id="observaciones_personal_completar" rows="3" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-info">Confirmar Completado</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('completar-form').style.display='none'">Cancelar</button>
                </form>
            </div>
        @endif

        @if(in_array($cita->estado, ['pendiente', 'aprobada']))
            <div id="cancelar-form" style="display: none; margin-top: 20px; padding: 20px; background: #fff3cd; border-radius: 8px;">
                <h4>Cancelar Cita</h4>
                <form action="{{ route('gestion-citas.cancelar', $cita) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="observaciones_personal_cancelar">Motivo de Cancelación *</label>
                        <textarea name="observaciones_personal" id="observaciones_personal_cancelar" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Confirmar Cancelación</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('cancelar-form').style.display='none'">Cancelar</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
