<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIGRAB') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #F4F6F9 0%, #E8EDF2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
                padding: 20px;
            }

            .auth-container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                padding: 40px;
                width: 100%;
                max-width: 480px;
            }

            .auth-logo {
                text-align: center;
                margin-bottom: 30px;
            }

            .auth-logo-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%);
                border-radius: 20px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
            }

            .auth-logo-icon i {
                font-size: 40px;
                color: white;
            }

            .auth-title {
                color: #343A40;
                font-size: 24px;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .auth-subtitle {
                color: #6C757D;
                font-size: 14px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                margin-bottom: 8px;
                color: #343A40;
                font-weight: 500;
                font-size: 14px;
            }

            .form-input {
                width: 100%;
                padding: 12px 15px;
                border: 1px solid #ddd;
                border-radius: 10px;
                font-size: 14px;
                transition: all 0.3s ease;
                font-family: 'Inter', sans-serif;
            }

            .form-input:focus {
                outline: none;
                border-color: #1D70B8;
                box-shadow: 0 0 0 4px rgba(29, 112, 184, 0.1);
            }

            .btn-primary {
                width: 100%;
                padding: 14px 24px;
                background: linear-gradient(135deg, #1D70B8 0%, #0D6EFD 100%);
                color: white;
                border: none;
                border-radius: 10px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(29, 112, 184, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(29, 112, 184, 0.4);
            }

            .error-message {
                color: #DC3545;
                font-size: 12px;
                margin-top: 5px;
            }

            .success-message {
                background: linear-gradient(135deg, rgba(32, 201, 151, 0.1) 0%, rgba(23, 162, 184, 0.1) 100%);
                border-left: 4px solid #20C997;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                color: #155724;
                font-size: 14px;
            }

            .info-text {
                color: #6C757D;
                font-size: 14px;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .back-link {
                display: inline-block;
                margin-top: 20px;
                color: #1D70B8;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .back-link:hover {
                color: #0D6EFD;
                transform: translateX(-3px);
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="auth-title">SIGRAB</div>
                <div class="auth-subtitle">Sistema de Gestión y Registro de Atenciones - Bienestar</div>
            </div>

            {{ $slot }}

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('welcome') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </div>
    </body>
</html>
