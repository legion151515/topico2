@extends('layouts.app')

@section('page_title', 'Importar Pacientes')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-file-import"></i> Importar Estudiantes desde Excel Oficial</h3>
        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">
        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('errores') && count(session('errores')) > 0)
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Errores encontrados:</h5>
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    @foreach(session('errores') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Instrucciones --}}
        <div class="alert alert-info">
            <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Instrucciones para Importar</h4>
            <hr>
            <ol style="margin-bottom: 0;">
                <li><strong>Selecciona la categoría</strong> (Tecnológico o Pedagógico)</li>
                <li><strong>Selecciona la carrera/programa</strong> correspondiente</li>
                <li><strong>Selecciona el semestre</strong></li>
                <li><strong>Sube el archivo Excel oficial</strong> que recibes del instituto (Lista Oficial 2025-I)</li>
                <li>El sistema procesará automáticamente el Excel y extraerá los estudiantes</li>
            </ol>
        </div>

        {{-- Formulario de importación --}}
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-upload"></i> Importar Lista de Estudiantes</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pacientes.importar.procesar') }}" method="POST" enctype="multipart/form-data" id="form-importar">
                    @csrf

                    <div class="row">
                        {{-- Categoría --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categoria"><i class="fas fa-tag"></i> Categoría *</label>
                                <select id="categoria" name="categoria" class="form-control" required onchange="cargarCarreras()">
                                    <option value="">-- Selecciona una categoría --</option>
                                    <option value="Tecnológico">Tecnológico</option>
                                    <option value="Pedagógico">Pedagógico</option>
                                </select>
                            </div>
                        </div>

                        {{-- Carrera --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="carrera_id"><i class="fas fa-graduation-cap"></i> Carrera/Programa *</label>
                                <select id="carrera_id" name="carrera_id" class="form-control" required disabled>
                                    <option value="">-- Primero selecciona una categoría --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Semestre --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="semestre"><i class="fas fa-book"></i> Semestre *</label>
                                <select id="semestre" name="semestre" class="form-control" required disabled>
                                    <option value="">-- Primero selecciona una categoría --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="archivo">
                            <i class="fas fa-file-excel"></i> Archivo Excel Oficial (.xlsx):
                        </label>
                        <div class="custom-file">
                            <input
                                type="file"
                                class="custom-file-input @error('archivo') is-invalid @enderror"
                                id="archivo"
                                name="archivo"
                                accept=".xlsx,.xls"
                                required
                                onchange="updateFileName(this)"
                            >
                            <label class="custom-file-label" for="archivo" id="archivo-label">
                                Elegir archivo...
                            </label>
                            @error('archivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            Sube el archivo Excel oficial (Lista Oficial 2025-I) que recibes del instituto | Tamaño máximo: 5 MB
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Importante:</strong>
                        <ul style="margin-bottom: 0; margin-top: 10px;">
                            <li>Sube el archivo Excel <strong>SIN MODIFICAR</strong> tal como lo recibes del instituto</li>
                            <li>Los estudiantes con DNI duplicado serán omitidos automáticamente</li>
                            <li>El sistema detectará automáticamente si es Pedagógico o Tecnológico desde el archivo</li>
                            <li>Los nombres se procesarán automáticamente (apellidos y nombres separados)</li>
                        </ul>
                    </div>

                    <div class="form-group text-center" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-import"></i> Importar Estudiantes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Información adicional --}}
        <div class="card mt-4" style="border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <h5><i class="fas fa-lightbulb"></i> Notas Importantes</h5>
                <ul style="margin-bottom: 0;">
                    <li>El sistema lee automáticamente el formato de "Lista Oficial 2025-I" del instituto</li>
                    <li>No es necesario modificar ni convertir el archivo Excel</li>
                    <li>El DNI se extraerá automáticamente de las 8 columnas separadas</li>
                    <li>Los nombres y apellidos se separarán automáticamente</li>
                    <li>La edad se calculará por defecto como 18 años (puede editarse después)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const label = document.getElementById('archivo-label');
    const fileName = input.files[0]?.name || 'Elegir archivo...';
    label.textContent = fileName;
}

// Configuración de semestres
const configuracion = {
    'Tecnológico': {
        semestres: ['I', 'II', 'III', 'IV', 'V', 'VI']
    },
    'Pedagógico': {
        semestres: ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X']
    }
};

function cargarCarreras() {
    const categoria = document.getElementById('categoria').value;
    const carreraSelect = document.getElementById('carrera_id');
    const semestreSelect = document.getElementById('semestre');

    if (!categoria) {
        carreraSelect.innerHTML = '<option value="">-- Primero selecciona una categoría --</option>';
        carreraSelect.disabled = true;
        semestreSelect.innerHTML = '<option value="">-- Primero selecciona una categoría --</option>';
        semestreSelect.disabled = true;
        return;
    }

    // Cargar carreras desde el servidor
    carreraSelect.innerHTML = '<option value="">-- Cargando --</option>';
    carreraSelect.disabled = false;

    fetch(`/carreras/categoria/${categoria}`)
        .then(response => response.json())
        .then(data => {
            carreraSelect.innerHTML = '<option value="">-- Selecciona una carrera --</option>';
            data.forEach(carrera => {
                const option = document.createElement('option');
                option.value = carrera.id;
                option.textContent = carrera.nombre;
                carreraSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar carreras:', error);
            carreraSelect.innerHTML = '<option value="">Error al cargar carreras</option>';
        });

    // Cargar semestres
    semestreSelect.innerHTML = '<option value="">-- Selecciona un semestre --</option>';
    semestreSelect.disabled = false;

    if (configuracion[categoria]) {
        configuracion[categoria].semestres.forEach(sem => {
            const option = document.createElement('option');
            option.value = sem;
            option.textContent = sem;
            semestreSelect.appendChild(option);
        });
    }
}
</script>

<style>
.alert-heading {
    margin-bottom: 10px;
}

.custom-file-label::after {
    content: "Buscar";
}
</style>
@endsection
