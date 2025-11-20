@extends('layouts.app')

@section('page_title', 'Reportes')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #20C997 0%, #17A2B8 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-chart-pie" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Atenciones por Área</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Estadísticas de pacientes por carrera</p>
        <a href="{{ route('reportes.area') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-eye"></i> Ver Reporte
        </a>
    </div>

    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-hospital-user" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Enfermedades Comunes</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Motivos de consulta más frecuentes</p>
        <a href="{{ route('reportes.enfermedad') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-eye"></i> Ver Reporte
        </a>
    </div>

    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #DC3545 0%, #C82333 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-exclamation-triangle" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Stock Bajo</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Medicamentos con inventario bajo</p>
        <a href="{{ route('reportes.stock') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-exclamation-circle"></i> Ver Reporte
        </a>
    </div>

    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-calendar-alt" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Reporte Mensual</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Atenciones detalladas por mes (PDF y Excel)</p>
        <a href="{{ route('reportes.mensual') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-file-pdf"></i> Generar Reporte
        </a>
    </div>

    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-calendar" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Reporte Anual</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Atenciones detalladas por año (PDF y Excel)</p>
        <a href="{{ route('reportes.anual') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-file-excel"></i> Generar Reporte
        </a>
    </div>

    <div class="card" style="text-align: center; padding: 35px; border: none; background: linear-gradient(135deg, #E91E63 0%, #C2185B 100%); color: white; transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-user-md" style="font-size: 55px; margin-bottom: 20px; opacity: 0.95;"></i>
        <h3 style="margin: 15px 0; font-weight: 600; color: white;">Actividad por Usuario</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">Atenciones, pacientes y recursos por usuario</p>
        <a href="{{ route('reportes.por-usuario') }}" class="btn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-user-chart"></i> Generar Reporte
        </a>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection