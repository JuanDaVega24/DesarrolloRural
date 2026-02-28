/**
 * Funcionalidad de opciones con imágenes para el constructor de formularios
 * Permite crear opciones para preguntas select/checkbox con imágenes asociadas a cada opción
 */

class ConstructorOpcionesImagenes {
    constructor() {
        this.init();
    }

    init() {
        // Escuchar clics en botones de agregar opción
        document.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-add-option')) {
                const preguntaIndex = e.target.getAttribute('onclick').match(/addOption\((\d+)\)/)[1];
                this.agregarOpcion(preguntaIndex);
            }
        });

        // Definir la función global addOption para que funcione onclick
        window.addOption = (preguntaIndex) => {
            this.agregarOpcion(preguntaIndex);
        };

        // Escuchar cambios en inputs de archivo
        document.addEventListener('change', (e) => {
            if (e.target && e.target.classList.contains('input-imagen-opcion')) {
                this.manejarSeleccionImagen(e.target);
            }
        });

        // Escuchar clics en botones de eliminar imagen
        document.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-eliminar-imagen-opcion')) {
                this.eliminarImagenOpcion(e.target);
            }
        });
    }

    agregarOpcion(preguntaIndex) {
        const optionsList = document.getElementById(`options-list-${preguntaIndex}`);
        const opcionIndex = optionsList.children.length;

        const opcionDiv = document.createElement('div');
        opcionDiv.className = 'opcion-item';
        opcionDiv.innerHTML = `
            <div class="opcion-input-group">
                <input type="text" 
                       class="form-control opcion-texto" 
                       placeholder="Texto de la opción"
                       name="preguntas[${preguntaIndex}][opciones][${opcionIndex}][texto]"
                       required>
                
                <div class="contenedor-imagen-opcion">
                    <div class="drop-zone-opcion">
                        <div class="drop-zone-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="drop-zone-text">Arrastrar y soltar imagen</div>
                        <div class="drop-zone-subtext">o hacer clic para seleccionar</div>
                        <input type="file" 
                               class="input-imagen-opcion" 
                               accept="image/*"
                               data-pregunta-index="${preguntaIndex}"
                               data-opcion-index="${opcionIndex}">
                    </div>
                    
                    <div class="miniatura-container" style="display: none;">
                        <img class="miniatura-opcion" src="" alt="Miniatura">
                        <button type="button" class="btn-eliminar-imagen-opcion" title="Eliminar imagen">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mensaje-estado-opcion"></div>
                </div>
            </div>
        `;

        optionsList.appendChild(opcionDiv);

        // Actualizar índices de todas las opciones
        this.actualizarIndicesOpciones(preguntaIndex);
    }

    manejarSeleccionImagen(input) {
        const file = input.files[0];
        if (!file) return;

        // Validar archivo
        if (!this.validarArchivo(file)) {
            this.mostrarMensajeError(input, 'Por favor selecciona un archivo de imagen válido (JPEG, PNG, JPG, GIF).');
            return;
        }

        // Mostrar miniatura
        this.mostrarMiniatura(input, file);

        // Subir imagen
        this.subirImagen(input, file);
    }

    validarArchivo(file) {
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

    mostrarMiniatura(input, file) {
        const miniaturaContainer = input.closest('.contenedor-imagen-opcion').querySelector('.miniatura-container');
        const miniatura = miniaturaContainer.querySelector('.miniatura-opcion');

        const reader = new FileReader();
        reader.onload = (e) => {
            miniatura.src = e.target.result;
            miniaturaContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    subirImagen(input, file) {
        const preguntaIndex = input.dataset.preguntaIndex;
        const opcionIndex = input.dataset.opcionIndex;
        const mensajeEstado = input.closest('.contenedor-imagen-opcion').querySelector('.mensaje-estado-opcion');

        this.mostrarMensaje(mensajeEstado, 'advertencia', 'Subiendo imagen...');

        const formData = new FormData();
        formData.append('imagen', file);

        fetch(`/formularios/constructor/imagenes`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.mostrarMensaje(mensajeEstado, 'exito', '¡Imagen subida exitosamente!');
                
                // Crear campo oculto para almacenar la URL de la imagen
                this.crearCampoImagen(input, data.imagenes[0]);
            } else {
                this.mostrarMensaje(mensajeEstado, 'error', data.message || 'Error al subir la imagen.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.mostrarMensaje(mensajeEstado, 'error', 'Error de red al subir la imagen.');
        });
    }

    crearCampoImagen(input, urlImagen) {
        const preguntaIndex = input.dataset.preguntaIndex;
        const opcionIndex = input.dataset.opcionIndex;
        
        // Buscar o crear el campo oculto para la imagen de esta opción
        let inputImagen = input.closest('.opcion-item').querySelector(`input[name="preguntas[${preguntaIndex}][opciones][${opcionIndex}][imagen]"]`);
        if (!inputImagen) {
            inputImagen = document.createElement('input');
            inputImagen.type = 'hidden';
            inputImagen.name = `preguntas[${preguntaIndex}][opciones][${opcionIndex}][imagen]`;
            input.closest('.opcion-item').appendChild(inputImagen);
        }
        
        // Actualizar el valor del campo oculto
        inputImagen.value = urlImagen;
    }

    eliminarImagenOpcion(button) {
        const miniaturaContainer = button.closest('.miniatura-container');
        const inputImagen = miniaturaContainer.closest('.opcion-item').querySelector('input[type="hidden"]');
        const inputArchivo = miniaturaContainer.closest('.contenedor-imagen-opcion').querySelector('.input-imagen-opcion');
        const mensajeEstado = miniaturaContainer.closest('.contenedor-imagen-opcion').querySelector('.mensaje-estado-opcion');

        if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
            // Eliminar del servidor
            if (inputImagen && inputImagen.value) {
                this.eliminarImagenServidor(inputImagen.value, (success) => {
                    if (success) {
                        // Limpiar UI
                        miniaturaContainer.style.display = 'none';
                        miniaturaContainer.querySelector('img').src = '';
                        inputImagen.value = '';
                        inputArchivo.value = '';
                        this.mostrarMensaje(mensajeEstado, 'exito', 'Imagen eliminada exitosamente.');
                    } else {
                        this.mostrarMensaje(mensajeEstado, 'error', 'Error al eliminar la imagen del servidor.');
                    }
                });
            } else {
                // Limpiar UI sin eliminar del servidor
                miniaturaContainer.style.display = 'none';
                miniaturaContainer.querySelector('img').src = '';
                inputArchivo.value = '';
                this.mostrarMensaje(mensajeEstado, 'exito', 'Imagen eliminada exitosamente.');
            }
        }
    }

    eliminarImagenServidor(urlImagen, callback) {
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
            console.error('Error:', error);
            callback(false);
        });
    }

    actualizarIndicesOpciones(preguntaIndex) {
        const optionsList = document.getElementById(`options-list-${preguntaIndex}`);
        const opciones = optionsList.querySelectorAll('.opcion-item');

        opciones.forEach((opcion, index) => {
            const inputTexto = opcion.querySelector('.opcion-texto');
            const inputImagen = opcion.querySelector('input[type="hidden"]');
            const inputArchivo = opcion.querySelector('.input-imagen-opcion');

            // Actualizar nombres de los inputs
            inputTexto.name = `preguntas[${preguntaIndex}][opciones][${index}][texto]`;
            
            if (inputImagen) {
                inputImagen.name = `preguntas[${preguntaIndex}][opciones][${index}][imagen]`;
            }

            if (inputArchivo) {
                inputArchivo.dataset.opcionIndex = index;
            }
        });
    }

    mostrarMensaje(elemento, tipo, mensaje) {
        elemento.className = `mensaje-estado-opcion ${tipo}`;
        elemento.textContent = mensaje;
        elemento.style.display = 'block';

        // Ocultar mensaje después de 3 segundos
        setTimeout(() => {
            elemento.style.display = 'none';
        }, 3000);
    }

    mostrarMensajeError(input, mensaje) {
        const contenedor = input.closest('.opcion-input-group');
        let errorDiv = contenedor.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger';
            contenedor.appendChild(errorDiv);
        }
        
        errorDiv.textContent = mensaje;
        
        // Ocultar mensaje después de 3 segundos
        setTimeout(() => {
            errorDiv.textContent = '';
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ConstructorOpcionesImagenes();
});

// Exportar para uso en otros módulos
window.ConstructorOpcionesImagenes = ConstructorOpcionesImagenes;