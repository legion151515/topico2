@extends('layouts.app')

@section('page_title', 'Editar Usuario del Personal')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Usuario: {{ $usuario->name }}</h3>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre Completo <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="dni">DNI <span style="color: red;">*</span></label>
                <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni', $usuario->dni) }}" maxlength="8" required>
                <small class="form-text text-muted">8 dígitos</small>
                @error('dni')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico <span style="color: red;">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuario <span style="color: red;">*</span></label>
                <select name="tipo_usuario" id="tipo_usuario" class="form-control @error('tipo_usuario') is-invalid @enderror" required>
                    <option value="">Seleccionar tipo...</option>
                    <option value="admin" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="medico" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'medico' ? 'selected' : '' }}>Médico</option>
                    <option value="enfermero" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'enfermero' ? 'selected' : '' }}>Enfermero(a)</option>
                    <option value="recepcionista" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                </select>
                @error('tipo_usuario')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <hr>
            <p style="margin-bottom: 20px; color: #666;">
                <i class="fas fa-info-circle"></i> <strong>Cambiar contraseña (opcional):</strong><br>
                Deja estos campos vacíos si no deseas cambiar la contraseña.
            </p>

            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                <small class="form-text text-muted">Mínimo 8 caracteres (solo si deseas cambiar la contraseña)</small>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
