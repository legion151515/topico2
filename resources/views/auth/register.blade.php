<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - SIGRAB</title>
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

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1D70B8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #0D6EFD;
            transform: translateX(-5px);
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 50px 40px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #17A2B8 0%, #20C997 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(23, 162, 184, 0.3);
        }

        .logo-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: #343A40;
            margin-bottom: 8px;
        }

        .logo-section p {
            font-size: 14px;
            color: #6C757D;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #343A40;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6C757D;
            font-size: 18px;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #E8EDF2;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #F8F9FA;
        }

        .form-control:focus {
            outline: none;
            border-color: #17A2B8;
            background: white;
            box-shadow: 0 0 0 4px rgba(23, 162, 184, 0.1);
        }

        .form-control.error {
            border-color: #DC3545;
            background: #FFF5F5;
        }

        .error-message {
            color: #DC3545;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #E8EDF2;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
        }

        .strength-weak {
            width: 33%;
            background: #DC3545;
        }

        .strength-medium {
            width: 66%;
            background: #FFC107;
        }

        .strength-strong {
            width: 100%;
            background: #28A745;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 25px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #17A2B8;
            flex-shrink: 0;
        }

        .checkbox-wrapper label {
            font-size: 13px;
            color: #6C757D;
            cursor: pointer;
            line-height: 1.5;
        }

        .checkbox-wrapper label a {
            color: #1D70B8;
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-wrapper label a:hover {
            color: #0D6EFD;
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #17A2B8 0%, #20C997 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 201, 151, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
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

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #6C757D;
        }

        .login-link a {
            color: #1D70B8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #0D6EFD;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #F8D7DA;
            color: #DC3545;
            border-left: 4px solid #DC3545;
        }

        .alert-info {
            background: #D1ECF1;
            color: #17A2B8;
            border-left: 4px solid #17A2B8;
        }

        @media (max-width: 580px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .register-card {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <a href="{{ url('/') }}" class="back-link">
            ← Volver al inicio
        </a>

        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">✨</div>
                <h1>Crear Cuenta</h1>
                <p>Completa tus datos para registrarte</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <span>✕</span>
                    <div>
                        Por favor corrige los siguientes errores:
                        <ul style="margin: 5px 0 0 20px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') error @enderror"
                                placeholder="Juan Pérez"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                            >
                        </div>
                        @error('name')
                            <span class="error-message">
                                <span>✕</span>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🆔</span>
                            <input
                                type="text"
                                id="dni"
                                name="dni"
                                class="form-control @error('dni') error @enderror"
                                placeholder="12345678"
                                value="{{ old('dni') }}"
                                maxlength="8"
                                required
                            >
                        </div>
                        @error('dni')
                            <span class="error-message">
                                <span>✕</span>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') error @enderror"
                            placeholder="tu@email.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                        >
                    </div>
                    @error('email')
                        <span class="error-message">
                            <span>✕</span>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') error @enderror"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strength-bar"></div>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <span>✕</span>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    Crear mi cuenta
                </button>
            </form>

            <div class="divider">
                <span>o</span>
            </div>

            <div class="login-link">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'password-strength-bar';

            if (strength <= 1) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });
    </script>
</body>
</html>
