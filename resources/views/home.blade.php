<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        /* ===== LAYOUT ===== */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #0b4f79;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .sidebar .logo img {
            width: 110px;
        }

        .sidebar a {
            color: #cfe6f5;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .sidebar .user {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 15px;
            font-size: 13px;
        }

        /* ===== CONTENT ===== */
.content {
    flex: 1;
    padding: 30px 40px;

    display: flex;
    flex-direction: column;
    align-items: center;  
}


        /* ===== PROFILE CARD ===== */
.profile-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    overflow: hidden;
    max-width: 900px;
    width: 100%;            /* 👈 ocupa el ancho permitido */
}


        .profile-header {
            background: #b7d8ee;
            padding: 22px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #ddd;
            border: 3px solid #f2b705;
            position: relative;
        }

        .avatar::after {
            content: "+";
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0b4f79;
            color: #fff;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .profile-header small {
            color: #555;
        }

        .profile-body {
            padding: 30px;
        }

        .profile-body h5 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 13px;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-edit {
            background: #e0b23f;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 40px;
            font-size: 14px;
        }

        .btn-edit:hover {
            background: #c89b2d;
        }
        .action-buttons {
    display: flex;
    justify-content: center;
    gap: 12px; /* espacio entre Editar y 2FA */
    margin-top: 10px;
}

.btn-2fa {
    background: #e0b23f;
    color: white;
    border-radius: 20px;
    width: 120px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.btn-2fa:hover {
    background: #c89b2d;
    color: #fff;
}

    </style>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="/images/logo-danny.png" alt="Logo Danny">
        </div>

        <a href="#" class="active">👤 Mi Perfil</a>
        <a href="{{ route('citas.create') }}">📅 Citas</a>
        <a href="{{ route('pacientes.index') }}">👥 Pacientes</a>


        <div class="user">
            <strong>{{ Auth::user()->nombre }}</strong><br>
            <small>{{Auth::user()->nombre_rol}}</small>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <h3 class="fw-bold mb-4">Mi perfil</h3>

        <div class="profile-card">

            <div class="profile-header">
                <div class="avatar"></div>
                <div>
                    <h4>{{ Auth::user()->nombre }}</h4>
                    <small>{{Auth::user()->nombre_rol}}</small>
                </div>
            </div>

            <div class="profile-body">
                <h5>Información Personal:</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo:</label>
                        <input type="text" class="form-control"
                               value="{{ Auth::user()->nombre }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control"
                               value="{{ Auth::user()->email }}" disabled>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" class="form-control"
                               value="{{ Auth::user()->tel ?? 'No registrado' }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado:</label>
                        <input type="text" class="form-control"
                               value="{{ Auth::user()->nombre_estado ?? 'No registrada' }}" disabled>
                    </div>
                </div>

                <div class="text-center">

<div class="action-buttons">
    <button class="btn-edit">Editar</button>
    
    <a href="{{ route('profile.2fa') }}"
       class="btn-2fa"
       title="Seguridad 2FA">
        🔐2FA
    </a>
</div>


            </div>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@if (session('session_expired'))
<div class="modal fade show" id="sessionExpiredModal"
     tabindex="-1"
     style="display:block; background:rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Sesión cerrada</h5>
            </div>

            <div class="modal-body text-center">
                <p>
                    Tu sesión se cerró automáticamente por
                    <strong>inactividad de 2 minutos</strong>.
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button"
                        class="btn btn-danger"
                        onclick="window.location.href='{{ route('login') }}'">
                    Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
@endif

</body>
</html>
