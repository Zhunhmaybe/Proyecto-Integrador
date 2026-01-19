<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/admin/admin-panel.css'])
</head>

<body class="bg-light">

    <div class="wrapper">

        <aside class="sidebar">
            <div class="logo">
                <img src="/images/logo-danny.png" alt="Logo Danny">
            </div>

            <a href="{{ route('admin.dashboard') }}" class="active">Mi perfil</a>
            <a href="{{ route('admin.pacientes.index') }}" >Pacientes</a>
            <a href="{{ route('admin.doctores.index') }}" >👤 Doctores</a>
            <a href="{{ route('admin.especialidades.index') }}" >Especialidades</a>
            <a href="{{ route('admin.usuarios.index') }}" >Usuarios</a>
            <a href="{{ route('admin.citas.create') }}">📅 Citas</a>
            <a href="{{ route('admin.roles.index') }}">Roles</a>


            <div class="user">
                <strong>{{ Auth::user()->nombre }}</strong><br>
                <small>{{ Auth::user()->nombre_rol }}</small>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100">Cerrar Sesión</button>
                </form>
            </div>
        </aside>

        <main class="content">
            <h3 class="fw-bold mb-4">Administrador </h3>

            <div class="profile-card">

                <div class="profile-header">
                    <div class="avatar"></div>
                    <div>
                        <h4>{{ Auth::user()->nombre }}</h4>
                        <small>{{ Auth::user()->nombre_rol }}</small>
                    </div>
                </div>

                <div class="profile-body">
                    <h5>Información Personal:</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->nombre }}" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico:</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" class="form-control"
                                value="{{ Auth::user()->tel ?? 'No registrado' }}" disabled>
                        </div>

                    </div>

                    <div class="text-center">

                        <div class="action-buttons">
                            <a href="{{ route('perfil.edit') }}" class="btn btn-gold">
                                Editar
                            </a>


                            <a href="{{ route('profile.2fa') }}" class="btn-2fa" title="Seguridad 2FA">
                                🔐2FA
                            </a>
                        </div>


                    </div>

                </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if (session('session_expired'))
        <div class="modal fade show session-modal-overlay" id="sessionExpiredModal" tabindex="-1">
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
                        <button type="button" class="btn btn-danger"
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
