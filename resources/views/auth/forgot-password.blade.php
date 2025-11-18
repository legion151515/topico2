<x-guest-layout>
    <div class="info-text">
        ¿Olvidaste tu contraseña? No hay problema. Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
    </div>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>
            <i class="fas fa-envelope"></i> Enviar Enlace de Restablecimiento
        </x-primary-button>
    </form>
</x-guest-layout>
