<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado</title>
    <link rel="stylesheet"  href="{{ asset('css/style.css') }}">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>

<div id="contenido-pdf">
    <div id="pagina">
        
        <h1>FORMULARIO DE CONSENTIMIENTO INFORMADO</h1>

        <table class="no-corte">
            <tr>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Nombres</th>
                <th>Servicio</th>
                <th>Sala</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
            <tr>
                <td><input type="text" id="apellidoPaterno"></td>
                <td><input type="text" id="apellidoMaterno"></td>
                <td><input type="text" id="nombres"></td>
                <td><p>Odontología</p></td>
                <td><input type="text" id="sala" value="Consultorio 1"></td>
                <td><input type="date" id="fecha"></td>
                <td><input type="time" id="hora"></td>
            </tr>
        </table>

        <div class="section-title">CONSENTIMIENTO INFORMADO DEL PACIENTE</div>
        
        <table class="no-corte">
            <tr>
                <th style="width: 80%;">Declaración</th>
                <th style="width: 20%;">Sí acepto</th>
            </tr>
            <tr>
                <td>A. El profesional tratante me ha informado satisfactoriamente acerca de los motivos y propósitos del tratamiento planificado para mi enfermedad</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>B. El profesional tratante me ha explicado adecuadamente las actividades esenciales que se realizarán durante el tratamiento de mi enfermedad</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>C. Consiento a que se realicen las intervenciones quirúrgicas, procedimientos diagnósticos y tratamientos necesarios para mi enfermedad</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>D. Consiento a que me administren la anestesia propuesta</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>E. He entendido bien que existe garantía de la calidad de los medios utilizados para el tratamiento, pero no acerca de los resultados</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>F. He comprendido plenamente los beneficios y los riesgos de complicaciones derivadas del tratamiento</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>G. El profesional tratante me ha informado que existe garantía de respeto a mi intimidad, a mis creencias religiosas y a la confidencialidad de la información (inclusive en el caso de VIH/SIDA)</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>H. He comprendido que tengo el derecho de anular este consentimiento informado en el momento que yo lo considere necesario</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>I. Declaro que he entregado al profesional tratante información completa y fidedigna sobre los antecedentes personales y familiares de mi estado de salud. Estoy consciente de que mis omisiones o distorsiones deliberadas de los hechos pueden afectar los resultados del tratamiento</td>
                <td class="checkbox-cell"><input type="checkbox"></td>
            </tr>
        </table>
    </div>

    <div id="pagina"> 
        <div class="section-title">AUTORIZACIÓN PARA CIRUGÍA, TRATAMIENTO CLÍNICO O PROCEDIMIENTO DIAGNÓSTICO</div>
        
        <p class="authorization-text">
            Autorizo al profesional tratante de este establecimiento de salud para realizar las operaciones quirúrgicas, procedimientos diagnósticos y tratamientos clínicos propuestos y necesarios para el tratamiento de mi enfermedad.
        </p>

        <table class="no-corte">
            <tr>
                <th>Nombre del Paciente</th>
                <th>Teléfono</th>
                <th>Cédula de Ciudadanía</th>
            </tr>
            <tr>
                <td><input type="text" id="nombrePaciente"></td>
                <td><input type="tel" id="telefonoPaciente"></td>
                <td><input type="text" id="cedulaPaciente"></td>
            </tr>
        </table>

        <div class="checkbox-container">
            <label>
                <input type="checkbox" id="checkMenor">
                <strong>¿El paciente es menor de edad?</strong>
            </label>
        </div>

        <div id="menor-edad">
            <div class="section-title">CONSENTIMIENTO INFORMADO DEL REPRESENTANTE LEGAL</div>
            
            <p class="authorization-text">
                Como responsable legal del paciente que ha sido considerado por ahora imposibilitado para decidir en forma autónoma su consentimiento, autorizo la realización del tratamiento según la información entregada por los profesionales de la salud en este documento.
            </p>

            <table class="no-corte">
                <tr>
                    <th>Nombre del Representante Legal</th>
                    <th>Parentesco</th>
                    <th>Cédula del Representante</th>
                    <th>Teléfono de Contacto</th>
                    
                </tr>
                <tr>
                    <td><input type="text" id="nombreRepresentante"></td>
                    <td><input type="text" id="parentesco"></td>
                    <td><input type="text" id="cedulaRepresentante"></td>
                    <td><input type="tel" id="telefonoRepresentante"></td>
                </tr>
            </table>
        </div>

        <div class="section-title">INFORMACIÓN DEL PROFESIONAL TRATANTE</div>
        
        <table class="no-corte">
            <tr>
                <th>Nombre del profesional tratante</th>
                <th>Especialidad</th>
                <th>Teléfono</th>
                <th>Código</th>
                <th>Firma</th>
            </tr>
            <tr>
                <td>Danny Lara Castillo</td>
                <td><p>Odontología</p></td>
                <td><input type="tel" id="telefonoProfesional"></td>
                <td><input type="text" id="codigoProfesional"></td>
                <td class="firma-cell"></td>
            </tr>
        </table>
    </div>
</div>

<button type="button">
    Aceptar
</button>

<script>
    const checkbox = document.getElementById('checkMenor');
    const menorEdad = document.getElementById('menor-edad');

    checkbox.addEventListener('change', () => {
        menorEdad.style.display = checkbox.checked ? 'block' : 'none';
    });

    document.getElementById('fecha').valueAsDate = new Date();
    const now = new Date();
    document.getElementById('hora').value = now.getHours().toString().padStart(2, '0') + ':' + 
                                            now.getMinutes().toString().padStart(2, '0');

</script>

</body>
</html>
