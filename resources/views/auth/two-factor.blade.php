<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificación 2FA</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
            overflow-x: hidden;
        }

        /* ===== FONDO ===== */
        .background-container {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #2c7fb8, #4a9fd8, #6bb9e8);
            z-index: -1;
        }

        /* ===== OLA ===== */
        .wave-decoration {
            position: absolute;
            top: 45%;
            left: 0;
            width: 100%;
            height: 55%;
            overflow: hidden;
        }

        .wave-decoration svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* ===== CONTENEDOR ===== */
        .verify-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 40px 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 12px 35px rgba(0,0,0,0.18);
            z-index: 2;
            text-align: center;
        }

        /* ===== ICONO ===== */
        .icon-circle {
            width: 70px;
            height: 70px;
            border: 3px solid #f4c430;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 15px rgba(244,196,48,.25);
        }

        .icon-circle svg {
            width: 34px;
            height: 34px;
            stroke: #f4c430;
            fill: none;
            stroke-width: 2;
        }

        h2 {
            color: #1a5490;
            font-weight: 700;
        }

        .code-input {
            font-size: 2rem;
            text-align: center;
            letter-spacing: 1rem;
            font-weight: bold;
            border-radius: 10px;
        }

        .btn-verify {
            width: 100%;
            background: linear-gradient(135deg, #1a5490, #2c7fb8);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-verify:hover {
            box-shadow: 0 8px 18px rgba(44,127,184,.3);
            transform: translateY(-1px);
        }

        .link-action {
            font-size: 13px;
            color: #2c7fb8;
            text-decoration: none;
            font-weight: 600;
        }

        .link-login {
            font-size: 13px;
            color: #f4c430;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- FONDO + OLA -->
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

<!-- VERIFICACIÓN 2FA -->
<div class="verify-container">
    <div class="verify-card">

        <!-- ICONO -->
        <div class="icon-circle">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C7 2 4 4 4 4v6c0 5.55 3.84 9.74 8 12
                         4.16-2.26 8-6.45 8-12V4s-3-2-8-2z"/>
                <path d="M12 11a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
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

        <!-- FORMULARIO FUNCIONAL -->
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

        <!-- REENVIAR -->
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
    codeInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Prevenir múltiples envíos del formulario de verificación
    const verifyForm = document.getElementById('verify-form');
    const verifyBtn = document.getElementById('verify-btn');
    let isSubmitting = false;

    verifyForm.addEventListener('submit', function (e) {
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

    resendForm.addEventListener('submit', function (e) {
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
