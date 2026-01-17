<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificación 2FA</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/auth/two-factor.css'])
</head>

<body>


    <div class="background-container">
        <div class="wave-decoration">
            <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path
                    fill="#ffffff"
                    d="
                    M0,160
                    C240,160 480,240 720,240
                    C960,240 1200,160 1440,160
                    L1440,320 L0,320 Z
                ">
                </path>
            </svg>
        </div>
    </div>


    <div class="verify-container">
        <div class="verify-card">


            <div class="icon-circle">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C7 2 4 4 4 4v6c0 5.55 3.84 9.74 8 12
                         4.16-2.26 8-6.45 8-12V4s-3-2-8-2z" />
                    <path d="M12 11a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z" />
                </svg>
            </div>

            <h2>Verificación de Dos Factores</h2>
            <p class="text-muted" style="font-size:14px;">
                Ingresa el código de 6 dígitos enviado a tu email
            </p>

            {{-- MENSAJE ÉXITO --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- ERRORES --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form method="POST" action="{{ route('2fa.verify.post') }}" id="verify-form">
                @csrf

                <div class="mb-4">
                    <input type="text"
                        class="form-control code-input @error('code') is-invalid @enderror"
                        id="code"
                        name="code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        placeholder="000000"
                        autocomplete="off"
                        required
                        autofocus>
                    @error('code')
                    <div class="invalid-feedback text-center">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-verify mb-3" id="verify-btn">
                    Verificar
                </button>
            </form>


            <form method="POST" action="{{ route('2fa.resend') }}" id="resend-form">
                @csrf
                <button type="submit" class="btn btn-link link-action" id="resend-btn">
                    ¿No recibiste el código? Reenviar
                </button>
            </form>

            <a href="{{ route('login') }}" class="link-login d-block mt-2">
                Volver al inicio de sesión
            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Solo permitir números
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Prevenir múltiples envíos del formulario de verificación
        const verifyForm = document.getElementById('verify-form');
        const verifyBtn = document.getElementById('verify-btn');
        let isSubmitting = false;

        verifyForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';
        });

        // Prevenir múltiples envíos del botón reenviar
        const resendForm = document.getElementById('resend-form');
        const resendBtn = document.getElementById('resend-btn');
        let isResending = false;

        resendForm.addEventListener('submit', function(e) {
            if (isResending) {
                e.preventDefault();
                return false;
            }
            isResending = true;
            resendBtn.disabled = true;
            resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Reenviando...';
        });
    </script>

</body>

</html>