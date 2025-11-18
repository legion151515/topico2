@extends('layouts.app')

@section('page_title', 'Crear Nuevo Usuario del Personal')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Crear Nuevo Usuario del Personal</h3>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nombre Completo <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="dni">DNI <span style="color: red;">*</span></label>
                <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}" maxlength="8" required>
                <small class="form-text text-muted">8 dígitos</small>
                @error('dni')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico <span style="color: red;">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuario <span style="color: red;">*</span></label>
                <select name="tipo_usuario" id="tipo_usuario" class="form-control @error('tipo_usuario') is-invalid @enderror" required>
                    <option value="">Seleccionar tipo...</option>
                    <option value="admin" {{ old('tipo_usuario') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="medico" {{ old('tipo_usuario') == 'medico' ? 'selected' : '' }}>Médico</option>
                    <option value="enfermero" {{ old('tipo_usuario') == 'enfermero' ? 'selected' : '' }}>Enfermero(a)</option>
                    <option value="recepcionista" {{ old('tipo_usuario') == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                </select>
                @error('tipo_usuario')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña <span style="color: red;">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
