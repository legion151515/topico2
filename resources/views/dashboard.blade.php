@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')

<!-- ESTADÍSTICAS RÁPIDAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 35px;">

    <!-- Pacientes Hoy -->
    <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #20C997 0%, #17A2B8 100%); color: white; padding: 30px; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-user-injured" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
        <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">{{ $pacientes_hoy }}</div>
        <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.95;">Pacientes Hoy</p>
        <a href="{{ route('atenciones.index') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-eye"></i> Ver Atenciones
        </a>
    </div>

    <!-- Medicamentos a Vencer -->
    <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%); color: white; padding: 30px; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-pills" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
        <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">{{ $medicamentos_vencer }}</div>
        <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.95;">Medicamentos a Vencer</p>
        <a href="{{ route('medicamentos.index') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-list"></i> Ver Medicamentos
        </a>
    </div>

    <!-- Nueva Atención -->
    <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%); color: white; padding: 30px; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-plus-circle" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
        <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">+</div>
        <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.95;">Nueva Atención</p>
        <a href="{{ route('atenciones.create') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-file-medical"></i> Crear Atención
        </a>
    </div>

    <!-- Reportes -->
    <div class="card" style="text-align: center; border: none; background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%); color: white; padding: 30px; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-chart-bar" style="font-size: 50px; margin-bottom: 15px; opacity: 0.9;"></i>
        <div style="font-size: 48px; font-weight: 700; margin-bottom: 10px;">📊</div>
        <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.95;">Reportes</p>
        <a href="{{ route('reportes.index') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-file-alt"></i> Ver Reportes
        </a>
    </div>

</div>

<!-- ÚLTIMAS 5 ATENCIONES -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Últimas 5 Atenciones</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-user"></i> Paciente</th>
                        <th><i class="fas fa-tag"></i> Acrónimo</th>
                        <th><i class="fas fa-notes-medical"></i> Motivo</th>
                        <th><i class="fas fa-clock"></i> Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimas_atenciones as $atencion)
                        <tr>
                            <td><strong>{{ $atencion->created_at->format('d/m/Y') }}</strong></td>
                            <td>
                                @if($atencion->paciente)
                                    {{ $atencion->paciente->nombre }} {{ $atencion->paciente->apellido }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($atencion->paciente && $atencion->paciente->carrera && $atencion->paciente->carrera->acronimo)
                                    <span class="badge badge-info">{{ $atencion->paciente->carrera->acronimo }}</span>
                                @elseif($atencion->categoria)
                                    <span class="badge badge-success">{{ $atencion->categoria }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $atencion->motivo->nombre ?? $atencion->motivo_otro ?? 'N/A' }}</td>
                            <td><span class="badge badge-secondary">{{ $atencion->hora_entrada }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                                <span style="color: #999;">No hay atenciones registradas</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
</style>

@endsection