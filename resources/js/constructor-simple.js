/**
 * Sistema simplificado de opciones con imágenes para el constructor de formularios
 * Solución directa y robusta para subir imágenes por opción
 */

// Definir funciones globales para que el HTML pueda acceder a ellas
window.agregarOpcion = function(preguntaIndex, data = null) {
    console.log('Función agregarOpcion llamada con índice:', preguntaIndex, data);
    
    const optionsList = document.getElementById(`options-list-${preguntaIndex}`);
    if (!optionsList) {
        console.error('No se encontró el contenedor de opciones para la pregunta', preguntaIndex);
        return;
    }
    
    const opcionIndex = optionsList.children.length;
    console.log('Creando opción número:', opcionIndex);
    
    const textoOpcion = data ? (typeof data === 'object' ? data.texto : data) : '';
    const imagenOpcion = data && typeof data === 'object' ? data.imagen : '';

    const opcionDiv = document.createElement('div');
    opcionDiv.className = 'opcion-item';
    opcionDiv.innerHTML = `
        <div class="opcion-input-group">
            <!-- Input de texto de la opción -->
            <input type="text" 
                   class="form-control opcion-texto" 
                   placeholder="Texto de la opción"
                   value="${textoOpcion}"
                   required>
            
            <!-- Contenedor de imagen de la opción -->
            <div class="contenedor-imagen-opcion">
                <!-- Input de archivo visible -->
                <input type="file" 
                       class="input-imagen-opcion-simple" 
                       accept="image/*"
                       data-pregunta-index="${preguntaIndex}"
                       data-opcion-index="${opcionIndex}">
                
                <!-- Botón de subida visible -->
                <button type="button" 
                        class="btn-subir-imagen-simple" 
                        onclick="subirImagenSimple(this)"
                        data-pregunta-index="${preguntaIndex}"
                        data-opcion-index="${opcionIndex}">
                    <i class="fas fa-upload me-1"></i>Subir Imagen
                </button>
                
                <!-- Miniatura de la imagen -->
                <div class="miniatura-container-simple" style="${imagenOpcion ? 'display: block;' : 'display: none;'}">
                    <img class="miniatura-opcion-simple" src="${imagenOpcion || ''}" alt="Miniatura">
                    <button type="button" 
                            class="btn-eliminar-imagen-simple" 
                            onclick="eliminarImagenSimple(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <!-- Mensaje de estado -->
                <div class="mensaje-estado-simple"></div>
            </div>
        </div>
        
        <!-- Campo oculto para la URL de la imagen -->
        <input type="hidden" 
               class="input-url-imagen-simple"
               value="${imagenOpcion || ''}">
    `;
    
    optionsList.appendChild(opcionDiv);
    console.log('Opción creada exitosamente');
    
    // Actualizar índices de todas las opciones
    actualizarIndicesOpcionesSimples(preguntaIndex);
};

window.subirImagenSimple = function(button) {
    console.log('Función subirImagenSimple llamada');
    
    const preguntaIndex = button.getAttribute('data-pregunta-index');
    const opcionIndex = button.getAttribute('data-opcion-index');
    
    // Encontrar el input de archivo correspondiente
    const inputArchivo = button.parentElement.querySelector('.input-imagen-opcion-simple');
    const mensajeEstado = button.parentElement.querySelector('.mensaje-estado-simple');
    const miniaturaContainer = button.parentElement.querySelector('.miniatura-container-simple');
    const miniatura = miniaturaContainer.querySelector('.miniatura-opcion-simple');
    const inputUrl = button.closest('.opcion-item').querySelector('.input-url-imagen-simple');
    
    if (!inputArchivo || !inputArchivo.files || inputArchivo.files.length === 0) {
        mostrarMensajeSimple(mensajeEstado, 'error', 'Por favor selecciona una imagen primero');
        return;
    }
    
    const file = inputArchivo.files[0];
    
    // Validar archivo
    if (!validarArchivoSimple(file)) {
        mostrarMensajeSimple(mensajeEstado, 'error', 'Por favor selecciona un archivo de imagen válido (JPEG, PNG, JPG, GIF)');
        return;
    }
    
    // Mostrar miniatura
    mostrarMiniaturaSimple(miniatura, file);
    miniaturaContainer.style.display = 'block';
    
    // Subir imagen
    subirImagenServidorSimple(file, preguntaIndex, opcionIndex, mensajeEstado, inputUrl);
};

window.eliminarImagenSimple = function(button) {
    console.log('Función eliminarImagenSimple llamada');
    
    const miniaturaContainer = button.parentElement;
    const inputUrl = button.closest('.opcion-item').querySelector('.input-url-imagen-simple');
    const inputArchivo = button.parentElement.parentElement.querySelector('.input-imagen-opcion-simple');
    const mensajeEstado = button.parentElement.parentElement.querySelector('.mensaje-estado-simple');
    
    if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
        // Limpiar UI
        miniaturaContainer.style.display = 'none';
        miniaturaContainer.querySelector('img').src = '';
        inputUrl.value = '';
        inputArchivo.value = '';
        mostrarMensajeSimple(mensajeEstado, 'exito', 'Imagen eliminada exitosamente');
    }
};

// Funciones auxiliares

function validarArchivoSimple(file) {
    const tiposValidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!tiposValidos.includes(file.type)) {
        return false;
    }

    if (file.size > maxSize) {
        return false;
    }

    return true;
}

function mostrarMiniaturaSimple(miniatura, file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        miniatura.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function subirImagenServidorSimple(file, preguntaIndex, opcionIndex, mensajeEstado, inputUrl) {
    mostrarMensajeSimple(mensajeEstado, 'advertencia', 'Subiendo imagen...');
    
    const formData = new FormData();
    formData.append('imagen', file);

    fetch(`/formularios/constructor/imagenes`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Respuesta de subida:', response);
        return response.json();
    })
    .then(data => {
        console.log('Datos de subida:', data);
        if (data.success) {
            mostrarMensajeSimple(mensajeEstado, 'exito', '¡Imagen subida exitosamente!');
            inputUrl.value = data.imagenes[0];
        } else {
            mostrarMensajeSimple(mensajeEstado, 'error', data.message || 'Error al subir la imagen');
        }
    })
    .catch(error => {
        console.error('Error de subida:', error);
        mostrarMensajeSimple(mensajeEstado, 'error', 'Error de red al subir la imagen');
    });
}

function eliminarImagenServidorSimple(urlImagen, callback) {
    fetch(`/formularios/constructor/imagenes`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            imagen_url: urlImagen
        })
    })
    .then(response => response.json())
    .then(data => {
        callback(data.success);
    })
    .catch(error => {
        console.error('Error al eliminar:', error);
        callback(false);
    });
}

function actualizarIndicesOpcionesSimples(preguntaIndex) {
    // Ya no es necesario actualizar nombres individuales ya que usamos JSON
}

function mostrarMensajeSimple(elemento, tipo, mensaje) {
    if (!elemento) return;
    
    elemento.className = `mensaje-estado-simple ${tipo}`;
    elemento.textContent = mensaje;
    elemento.style.display = 'block';
    elemento.style.color = tipo === 'error' ? '#dc3545' : tipo === 'exito' ? '#28a745' : '#ffc107';
    
    // Ocultar mensaje después de 3 segundos
    setTimeout(() => {
        elemento.style.display = 'none';
    }, 3000);
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('Constructor simple cargado y listo');
});