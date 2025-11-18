<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Anual - {{ $anio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        .info-box {
            background-color: #e3f2fd;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead {
            background-color: #2ecc71;
            color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 4px;
            text-align: left;
        }
        th {
            font-weight: bold;
            font-size: 9px;
        }
        td {
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 7px;
            display: inline-block;
        }
        .badge-info {
            background-color: #3498db;
            color: white;
        }
        .badge-success {
            background-color: #2ecc71;
            color: white;
        }
        .badge-warning {
            background-color: #f39c12;
            color: white;
        }
        .badge-secondary {
            background-color: #95a5a6;
            color: white;
        }
        .medicamentos-list {
            margin: 0;
            padding-left: 12px;
            font-size: 7px;
        }
        .medicamentos-list li {
            margin: 2px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE ANUAL DE ATENCIONES - {{ $anio }}</h1>
        <p>Sistema de Gestión y Registro de Atenciones - SIGRAB</p>
        <p>La Salle Urubamba</p>
        <p><strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if($atenciones->count() > 0)
        <div class="info-box">
            <strong>Total de atenciones registradas:</strong> {{ $atenciones->count() }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">#</th>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 7%;">Hora</th>
                    <th style="width: 10%;">Carrera</th>
                    <th style="width: 6%;">Sem.</th>
                    <th style="width: 6%;">Edad</th>
                    <th style="width: 30%;">Motivo de Consulta</th>
                    <th style="width: 30%;">Medicamentos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($atenciones as $index => $atencion)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($atencion->fecha_atencion)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($atencion->hora_entrada)->format('H:i') }}</td>
                        <td>
                            @if($atencion->paciente && $atencion->paciente->carrera)
                                <span class="badge badge-info">{{ $atencion->paciente->carrera->acronimo }}</span>
                            @elseif($atencion->paciente && $atencion->paciente->nivel)
                                @if($atencion->paciente->nivel->nivel_escuela)
                                    <span class="badge badge-success">Escuela</span>
                                @elseif($atencion->paciente->nivel->otros_especificacion)
                                    <span class="badge badge-warning">Otros</span>
                                @else
                                    N/A
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($atencion->paciente && $atencion->paciente->semestre)
                                {{ $atencion->paciente->semestre }}°
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($atencion->paciente && $atencion->paciente->fecha_nacimiento)
                                {{ \Carbon\Carbon::parse($atencion->paciente->fecha_nacimiento)->age }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($atencion->motivo)
                                {{ $atencion->motivo->nombre }}
                            @elseif($atencion->motivo_otro)
                                {{ $atencion->motivo_otro }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($atencion->medicamentos && $atencion->medicamentos->count() > 0)
                                <ul class="medicamentos-list">
                                    @foreach($atencion->medicamentos as $med)
                                        <li>{{ $med->nombre }} ({{ $med->pivot->cantidad }})</li>
                                    @endforeach
                                </ul>
                            @else
                                Sin medicamentos
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No hay atenciones registradas para el año {{ $anio }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema SIGRAB</p>
        <p>© {{ date('Y') }} La Salle Urubamba - Tópico Médico</p>
    </div>
</body>
</html>
