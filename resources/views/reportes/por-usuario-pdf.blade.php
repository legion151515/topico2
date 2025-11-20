<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Actividad - {{ $usuario->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #E91E63;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #E91E63;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        .info-box {
            background-color: #ffe8f3;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border-left: 4px solid #E91E63;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 4px;
            font-size: 9px;
        }
        .info-box strong {
            color: #E91E63;
        }
        .stats-box {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-item {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 4px;
        }
        .stat-item:first-child {
            margin-right: 10px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #E91E63;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead {
            background-color: #E91E63;
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
        .badge-primary {
            background-color: #1D70B8;
            color: white;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        h3 {
            color: #E91E63;
            font-size: 12px;
            margin: 15px 0 8px 0;
            border-bottom: 1px solid #E91E63;
            padding-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ACTIVIDAD POR USUARIO</h1>
        <p>Sistema de Gestión y Registro de Atenciones - SIGRAB</p>
        <p>La Salle Urubamba</p>
        <p><strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td><strong>Usuario:</strong> {{ $usuario->name }}</td>
                <td><strong>Tipo:</strong> {{ ucfirst($usuario->tipo_usuario) }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Periodo:</strong> {{ $nombrePeriodo }}</td>
            </tr>
        </table>
    </div>

    <div class="stats-box">
        <div class="stat-item">
            <div class="stat-number">{{ $totalAtenciones }}</div>
            <div class="stat-label">Total de Atenciones</div>
        </div>
        <div class="stat-item" style="margin-left: 10px;">
            <div class="stat-number">{{ $pacientesUnicos }}</div>
            <div class="stat-label">Pacientes Únicos</div>
        </div>
    </div>

    <h3>Detalle de Atenciones Realizadas</h3>

    @if($atenciones->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Fecha</th>
                    <th style="width: 8%;">Hora</th>
                    <th style="width: 25%;">Paciente</th>
                    <th style="width: 35%;">Motivo de Consulta</th>
                    <th style="width: 12%;">Medicamentos</th>
                    <th style="width: 10%;">Hora Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($atenciones as $atencion)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($atencion->fecha)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($atencion->hora_entrada)->format('H:i') }}</td>
                        <td>
                            @if($atencion->paciente)
                                <strong>{{ $atencion->paciente->nombre }} {{ $atencion->paciente->apellido }}</strong>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $atencion->motivo->nombre ?? $atencion->motivo_otro ?? 'N/A' }}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-primary">{{ $atencion->medicamentos->count() }}</span>
                        </td>
                        <td>{{ $atencion->hora_salida ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #999;">
            No hay atenciones registradas para este usuario en el periodo seleccionado.
        </p>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema SIGRAB</p>
        <p>© {{ date('Y') }} La Salle Urubamba - Tópico Médico</p>
    </div>
</body>
</html>
