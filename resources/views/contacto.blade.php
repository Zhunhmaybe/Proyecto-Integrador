<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos | Consultorio Danny</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/contacto.css'])
</head>

<body>


    <section class="py-5 bg-custom-contact">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="section-title">Contáctanos</h2>
                <p class="text-muted">
                    Estamos listos para atenderte. Comunícate con nosotros o visítanos.
                </p>
            </div>

            <div class="row g-4">


                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">📍</div>
                        <h5>Dirección</h5>
                        <p>
                            Av. Fray Vacas Galindo <br>
                            y Miguel Ángel Buonarroti
                        </p>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">📞</div>
                        <h5>Teléfonos</h5>
                        <p>
                            099 155 2320 <br>
                            095 899 5356
                        </p>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">📧</div>
                        <h5>Correo Electrónico</h5>
                        <p>
                            dalc-danels@hotmail.es
                        </p>
                    </div>
                </div>

            </div>


            <div class="row mt-5">
                <div class="col-md-8 mx-auto">
                    <div class="contact-card">
                        <h4 class="section-title text-center mb-4">Envíanos un mensaje</h4>

                        <form>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" placeholder="Tu nombre">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" placeholder="Tu correo">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mensaje</label>
                                <textarea class="form-control" rows="4" placeholder="Escribe tu mensaje"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Enviar mensaje
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>


    <footer class="text-center">
        <div class="container">
            <p class="mb-0">
                © 2025 Consultorio Odontológico Danny | Contáctanos
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>