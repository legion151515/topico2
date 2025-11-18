<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGRAB - Sistema de Gestión y Registro de Atenciones - Bienestar</title>
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
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .left-section {
            background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%);
            padding: 60px 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .logo-text h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logo-text p {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 300;
        }

        .welcome-text h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .welcome-text p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 15px;
        }

        .features {
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: #17A2B8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 14px;
            font-weight: 500;
        }

        .right-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-header h3 {
            font-size: 28px;
            font-weight: 700;
            color: #343A40;
            margin-bottom: 10px;
        }

        .auth-header p {
            font-size: 14px;
            color: #6C757D;
        }

        .auth-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: #1D70B8;
            color: white;
            box-shadow: 0 4px 15px rgba(29, 112, 184, 0.3);
        }

        .btn-primary:hover {
            background: #0D6EFD;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #1D70B8;
            border: 2px solid #1D70B8;
        }

        .btn-secondary:hover {
            background: #F4F6F9;
            border-color: #0D6EFD;
            color: #0D6EFD;
            transform: translateY(-2px);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #E8EDF2;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #6C757D;
            font-size: 14px;
            position: relative;
        }

        .info-box {
            margin-top: 30px;
            padding: 20px;
            background: #F4F6F9;
            border-left: 4px solid #17A2B8;
            border-radius: 8px;
        }

        .info-box h4 {
            font-size: 14px;
            font-weight: 600;
            color: #343A40;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box p {
            font-size: 13px;
            color: #6C757D;
            line-height: 1.5;
        }

        .footer-info {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6C757D;
        }

        @media (max-width: 968px) {
            .welcome-card {
                grid-template-columns: 1fr;
            }

            .left-section {
                padding: 40px 30px;
            }

            .right-section {
                padding: 40px 30px;
            }

            .welcome-text h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-card">
            <!-- Sección Izquierda - Información -->
            <div class="left-section">
                <div class="logo-container">
                    <div class="logo-icon">🏥</div>
                    <div class="logo-text">
                        <h1>SIGRAB</h1>
                        <p>Instituto "La Salle" Urubamba</p>
                    </div>
                </div>

                <div class="welcome-text">
                    <h2>Sistema de Gestión y Registro de Atenciones - Bienestar</h2>
                    <p>Plataforma integral para el manejo eficiente de historias clínicas, citas médicas y atención de pacientes.</p>
                    <p>Diseñado especialmente para el Tópico de Salud de nuestro instituto.</p>
                </div>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">📋</div>
                        <div class="feature-text">Gestión completa de historias clínicas</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📅</div>
                        <div class="feature-text">Control de citas y atenciones médicas</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">💊</div>
                        <div class="feature-text">Inventario automático de medicamentos</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <div class="feature-text">Reportes y estadísticas en tiempo real</div>
                    </div>
                </div>
            </div>

            <!-- Sección Derecha - Autenticación -->
            <div class="right-section">
                <div class="auth-header">
                    @auth
                        <h3>¡Bienvenido de vuelta!</h3>
                        <p>Ya tienes una sesión activa</p>
                    @else
                        <h3>Bienvenido de vuelta</h3>
                        <p>Accede a tu cuenta para continuar</p>
                    @endauth
                </div>

                <div class="auth-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <span>📊</span>
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <span>🔐</span>
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                            <div class="divider">
                                <span>o</span>
                            </div>

                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                <span>✨</span>
                                Registrarse como Estudiante
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="info-box">
                    <h4>
                        <span>ℹ️</span>
                        Acceso Autorizado
                    </h4>
                    <p>Este sistema es de uso exclusivo para el personal autorizado del Tópico de Salud y estudiantes registrados. Para solicitar acceso, contacta con el administrador del sistema.</p>
                </div>

                <div class="footer-info">
                    <p>© {{ date('Y') }} SIGRAB - Instituto "La Salle" Urubamba</p>
                    <p style="margin-top: 5px;">Sistema de Gestión y Registro de Atenciones</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
