<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios | Consultorio Danny</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .section-title {
            color: #0f4c75;
            font-weight: bold;
        }

        .service-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .service-icon {
            font-size: 45px;
            color: #0f4c75;
        }

        .service-card h5 {
            font-weight: bold;
            margin-top: 15px;
        }

        .service-card p {
            font-size: 0.95rem;
            color: #555;
        }
    </style>
</head>
<body>

<!-- 🔷 SERVICIOS -->
<section class="py-5" style="background-color:#ffffff;">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">Nuestros Servicios</h2>
            <p class="mt-2 text-muted">
                Brindamos soluciones odontológicas integrales con calidad,
                seguridad y atención personalizada.
            </p>
        </div>

        <div class="row g-4">

            <!-- SERVICIO 1 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">🦷</div>
                    <h5>Odontología General</h5>
                    <p>
                        Diagnóstico, limpieza dental, restauraciones y
                        tratamientos preventivos para el cuidado de tu salud bucal.
                    </p>
                </div>
            </div>

            <!-- SERVICIO 2 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">😁</div>
                    <h5>Estética Dental</h5>
                    <p>
                        Blanqueamiento dental, carillas estéticas y diseño
                        de sonrisa para mejorar tu imagen y confianza.
                    </p>
                </div>
            </div>

            <!-- SERVICIO 3 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">🛡️</div>
                    <h5>Ortodoncia</h5>
                    <p>
                        Tratamientos con brackets y alineadores para corregir
                        la posición dental y mejorar la mordida.
                    </p>
                </div>
            </div>

            <!-- SERVICIO 4 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">🪥</div>
                    <h5>Profilaxis Dental</h5>
                    <p>
                        Limpieza profunda para prevenir caries, gingivitis
                        y mantener una sonrisa saludable.
                    </p>
                </div>
            </div>

            <!-- SERVICIO 5 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">🦠</div>
                    <h5>Endodoncia</h5>
                    <p>
                        Tratamiento de conductos para eliminar infecciones
                        y conservar las piezas dentales.
                    </p>
                </div>
            </div>

            <!-- SERVICIO 6 -->
            <div class="col-md-4">
                <div class="card service-card text-center p-4">
                    <div class="service-icon">🦷</div>
                    <h5>Prótesis Dental</h5>
                    <p>
                        Rehabilitación oral mediante prótesis fijas o removibles
                        para recuperar funcionalidad y estética.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
