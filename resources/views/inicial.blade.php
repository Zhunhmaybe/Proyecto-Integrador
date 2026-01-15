<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorio Odontológico Danny</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
body {
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar {
    padding: 10px 0;
}

.navbar-brand img {
    height: 50px;
}

/* HERO */
.hero {
    background:#3282b8 ;
    color: white;
    padding: 80px 0; /* antes estaba muy alto */
}

.hero h1 {
    font-size: 2.8rem;
    font-weight: bold;
}

.hero p {
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto;
}

/* BOTÓN */
.hero .btn {
    padding: 12px 30px;
    font-size: 1.1rem;
    border-radius: 30px;
}

/* TÍTULOS */
.section-title {
    color: #0f4c75;
    font-weight: bold;
    margin-bottom: 40px;
}

/* SERVICIOS */
.service-icon {
    font-size: 42px;
    color: #0f4c75;
}

section.py-5 {
    padding: 60px 0 !important;
}

/* FOOTER */
footer {
    background-color: #0f4c75;
    color: white;
    padding: 15px 0;
}

    </style>
</head>
<body>

<!-- 🔷 NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm" style="background-color:#0f4c75;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="/images/logo-danny.png" alt="Logo Danny">
            <span class="ms-2 fw-bold text-white">Consultorio Danny</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Ingresar</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('servicios') }}">Servicios</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('contacto') }}">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>


<!-- 🔷 HERO -->
<section class="hero text-center">
    <div class="container">
        <h1>Tu sonrisa, nuestra prioridad</h1>
        <p class="mt-3">
            En el Consultorio Odontológico Danny brindamos atención profesional,
            humana y de calidad para el cuidado integral de tu salud dental.
        </p>
        <a href="#" class="btn btn-light mt-4">Agendar Cita</a>
    </div>
</section>


<!-- 🔷 SERVICIOS -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="section-title mb-4">Nuestros Servicios</h2>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="service-icon">🦷</div>
                <h5 class="mt-3">Odontología General</h5>
                <p>Diagnóstico, limpieza dental y tratamientos preventivos.</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="service-icon">😁</div>
                <h5 class="mt-3">Estética Dental</h5>
                <p>Blanqueamiento, carillas y diseño de sonrisa.</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="service-icon">🛡️</div>
                <h5 class="mt-3">Ortodoncia</h5>
                <p>Brackets y tratamientos para una correcta alineación dental.</p>
            </div>
        </div>
    </div>
</section>

<!-- 🔷 NOSOTROS -->
<section class="py-5" style="background-color:#f4f9fc;">
    <div class="container">
        <div class="row align-items-center">

            <!-- TEXTO -->
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="section-title">¿Quiénes Somos?</h2>

                <p class="mt-3">
                    En el <strong>Consultorio Odontológico Danny</strong> nos
                    especializamos en brindar atención dental integral,
                    combinando experiencia profesional, tecnología moderna
                    y un trato humano y cercano.
                </p>

                <p>
                    Nuestro compromiso es cuidar tu sonrisa mediante
                    tratamientos seguros, personalizados y orientados al
                    bienestar y la confianza de cada paciente.
                </p>

                <ul class="mt-3">
                    <li>✔ Atención personalizada</li>
                    <li>✔ Tecnología odontológica moderna</li>
                    <li>✔ Profesionales calificados</li>
                    <li>✔ Ambiente seguro y confortable</li>
                </ul>
            </div>

            <!-- IMAGEN / LOGO -->
            <div class="col-md-6 text-center">
                <div style="
                    background:#0f4c75;
                    padding:30px;
                    border-radius:15px;
                    box-shadow:0 10px 30px rgba(0,0,0,0.1);
                    display:inline-block;
                ">
                    <img src="/images/logo-danny.png"
                         alt="Consultorio Danny"
                         class="img-fluid"
                         style="max-width:220px;">
                </div>
            </div>

        </div>
    </div>
</section>


<!-- 🔷 FOOTER -->
<footer class="text-center">
    <div class="container">
        <p class="mb-0">
            © 2025 Consultorio Odontológico Danny | Cuidando sonrisas
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
