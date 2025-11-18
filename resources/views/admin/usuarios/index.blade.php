@extends('layouts.app')

@section('page_title', 'Gestión de Usuarios del Personal')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Gestión de Usuarios del Personal</h3>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nuevo Usuario
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

        @if($usuarios->count() > 0)
            <div style="margin-bottom: 15px;">
                <strong>Total de usuarios:</strong> <span class="badge badge-primary badge-lg">{{ $usuarios->count() }}</span>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Nombre</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-id-card"></i> DNI</th>
                            <th><i class="fas fa-user-tag"></i> Tipo</th>
                            <th><i class="fas fa-calendar-plus"></i> Fecha Creación</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td><strong>{{ $usuario->id }}</strong></td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->dni }}</td>
                                <td>
                                    @if($usuario->tipo_usuario === 'admin')
                                        <span class="badge badge-danger">Administrador</span>
                                    @elseif($usuario->tipo_usuario === 'medico')
                                        <span class="badge badge-primary">Médico</span>
                                    @elseif($usuario->tipo_usuario === 'enfermero')
                                        <span class="badge badge-success">Enfermero(a)</span>
                                    @elseif($usuario->tipo_usuario === 'recepcionista')
                                        <span class="badge badge-info">Recepcionista</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">(Tu cuenta)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-users" style="font-size: 60px; color: #ccc;"></i>
                <p style="margin-top: 20px; color: #666; font-size: 18px;">
                    No hay usuarios del personal registrados
                </p>
                <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                    <i class="fas fa-plus"></i> Crear Primer Usuario
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
