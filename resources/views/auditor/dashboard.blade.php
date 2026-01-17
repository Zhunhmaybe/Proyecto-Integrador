<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Auditor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="#">Panel Auditoría</a>
            <form method="POST" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h1 class="display-4 text-secondary">Bienvenido, Auditor</h1>
                <p class="lead text-muted">Supervisión y control de registros.</p>
                <hr class="my-4">
                <p>Aquí se mostrarán los registros de auditoría.</p>
            </div>
        </div>
    </div>

</body>

</html>