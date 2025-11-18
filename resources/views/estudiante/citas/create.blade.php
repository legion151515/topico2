@extends('layouts.estudiante')

@section('page_title', 'Agendar Nueva Cita')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Agendar Nueva Cita Médica</h3>
        <a href="{{ route('estudiante.citas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <div style="background: linear-gradient(135deg, rgba(23, 162, 184, 0.08) 0%, rgba(32, 201, 151, 0.08) 100%); padding: 25px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #17A2B8;">
            <h4 style="margin: 0 0 15px 0; color: #17A2B8; font-weight: 600;">
                <i class="fas fa-info-circle"></i> Información Importante
            </h4>
            <ul style="margin: 0; padding-left: 20px; line-height: 2; color: #495057;">
                <li><strong style="color: #17A2B8;">Horario de atención:</strong> Lunes a Sábado de 8:00 AM a 1:00 PM</li>
                <li><strong style="color: #17A2B8;">Domingos:</strong> No hay atención</li>
                <li>Tu cita debe ser <strong>confirmada</strong> por el personal médico</li>
                <li>Recibirás una notificación cuando tu cita sea aprobada o rechazada</li>
                <li>Puedes cancelar tu cita en cualquier momento</li>
            </ul>
        </div>

        <form action="{{ route('estudiante.citas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="fecha">Fecha de la Cita <span style="color: red;">*</span></label>
                <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha') }}" min="{{ date('Y-m-d') }}" required>
                <small class="form-text text-muted">Selecciona la fecha deseada (debe ser de hoy en adelante, no domingos)</small>
                @error('fecha')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="hora">Hora de la Cita <span style="color: red;">*</span></label>
                <input type="time" name="hora" id="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora') }}" min="08:00" max="13:00" required>
                <small class="form-text text-muted">Horario de atención: 08:00 AM - 01:00 PM</small>
                @error('hora')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="motivo">Motivo de la Consulta <span style="color: red;">*</span></label>
                <textarea name="motivo" id="motivo" rows="4" class="form-control @error('motivo') is-invalid @enderror" required>{{ old('motivo') }}</textarea>
                <small class="form-text text-muted">Describe brevemente el motivo de tu consulta (mínimo 10 caracteres, máximo 500)</small>
                @error('motivo')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="observaciones_estudiante">Observaciones Adicionales (Opcional)</label>
                <textarea name="observaciones_estudiante" id="observaciones_estudiante" rows="3" class="form-control @error('observaciones_estudiante') is-invalid @enderror">{{ old('observaciones_estudiante') }}</textarea>
                <small class="form-text text-muted">Información adicional que consideres relevante (máximo 1000 caracteres)</small>
                @error('observaciones_estudiante')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Agendar Cita
                </button>
                <a href="{{ route('estudiante.citas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Deshabilitar domingos en el selector de fecha
    document.getElementById('fecha').addEventListener('input', function() {
        var fecha = new Date(this.value + 'T00:00:00');
        if (fecha.getDay() === 0) {
            alert('No se pueden agendar citas los domingos. Por favor selecciona otro día.');
            this.value = '';
        }
    });
</script>
@endsection
