<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Estudiante - SIGRAB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F4F6F9 0%, #E8EDF2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .icon {
            font-size: 90px;
            background: linear-gradient(135deg, #17A2B8 0%, #20C997 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 25px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        h1 {
            color: #343A40;
            margin-bottom: 10px;
            font-size: 32px;
            font-weight: 700;
        }

        .subtitle {
            color: #6C757D;
            margin-bottom: 35px;
            font-size: 16px;
            font-weight: 400;
        }

        .welcome-box {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 35px;
            border: 1px solid rgba(23, 162, 184, 0.1);
        }

        .welcome-box p {
            color: #495057;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .welcome-box strong {
            font-size: 20px;
            color: #17A2B8;
        }

        .welcome-box ul {
            text-align: left;
            padding-left: 30px;
            color: #495057;
            line-height: 2.2;
            margin-top: 20px;
        }

        .welcome-box ul li {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .cta-box {
            background: linear-gradient(135deg, #17A2B8 0%, #20C997 100%);
            padding: 25px;
            border-radius: 16px;
            margin-top: 20px;
            color: white;
            box-shadow: 0 8px 20px rgba(23, 162, 184, 0.25);
        }

        .cta-box i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            padding: 15px 35px;
            background: linear-gradient(135deg, #17A2B8 0%, #20C997 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 8px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .user-info {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            border: 1px solid rgba(23, 162, 184, 0.2);
            display: inline-block;
            min-width: 300px;
        }

        .user-info strong {
            color: #17A2B8;
            font-size: 18px;
            font-weight: 600;
        }

        .user-info i {
            color: #20C997;
            margin-right: 8px;
        }

        .user-info div {
            margin: 8px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 26px;
            }

            .icon {
                font-size: 70px;
            }

            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-user-graduate"></i>
        </div>

        <h1>Portal de Estudiante</h1>
        <p class="subtitle">SIGRAB - Sistema de Gestión y Registro de Atenciones</p>

        <div class="user-info">
            <div>
                <i class="fas fa-user"></i> <strong>{{ Auth::user()->name }}</strong>
            </div>
            <div style="color: #6C757D; font-size: 14px; margin-top: 5px;">
                <i class="fas fa-id-card"></i> DNI: {{ Auth::user()->dni }}
            </div>
        </div>

        <div class="welcome-box">
            <p><strong><i class="fas fa-check-circle"></i> ¡Bienvenido!</strong></p>
            <p>
                Tu cuenta de estudiante está activa y lista para usar. Ahora tienes acceso a:
            </p>
            <ul>
                <li><i class="fas fa-check" style="color: #20C997;"></i> Agendar citas médicas online</li>
                <li><i class="fas fa-check" style="color: #20C997;"></i> Ver tus citas programadas</li>
                <li><i class="fas fa-check" style="color: #20C997;"></i> Recibir confirmación del personal médico</li>
                <li><i class="fas fa-check" style="color: #20C997;"></i> Cancelar citas cuando lo necesites</li>
            </ul>
        </div>

        <div class="cta-box">
            <i class="fas fa-calendar-check"></i>
            <p style="margin: 10px 0; font-size: 18px; font-weight: 600;">Sistema de Citas Disponible</p>
            <p style="margin: 0; opacity: 0.95; font-size: 14px;">¡Agenda tu cita médica ahora!</p>
        </div>

        <div style="margin-top: 30px;">
            <a href="{{ route('estudiante.citas.index') }}" class="btn">
                <i class="fas fa-calendar-check"></i> Mis Citas Médicas
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
