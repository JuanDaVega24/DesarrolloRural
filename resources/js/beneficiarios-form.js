// Manejar lógica del formulario de beneficiarios
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ Script de formulario de beneficiarios cargado');

    // Variables globales para manejar la lista de beneficiarios
    let beneficiarios = [];
    let beneficiarioEditando = null;

    // Elementos del DOM
    const formulario = document.getElementById('beneficiarioForm');
    const btnAgregar = document.getElementById('btnAgregarBeneficiario');
    const btnGuardar = document.getElementById('btnGuardarBeneficiario');
    const btnCancelar = document.getElementById('btnCancelarBeneficiario');
    const listaBeneficiarios = document.getElementById('listaBeneficiarios');
    const totalBeneficiarios = document.getElementById('totalBeneficiarios');
    const totalHombres = document.getElementById('totalHombres');
    const totalMujeres = document.getElementById('totalMujeres');

    // Si no existen los elementos, no continuar
    if (!formulario || !btnAgregar) {
        console.log('Formulario o botón de agregar no encontrado');
        return;
    }

    console.log('✓ Elementos del DOM encontrados');

    // Evento para agregar beneficiario
    btnAgregar.addEventListener('click', function(e) {
        e.preventDefault();
        agregarBeneficiario();
    });

    // Evento para guardar beneficiario (cuando se está editando)
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function(e) {
            e.preventDefault();
            guardarBeneficiario();
        });
    }

    // Evento para cancelar edición
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function(e) {
            e.preventDefault();
            cancelarEdicion();
        });
    }

    // Función para agregar beneficiario
    function agregarBeneficiario() {
        console.log('✓ Intentando agregar beneficiario');

        // Validar campos
        const errores = validarCampos();
        if (errores.length > 0) {
            mostrarErrores(errores);
            return;
        }

        // Obtener datos del formulario
        const datos = obtenerDatosFormulario();

        // Si estamos editando, actualizar
        if (beneficiarioEditando !== null) {
            beneficiarios[beneficiarioEditando] = datos;
            beneficiarioEditando = null;
            if (btnAgregar) btnAgregar.style.display = 'inline-block';
            if (btnGuardar) btnGuardar.style.display = 'none';
            if (btnCancelar) btnCancelar.style.display = 'none';
        } else {
            // Si es nuevo, agregar a la lista
            beneficiarios.push(datos);
        }

        // Limpiar formulario
        limpiarFormulario();

        // Actualizar vista
        actualizarListaBeneficiarios();
        actualizarTotales();

        // Mostrar mensaje de éxito
        mostrarMensajeExito('Beneficiario agregado exitosamente');

        console.log('✓ Beneficiario procesado, total:', beneficiarios.length);
    }

    // Función para validar campos
    function validarCampos() {
        const errores = [];
        const campos = {
            'data[beneficiario][nombre]': 'Nombre',
            'data[beneficiario][apellido]': 'Apellido',
            'data[beneficiario][tipo_documento]': 'Tipo de documento',
            'data[beneficiario][numero_documento]': 'Número de documento',
            'data[beneficiario][genero]': 'Género',
            'data[beneficiario][telefono]': 'Teléfono',
            'data[beneficiario][direccion]': 'Dirección',
            'data[beneficiario][vereda]': 'Vereda',
            'data[beneficiario][corregimiento]': 'Corregimiento'
        };

        for (const [name, label] of Object.entries(campos)) {
            const input = formulario.querySelector(`[name="${name}"]`);
            if (input && !input.value.trim()) {
                errores.push(`${label} es requerido`);
            }
        }

        // Validar número de documento
        const docInput = formulario.querySelector('[name="data[beneficiario][numero_documento]"]');
        if (docInput && docInput.value && !/^\d{6,12}$/.test(docInput.value)) {
            errores.push('Número de documento debe tener entre 6 y 12 dígitos');
        }

        // Validar teléfono
        const telInput = formulario.querySelector('[name="data[beneficiario][telefono]"]');
        if (telInput && telInput.value && !/^\d{7,10}$/.test(telInput.value)) {
            errores.push('Teléfono debe tener entre 7 y 10 dígitos');
        }

        return errores;
    }

    // Función para obtener datos del formulario
    function obtenerDatosFormulario() {
        const datos = {};
        const inputs = formulario.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            if (input.name && input.name.startsWith('data[beneficiario][')) {
                const key = input.name.replace('data[beneficiario][', '').replace(']', '');
                datos[key] = input.value.trim();
            }
        });

        // Agregar datos de las preguntas dinámicas
        const preguntasInputs = formulario.querySelectorAll('input[name^="data[preguntas]"], select[name^="data[preguntas]"], textarea[name^="data[preguntas]"]');
        datos.preguntas = {};

        preguntasInputs.forEach(input => {
            const name = input.name.replace('data[preguntas][', '').replace(']', '');
            if (input.type === 'checkbox') {
                if (!datos.preguntas[name]) datos.preguntas[name] = [];
                if (input.checked) {
                    datos.preguntas[name].push(input.value);
                }
            } else {
                datos.preguntas[name] = input.value.trim();
            }
        });

        return datos;
    }

    // Función para mostrar errores
    function mostrarErrores(errores) {
        // Limpiar errores anteriores
        const erroresContainer = document.getElementById('erroresFormulario');
        if (erroresContainer) {
            erroresContainer.innerHTML = '';
            errores.forEach(error => {
                const div = document.createElement('div');
                div.className = 'alert alert-danger alert-dismissible fade show';
                div.innerHTML = `<strong>¡Error!</strong> ${error} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                erroresContainer.appendChild(div);
            });
        }

        // Mostrar alerta general
        alert('Por favor corrija los siguientes errores:\n' + errores.join('\n'));
    }

    // Función para limpiar formulario
    function limpiarFormulario() {
        const inputs = formulario.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });

        // Limpiar campos específicos de beneficiario
        const camposBeneficiario = ['nombre', 'apellido', 'tipo_documento', 'numero_documento', 'genero', 'telefono', 'direccion', 'vereda', 'corregimiento'];
        camposBeneficiario.forEach(campo => {
            const input = formulario.querySelector(`[name="data[beneficiario][${campo}]"]`);
            if (input) input.value = '';
        });
    }

    // Función para actualizar lista de beneficiarios
    function actualizarListaBeneficiarios() {
        if (!listaBeneficiarios) return;

        listaBeneficiarios.innerHTML = '';

        if (beneficiarios.length === 0) {
            listaBeneficiarios.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i><br>
                        No hay beneficiarios registrados
                    </td>
                </tr>
            `;
            return;
        }

        beneficiarios.forEach((beneficiario, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${beneficiario.nombre} ${beneficiario.apellido}</td>
                <td>${beneficiario.tipo_documento} - ${beneficiario.numero_documento}</td>
                <td>${beneficiario.genero}</td>
                <td>${beneficiario.telefono}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editarBeneficiario(${index})">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarBeneficiario(${index})">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            `;
            listaBeneficiarios.appendChild(row);
        });
    }

    // Función para actualizar totales
    function actualizarTotales() {
        if (!totalBeneficiarios || !totalHombres || !totalMujeres) return;

        const total = beneficiarios.length;
        const hombres = beneficiarios.filter(b => b.genero === 'Masculino').length;
        const mujeres = beneficiarios.filter(b => b.genero === 'Femenino').length;

        totalBeneficiarios.textContent = total;
        totalHombres.textContent = hombres;
        totalMujeres.textContent = mujeres;
    }

    // Función para mostrar mensaje de éxito
    function mostrarMensajeExito(mensaje) {
        const container = document.getElementById('mensajeExito');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Éxito!</strong> ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    }

    // Funciones globales para edición y eliminación
    window.editarBeneficiario = function(index) {
        console.log('✓ Editando beneficiario', index);
        const beneficiario = beneficiarios[index];
        beneficiarioEditando = index;

        // Llenar formulario con datos del beneficiario
        Object.keys(beneficiario).forEach(key => {
            if (key !== 'preguntas') {
                const input = formulario.querySelector(`[name="data[beneficiario][${key}]"]`);
                if (input) input.value = beneficiario[key];
            }
        });

        // Llenar preguntas dinámicas
        if (beneficiario.preguntas) {
            Object.keys(beneficiario.preguntas).forEach(key => {
                const input = formulario.querySelector(`[name="data[preguntas][${key}]"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        if (Array.isArray(beneficiario.preguntas[key])) {
                            beneficiario.preguntas[key].forEach(value => {
                                const checkbox = formulario.querySelector(`[name="data[preguntas][${key}]"][value="${value}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        }
                    } else {
                        input.value = beneficiario.preguntas[key];
                    }
                }
            });
        }

        // Cambiar botones
        if (btnAgregar) btnAgregar.style.display = 'none';
        if (btnGuardar) btnGuardar.style.display = 'inline-block';
        if (btnCancelar) btnCancelar.style.display = 'inline-block';
    };

    window.eliminarBeneficiario = function(index) {
        if (confirm('¿Está seguro de que desea eliminar este beneficiario?')) {
            beneficiarios.splice(index, 1);
            actualizarListaBeneficiarios();
            actualizarTotales();
            mostrarMensajeExito('Beneficiario eliminado exitosamente');
        }
    };

    function cancelarEdicion() {
        beneficiarioEditando = null;
        limpiarFormulario();
        if (btnAgregar) btnAgregar.style.display = 'inline-block';
        if (btnGuardar) btnGuardar.style.display = 'none';
        if (btnCancelar) btnCancelar.style.display = 'none';
        mostrarMensajeExito('Edición cancelada');
    }

    function guardarBeneficiario() {
        if (beneficiarioEditando !== null) {
            agregarBeneficiario();
        }
    }

    // Inicializar vista
    actualizarListaBeneficiarios();
    actualizarTotales();

    console.log('✓ Lógica de formulario de beneficiarios inicializada');
});