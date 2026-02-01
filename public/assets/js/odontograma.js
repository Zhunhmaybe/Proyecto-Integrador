/**
 * ODONTOGRAMA INTERACTIVO v2.0
 * Maneja dientes adultos (cuadrados) y niños (circulares)
 * Lógica: Modal con Categoría -> Tratamiento -> Caras
 */

// 1. CATÁLOGO DE TRATAMIENTOS (Lógica de tus imágenes)
const CATALOGO = {
    'Restauradora': [
        'Caries', 
        'Resina', 
        'Amalgama', 
        'Carilla directa', 
        'Incrustación',
        'Restauración temporal'
    ],
    'Endodoncia': [
        'Tratamiento de conducto', 
        'Pulpotomía', 
        'Apicoformación'
    ],
    'Cirugía': [
        'Extracción Indicada', 
        'Pieza Ausente (Perdida)', 
        'Cirugía apical'
    ],
    'Prótesis': [
        'Corona', 
        'Prótesis Fija', 
        'Prótesis Removible', 
        'Perno muñón'
    ],
    'Periodoncia': [
        'Movilidad',
        'Recesión'
    ],
    'Prevención': [
        'Sellante', 
        'Sano' // Opción para borrar/limpiar
    ]
};

// Variables Globales
let estadoOdontograma = {}; 
let dienteSeleccionado = null;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log("Odontograma JS cargado correctamente.");
    cargarDatosExistentes();
    cargarCategoriasEnModal();
});

// ============================================================
// 2. LÓGICA DEL MODAL (Abrir y Llenar Selects)
// ============================================================

// Se llama desde el HTML: onclick="abrirModalDiente(18)"
function abrirModalDiente(numeroDiente) {
    dienteSeleccionado = numeroDiente;
    document.getElementById('lbl-diente-seleccionado').innerText = numeroDiente;
    
    // Resetear formulario del modal
    document.getElementById('form-tratamiento').reset();
    
    // Resetear select dependiente
    const selectTrat = document.getElementById('select-tratamiento');
    selectTrat.innerHTML = '<option value="">Seleccione categoría primero...</option>';
    selectTrat.disabled = true;
    
    // Abrir Modal (Bootstrap 5)
    const modalEl = document.getElementById('modalTratamiento');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function cargarCategoriasEnModal() {
    const selectCat = document.getElementById('select-categoria');
    selectCat.innerHTML = '<option value="">Seleccione...</option>';
    
    // Llenar con las llaves del objeto CATALOGO
    for (const cat in CATALOGO) {
        let option = document.createElement('option');
        option.value = cat;
        option.innerText = cat;
        selectCat.appendChild(option);
    }
}

// Se llama al cambiar el select de Categoría (onchange)
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
// 3. FUNCIÓN PRINCIPAL: APLICAR TRATAMIENTO (Botón Guardar del Modal)
// ============================================================

function aplicarTratamiento() {
    // 1. Obtener datos del formulario
    const categoria = document.getElementById('select-categoria').value;
    const tratamiento = document.getElementById('select-tratamiento').value;
    const observacion = document.getElementById('txt-observacion').value;
    
    // Obtener estado (Radio button: malo/rojo o bueno/azul)
    const radioEstado = document.querySelector('input[name="estado_tratamiento"]:checked');
    const estado = radioEstado ? radioEstado.value : 'malo';

    // Obtener caras seleccionadas (Checkboxes)
    let caras = [];
    document.querySelectorAll('.cara-checkbox:checked').forEach(chk => {
        caras.push(chk.value);
    });

    // Validaciones
    if (!tratamiento) {
        alert("Por favor seleccione un Tratamiento.");
        return;
    }

    // 2. Definir Color
    // Rojo (#dc3545) = Patología, Azul (#0d6efd) = Realizado
    let color = (estado === 'malo') ? '#dc3545' : '#0d6efd';
    
    // Casos Especiales (Pintan todo el diente o borran)
    if (tratamiento.includes('Ausente') || tratamiento.includes('Extracción')) {
        color = '#333333'; // Negro
        caras = ['vestibular', 'lingual', 'distal', 'mesial', 'oclusal', 'palatina', 'center', 'top', 'bottom', 'left', 'right']; 
    } else if (tratamiento === 'Sano') {
        color = '#ffffff'; // Blanco (Borrar)
        caras = ['vestibular', 'lingual', 'distal', 'mesial', 'oclusal', 'palatina', 'center', 'top', 'bottom', 'left', 'right'];
    } else {
        // Si es un tratamiento normal y NO seleccionó caras, pintamos el centro (oclusal) por defecto
        if (caras.length === 0) caras = ['oclusal', 'center']; 
    }

    // 3. Actualizar Visualmente (SVG)
    pintarDienteEnPantalla(dienteSeleccionado, caras, color);

    // 4. Guardar en Memoria (Variable Global)
    if (!estadoOdontograma[dienteSeleccionado]) {
        estadoOdontograma[dienteSeleccionado] = { tratamientos: [] };
    }

    // Agregamos este movimiento al historial del diente
    estadoOdontograma[dienteSeleccionado].tratamientos.push({
        categoria,
        tratamiento,
        estado, // 'malo' o 'bueno'
        caras,
        observacion,
        color
    });

    // Actualizar la lista lateral izquierda
    actualizarListaLateral();

    // 5. Cerrar Modal
    const modalEl = document.getElementById('modalTratamiento');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}

/**
 * Busca el SVG y le cambia el color a las caras indicadas
 */
function pintarDienteEnPantalla(numero, caras, color) {
    const svg = document.querySelector(`svg[data-pieza="${numero}"]`);
    if (!svg) return;

    caras.forEach(cara => {
        // Selector CSS para encontrar la parte del SVG
        // Mapeamos nombres para que funcione en Adultos y Niños
        let selector = `.${cara}`;
        
        if (cara === 'palatina' || cara === 'lingual') selector = '.palatina, .lingual, .bottom';
        if (cara === 'vestibular') selector = '.vestibular, .top';
        if (cara === 'mesial') selector = '.mesial, .left';
        if (cara === 'distal') selector = '.distal, .right';
        if (cara === 'oclusal') selector = '.oclusal, .center';

        const partes = svg.querySelectorAll(selector);
        partes.forEach(parte => {
            parte.style.fill = color;
            parte.style.stroke = 'black'; // Asegurar que no pierda el borde
        });
    });
}

function actualizarListaLateral() {
    const lista = document.getElementById('lista-tratamientos');
    if(!lista) return;
    
    lista.innerHTML = ''; // Limpiar lista

    // Recorremos el estado guardado
    for (const [numero, data] of Object.entries(estadoOdontograma)) {
        // Mostrar el último tratamiento registrado
        if (data.tratamientos.length > 0) {
            const t = data.tratamientos[data.tratamientos.length - 1]; 
            
            // No mostrar si es "Sano" (borrado)
            if (t.color === '#ffffff') continue;

            let badgeClass = (t.estado === 'malo') ? 'bg-danger' : 'bg-primary';
            if (t.color === '#333333') badgeClass = 'bg-dark';

            const item = document.createElement('div');
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 fw-bold">Pieza ${numero}</h6>
                    <span class="badge ${badgeClass}">${t.estado === 'malo' ? 'Patología' : 'Realizado'}</span>
                </div>
                <p class="mb-1 small fw-bold">${t.tratamiento}</p>
                <small class="text-muted">Caras: ${t.caras.join(', ') || 'General'}</small>
                <div class="small text-muted fst-italic">${t.observacion || ''}</div>
            `;
            lista.appendChild(item);
        }
    }
}

// ============================================================
// 4. GUARDAR EN BASE DE DATOS (Botón Verde Superior)
// ============================================================

function guardarOdontograma() {
    const container = document.querySelector('.container-fluid[data-historia-id]');
    if(!container) return;
    
    const historiaId = container.dataset.historiaId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Preparamos datos para enviar a Laravel
    let dataToSend = [];
    
    for (const [numero, data] of Object.entries(estadoOdontograma)) {
        if(data.tratamientos.length > 0) {
            let ultimo = data.tratamientos[data.tratamientos.length - 1];
            
            // Si el último estado es "Sano", guardamos como sano en BD
            let estadoBD = (ultimo.tratamiento === 'Sano') ? 'sano' : ultimo.tratamiento;

            dataToSend.push({
                numero_pieza: numero,
                estado: estadoBD, 
                // Guardamos todo el objeto de detalle en 'observaciones' como JSON
                // Esto nos permite recuperar colores y caras después
                observaciones: JSON.stringify(ultimo) 
            });
        }
    }

    // Petición Fetch
    fetch(`/doctor/historia/${historiaId}/odontograma`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ odontograma: dataToSend })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('¡Odontograma guardado correctamente!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de conexión al guardar.');
    });
}

function cargarDatosExistentes() {
    const container = document.querySelector('.container-fluid[data-historia-id]');
    if(!container) return;
    const historiaId = container.dataset.historiaId;

    fetch(`/api/historia/${historiaId}/odontograma`)
    .then(res => res.json())
    .then(data => {
        if(Array.isArray(data)) {
            data.forEach(item => {
                // Intentamos leer el JSON guardado en observaciones
                try {
                    if (item.observaciones && item.observaciones.startsWith('{')) {
                        let detalle = JSON.parse(item.observaciones);
                        
                        // Reconstruir memoria
                        if (!estadoOdontograma[item.numero_pieza]) {
                            estadoOdontograma[item.numero_pieza] = { tratamientos: [] };
                        }
                        estadoOdontograma[item.numero_pieza].tratamientos.push(detalle);
                        
                        // Pintar visualmente
                        pintarDienteEnPantalla(item.numero_pieza, detalle.caras, detalle.color);
                    }
                } catch(e) {
                    console.log("Dato sin formato JSON detallado:", item);
                }
            });
            actualizarListaLateral();
        }
    });
}

function resetearOdontograma() {
    if(confirm('¿Limpiar todo el diagrama visualmente?')) {
        estadoOdontograma = {};
        actualizarListaLateral();
        // Reset visual SVG
        document.querySelectorAll('svg polygon, svg rect, svg path, svg circle').forEach(el => {
            el.style.fill = 'white'; 
            el.style.stroke = 'black';
        });
    }
}

// Hacer funciones accesibles globalmente para el HTML
window.abrirModalDiente = abrirModalDiente;
window.cargarCategoriasEnModal = cargarCategoriasEnModal;
window.cargarTratamientos = cargarTratamientos;
window.aplicarTratamiento = aplicarTratamiento;
window.guardarOdontograma = guardarOdontograma;
window.resetearOdontograma = resetearOdontograma;