/**
 * ODONTOGRAMA INTERACTIVO v2.0 (FINAL)
 * Lógica: Modal con Categoría -> Tratamiento -> Caras -> Pintar
 */

// 1. CATÁLOGO DE TRATAMIENTOS
const CATALOGO = {
    'Restauradora': ['Caries', 'Resina', 'Amalgama', 'Ionomero', 'Incrustación', 'Restauración temporal'],
    'Endodoncia': ['Tratamiento de conducto', 'Pulpotomía', 'Apicoformación'],
    'Cirugía': ['Extracción Indicada', 'Pieza Ausente (Perdida)', 'Cirugía apical'],
    'Prótesis': ['Corona', 'Prótesis Fija', 'Prótesis Removible', 'Perno muñón'],
    'Periodoncia': ['Movilidad', 'Recesión'],
    'Prevención': ['Sellante', 'Profilaxis', 'Flúor', 'Sano'] 
};

// Variable Global (Donde se guardan los cambios)
let estadoOdontograma = {}; 
let dienteSeleccionado = null;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log("Odontograma cargado.");
    cargarDatosExistentes();
    cargarCategoriasEnModal();
});

// ============================================================
// 2. LÓGICA DEL MODAL
// ============================================================

function abrirModalDiente(numeroDiente) {
    dienteSeleccionado = numeroDiente;
    document.getElementById('lbl-diente-seleccionado').innerText = numeroDiente;
    
    // Resetear formulario
    document.getElementById('form-tratamiento').reset();
    const selectTrat = document.getElementById('select-tratamiento');
    selectTrat.innerHTML = '<option value="">Seleccione categoría primero...</option>';
    selectTrat.disabled = true;
    
    // Abrir Modal
    const modalEl = document.getElementById('modalTratamiento');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function cargarCategoriasEnModal() {
    const selectCat = document.getElementById('select-categoria');
    selectCat.innerHTML = '<option value="">Seleccione...</option>';
    for (const cat in CATALOGO) {
        let option = document.createElement('option');
        option.value = cat;
        option.innerText = cat;
        selectCat.appendChild(option);
    }
}

function cargarTratamientos() {
    const cat = document.getElementById('select-categoria').value;
    const selectTrat = document.getElementById('select-tratamiento');
    selectTrat.innerHTML = '';
    
    if (cat && CATALOGO[cat]) {
        selectTrat.disabled = false;
        CATALOGO[cat].forEach(trat => {
            let option = document.createElement('option');
            option.value = trat;
            option.innerText = trat;
            selectTrat.appendChild(option);
        });
    } else {
        selectTrat.innerHTML = '<option value="">Seleccione categoría primero...</option>';
        selectTrat.disabled = true;
    }
}

// ============================================================
// 3. APLICAR TRATAMIENTO (Botón Guardar DEL MODAL)
// ============================================================

function aplicarTratamiento() {
    const categoria = document.getElementById('select-categoria').value;
    const tratamiento = document.getElementById('select-tratamiento').value;
    const observacion = document.getElementById('txt-observacion') ? document.getElementById('txt-observacion').value : '';
    
    // Obtener estado (Rojo/Azul)
    const radioEstado = document.querySelector('input[name="estado_tratamiento"]:checked');
    const estado = radioEstado ? radioEstado.value : 'malo';

    // Obtener caras
    let caras = [];
    document.querySelectorAll('.cara-checkbox:checked').forEach(chk => caras.push(chk.value));

    if (!tratamiento) { alert("Seleccione un tratamiento."); return; }

    // Definir Color
    let color = (estado === 'malo') ? '#dc3545' : '#0d6efd'; // Rojo o Azul
    
    // Casos Especiales
    if (tratamiento.includes('Ausente') || tratamiento.includes('Extracción')) {
        color = '#333333'; // Negro
        caras = ['vestibular', 'lingual', 'distal', 'mesial', 'oclusal', 'palatina', 'center', 'top', 'bottom', 'left', 'right']; 
    } else if (tratamiento === 'Sano') {
        color = '#ffffff'; // Blanco
        caras = ['vestibular', 'lingual', 'distal', 'mesial', 'oclusal', 'palatina', 'center', 'top', 'bottom', 'left', 'right'];
    } else if (caras.length === 0) {
        caras = ['oclusal', 'center']; // Por defecto centro
    }

    // 1. Pintar en Pantalla
    pintarDienteEnPantalla(dienteSeleccionado, caras, color);

    // 2. Guardar en Memoria
    if (!estadoOdontograma[dienteSeleccionado]) {
        estadoOdontograma[dienteSeleccionado] = { tratamientos: [] };
    }

    estadoOdontograma[dienteSeleccionado].tratamientos.push({
        categoria, tratamiento, estado, caras, observacion, color
    });

    actualizarListaLateral();

    // 3. Cerrar Modal
    bootstrap.Modal.getInstance(document.getElementById('modalTratamiento')).hide();
}

function pintarDienteEnPantalla(numero, caras, color) {
    const svg = document.querySelector(`svg[data-pieza="${numero}"]`);
    if (!svg) return;

    caras.forEach(cara => {
        let selector = `.${cara}`;
        // Compatibilidad de nombres de clases
        if (cara === 'palatina' || cara === 'lingual') selector = '.palatina, .lingual, .bottom';
        if (cara === 'vestibular') selector = '.vestibular, .top';
        if (cara === 'mesial') selector = '.mesial, .left';
        if (cara === 'distal') selector = '.distal, .right';
        if (cara === 'oclusal') selector = '.oclusal, .center';

        svg.querySelectorAll(selector).forEach(parte => {
            parte.style.fill = color;
            parte.style.stroke = 'black';
        });
    });
}

function actualizarListaLateral() {
    const lista = document.getElementById('lista-tratamientos');
    if(!lista) return;
    lista.innerHTML = '';

    for (const [numero, data] of Object.entries(estadoOdontograma)) {
        if (data.tratamientos.length > 0) {
            const t = data.tratamientos[data.tratamientos.length - 1];
            if (t.color === '#ffffff') continue; // Si es sano no mostrar

            let badgeClass = (t.estado === 'malo') ? 'bg-danger' : 'bg-primary';
            if (t.color === '#333333') badgeClass = 'bg-dark';

            lista.innerHTML += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 fw-bold">Pieza ${numero}</h6>
                        <span class="badge ${badgeClass}">${t.estado === 'malo' ? 'Patología' : 'Realizado'}</span>
                    </div>
                    <p class="mb-1 small fw-bold">${t.tratamiento}</p>
                    <small class="text-muted">Caras: ${t.caras.join(', ') || 'General'}</small>
                </div>`;
        }
    }
}

// ============================================================
// 4. GUARDAR EN BD (Botón Verde - AQUI ESTABA EL ERROR)
// ============================================================

function guardarOdontograma() {
    const container = document.querySelector('[data-historia-id]');
    if (!container) { alert("Error: No se encuentra el ID de la historia."); return; }
    
    const historiaId = container.dataset.historiaId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let dataToSend = [];
    
    for (const [numero, data] of Object.entries(estadoOdontograma)) {
        if(data.tratamientos.length > 0) {
            let ultimo = data.tratamientos[data.tratamientos.length - 1];
            dataToSend.push({
                numero_pieza: numero,
                estado: (ultimo.tratamiento === 'Sano') ? 'sano' : ultimo.tratamiento, 
                observaciones: JSON.stringify(ultimo) 
            });
        }
    }

    // --- AQUÍ ESTÁ EL ARREGLO ---
    // Creamos una función que devuelve "0" si el input no existe.
    // Esto evita que se envíe null a la base de datos.
    const getVal = (id) => {
        const el = document.getElementById(id);
        // Si existe y tiene valor, úsalo. Si no, devuelve "0".
        return (el && el.value) ? el.value : "0";
    };

    const indices = {
        cpo_cariados: getVal('cpo_cariados'),
        cpo_perdidos: getVal('cpo_perdidos'),
        cpo_obturados: getVal('cpo_obturados'),
        ceo_cariados: getVal('ceo_cariados'),
        ceo_extraccion: getVal('ceo_extraccion'),
        ceo_obturados: getVal('ceo_obturados'),
        // Estos son los que te daban error:
        placa_bacteriana: getVal('placa_bacteriana'),
        calculo_dental: getVal('calculo_dental'),
        gingivitis: getVal('gingivitis'),
        nivel_fluorosis: getVal('nivel_fluorosis'),
        tipo_oclusion: getVal('tipo_oclusion')
    };

    fetch(`/doctor/historia/${historiaId}/odontograma`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ odontograma: dataToSend, indices: indices })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('¡Guardado correctamente!');
        } else {
            alert('Error del servidor: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de conexión. Revisa la consola (F12) para detalles.');
    });
}

function cargarDatosExistentes() {
    const container = document.querySelector('[data-historia-id]');
    if (!container) return;
    const historiaId = container.dataset.historiaId;

    fetch(`/api/historia/${historiaId}/odontograma`)
    .then(res => res.json())
    .then(data => {
        if(Array.isArray(data)) {
            data.forEach(item => {
                try {
                    // Intentar leer JSON de observaciones
                    if (item.observaciones && item.observaciones.startsWith('{')) {
                        let detalle = JSON.parse(item.observaciones);
                        if (!estadoOdontograma[item.numero_pieza]) estadoOdontograma[item.numero_pieza] = { tratamientos: [] };
                        estadoOdontograma[item.numero_pieza].tratamientos.push(detalle);
                        pintarDienteEnPantalla(item.numero_pieza, detalle.caras, detalle.color);
                    }
                } catch(e) {}
            });
            actualizarListaLateral();
        }
    });
}

function resetearOdontograma() {
    if(confirm('¿Borrar visualmente todo?')) {
        estadoOdontograma = {};
        actualizarListaLateral();
        document.querySelectorAll('svg polygon, svg rect, svg path, svg circle').forEach(el => {
            el.style.fill = 'white'; 
            el.style.stroke = 'black';
        });
    }
}

// Hacer funciones globales para que el HTML las vea
window.abrirModalDiente = abrirModalDiente;
window.cargarCategoriasEnModal = cargarCategoriasEnModal;
window.cargarTratamientos = cargarTratamientos;
window.aplicarTratamiento = aplicarTratamiento;
window.guardarOdontograma = guardarOdontograma;
window.resetearOdontograma = resetearOdontograma;