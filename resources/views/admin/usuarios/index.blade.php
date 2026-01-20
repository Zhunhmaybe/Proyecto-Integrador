<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f7fb;
            font-family:'Segoe UI',sans-serif;
            margin:0;
        }

        .wrapper{ display:flex; min-height:100vh; }

        /* SIDEBAR */
        .sidebar{
            width:260px;
            background:#0b4f79;
            color:#fff;
            padding:20px;
            display:flex;
            flex-direction:column;
        }
        .sidebar .logo{ text-align:center; margin-bottom:25px; }
        .sidebar .logo img{ width:110px; }
        .sidebar a{
            color:#cfe6f5;
            text-decoration:none;
            padding:10px 15px;
            border-radius:6px;
            display:block;
            margin-bottom:8px;
            font-size:14px;
        }
        .sidebar a.active,
        .sidebar a:hover{
            background:rgba(255,255,255,0.15);
            color:#fff;
        }
        .sidebar .user{
            margin-top:auto;
            border-top:1px solid rgba(255,255,255,0.2);
            padding-top:15px;
            font-size:13px;
        }

        /* CONTENT */
        .content{
            flex:1;
            padding:30px 40px;
            display:flex;
            flex-direction:column;
            align-items:center;
        }

        /* CARD HEADER (como tu ejemplo) */
        .page-card{
            max-width:980px;
            width:100%;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 18px 45px rgba(0,0,0,.12);
            background:#fff;
        }
        .page-hero{
            background:#b7d8ee;
            padding:22px 26px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }
        .page-hero .title{
            display:flex;
            gap:10px;
            align-items:flex-start;
        }
        .page-hero h2{
            margin:0;
            font-weight:800;
            color:#0b4f79;
            font-size:28px;
            line-height:1.1;
        }
        .page-hero p{
            margin:3px 0 0;
            color:#4b4b4b;
            font-size:14px;
        }

        /* tabla */
        .table-wrap{
            padding:22px 26px 28px;
        }
        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0;
        }
        thead th{
            font-weight:800;
            color:#000;
            padding:14px 14px;
            border-bottom:1px solid #e7e7e7;
            background:#f8f8f8;
        }
        tbody td{
            padding:14px 14px;
            border-bottom:1px solid #ececec;
            vertical-align:middle;
        }

        .badge-role{
            background:#0b4f79;
            color:#fff;
            border-radius:999px;
            padding:6px 12px;
            font-size:12px;
        }

        .btn-outline{
            border:1px solid #2b74ff;
            color:#2b74ff;
            border-radius:8px;
            padding:6px 12px;
            font-size:13px;
            background:#fff;
            text-decoration:none;
            display:inline-flex;
            gap:8px;
            align-items:center;
        }
        .btn-outline:hover{
            background:#f2f6ff;
            color:#2b74ff;
        }

        .empty{
            text-align:center;
            color:#777;
            padding:30px 10px;
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

        <a href="{{ route('admin.dashboard') }}">Mi perfil</a>
        <a href="{{ route('admin.pacientes.index') }}">Pacientes</a>
        <a href="{{ route('admin.doctores.index') }}">👤 Doctores</a>
        <a href="{{ route('admin.especialidades.index') }}">Especialidades</a>
        <a href="{{ route('admin.usuarios.index') }}" class="active">Usuarios</a>
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

    <!-- CONTENT -->
    <main class="content">

        <div class="page-card">

            <!-- HERO -->
            <div class="page-hero">
                <div class="title">
                    <div style="font-size:28px;">👥</div>
                    <div>
                        <h2>Usuarios</h2>
                        <p>Listado y gestión de usuarios del sistema</p>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-wrap">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $u)
                            <tr>
                                <td class="fw-semibold">{{ $u->nombre }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    <span class="badge-role">
                                        {{ $u->nombre_rol }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
