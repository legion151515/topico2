<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Estudiante - SIGRAB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .welcome-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .welcome-box p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .user-info {
            background: #e7e9fc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .user-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-user-graduate"></i>
        </div>

        <h1>Bienvenido al Portal de Estudiante</h1>
        <p class="subtitle">SIGRAB - Sistema de Gestión y Registro de Atenciones</p>

        <div class="user-info">
            <i class="fas fa-user"></i> <strong>{{ Auth::user()->name }}</strong><br>
            <i class="fas fa-id-card"></i> DNI: {{ Auth::user()->dni }}
        </div>

        <div class="welcome-box">
            <p><strong>🎉 ¡Registro exitoso!</strong></p>
            <p>
                Tu cuenta de estudiante ha sido creada correctamente.
                Próximamente podrás:
            </p>
            <ul style="text-align: left; padding-left: 40px; color: #555; line-height: 2;">
                <li>📅 Agendar citas médicas online</li>
                <li>👀 Ver tus citas programadas</li>
                <li>📋 Consultar tu historial clínico</li>
                <li>🔔 Recibir notificaciones de tus citas</li>
            </ul>
            <p style="margin-top: 20px; color: #e74c3c;">
                <i class="fas fa-tools"></i> <strong>Sistema en desarrollo</strong><br>
                Esta funcionalidad estará disponible próximamente.
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>
