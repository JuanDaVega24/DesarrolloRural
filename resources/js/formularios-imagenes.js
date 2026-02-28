/**
 * Funcionalidad de subida de imágenes para formularios
 */

class FormularioImagenes {
    constructor(contenedor = null) {
        this.contenedor = contenedor || document.querySelector('.imagenes-container');
        this.inputFile = null;
        this.imagenesGrid = null;
        this.mensajeEstado = null;
        this.contadorImagenes = null;
        this.preguntaId = null;
        this.imagenesActuales = [];
        this.maxImagenes = 5;
        
        if (this.contenedor) {
            this.init();
        }
    }

    init() {
        if (!this.contenedor) return;

        this.inputFile = this.contenedor.querySelector('.file-input');
        this.imagenesGrid = this.contenedor.querySelector('.imagenes-grid');
        this.mensajeEstado = this.contenedor.querySelector('.mensaje-estado');
        this.contadorImagenes = this.contenedor.querySelector('.contador-imagenes');
        
        // Obtener ID de la pregunta desde el atributo data
        this.preguntaId = this.contenedor.dataset.preguntaId;
        
        // Inicializar eventos
        this.initEventListeners();
        
        // Cargar imágenes existentes
        this.cargarImagenesExistentes();
    }

    initEventListeners() {
        if (!this.inputFile) return;

        // Eventos para el input de archivo
        this.inputFile.addEventListener('change', (e) => {
            this.manejarSeleccionArchivos(e.target.files);
        });

        // Eventos para arrastrar y soltar
        const dropZone = this.contenedor.querySelector('.drop-zone');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => this.onDragOver(e, dropZone));
            dropZone.addEventListener('dragleave', (e) => this.onDragLeave(e, dropZone));
            dropZone.addEventListener('drop', (e) => this.onDrop(e, dropZone));
            dropZone.addEventListener('click', () => this.inputFile.click());
        }

        // Eventos para botones de acción
        const btnLimpiar = this.contenedor.querySelector('.btn-limpiar');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', () => this.limpiarSeleccion());
        }
    }

    onDragOver(e, dropZone) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('drag-over');
    }

    onDragLeave(e, dropZone) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drag-over');
    }

    onDrop(e, dropZone) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drag-over');

        const files = e.dataTransfer.files;
        if (files && files.length > 0) {
            this.manejarSeleccionArchivos(files);
        }
    }

    manejarSeleccionArchivos(files) {
        if (!files || files.length === 0) return;

        // Validar archivos
        const archivosValidos = Array.from(files).filter(file => this.validarArchivo(file));
        
        if (archivosValidos.length === 0) {
            this.mostrarMensaje('error', 'Por favor selecciona archivos de imagen válidos (JPEG, PNG, JPG, GIF).');
            return;
        }

        // Validar límite de imágenes
        const totalActual = this.imagenesActuales.length;
        const totalNuevo = totalActual + archivosValidos.length;
        
        if (totalNuevo > this.maxImagenes) {
            this.mostrarMensaje('advertencia', `Solo puedes subir hasta ${this.maxImagenes} imágenes. Actualmente tienes ${totalActual} y estás intentando subir ${archivosValidos.length} más.`);
            return;
        }

        // Procesar archivos
        this.procesarArchivos(archivosValidos);
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

    procesarArchivos(archivos) {
        const formData = new FormData();
        
        // Agregar archivos al FormData
        archivos.forEach((file, index) => {
            formData.append(`imagenes[${index}]`, file);
        });

        // Mostrar estado de carga
        this.mostrarMensaje('advertencia', 'Subiendo imágenes...');
        
        // Enviar solicitud
        fetch(`/formularios/${this.preguntaId}/imagenes`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Agregar imágenes al array actual
                this.imagenesActuales = [...this.imagenesActuales, ...data.imagenes];
                
                // Actualizar UI
                this.renderizarImagenes();
                this.actualizarContador();
                this.mostrarMensaje('exito', `¡Imágenes subidas exitosamente!`);
                
                // Limpiar input
                this.inputFile.value = '';
            } else {
                this.mostrarMensaje('error', data.message || 'Error al subir las imágenes.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.mostrarMensaje('error', 'Error de red al subir las imágenes.');
        });
    }

    cargarImagenesExistentes() {
        if (!this.preguntaId) return;

        fetch(`/formularios/${this.preguntaId}/imagenes`)
            .then(response => response.json())
            .then(data => {
                this.imagenesActuales = data.imagenes || [];
                this.renderizarImagenes();
                this.actualizarContador();
            })
            .catch(error => {
                console.error('Error al cargar imágenes:', error);
            });
    }

    renderizarImagenes() {
        if (!this.imagenesGrid) return;

        // Limpiar grid actual
        this.imagenesGrid.innerHTML = '';

        // Renderizar cada imagen
        this.imagenesActuales.forEach((url, index) => {
            const imagenDiv = document.createElement('div');
            imagenDiv.className = 'imagen-preview';
            imagenDiv.innerHTML = `
                <img src="${url}" alt="Imagen ${index + 1}" loading="lazy">
                <button type="button" class="eliminar-imagen-btn" data-index="${index}" title="Eliminar imagen">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            
            // Añadir evento de eliminación
            const btnEliminar = imagenDiv.querySelector('.eliminar-imagen-btn');
            btnEliminar.addEventListener('click', (e) => {
                e.preventDefault();
                this.eliminarImagen(index);
            });

            this.imagenesGrid.appendChild(imagenDiv);
        });
    }

    eliminarImagen(index) {
        const url = this.imagenesActuales[index];
        
        if (!confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
            return;
        }

        // Mostrar estado de carga
        this.mostrarMensaje('advertencia', 'Eliminando imagen...');

        fetch(`/formularios/${this.preguntaId}/imagenes`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                imagen_url: url
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar del array
                this.imagenesActuales.splice(index, 1);
                
                // Actualizar UI
                this.renderizarImagenes();
                this.actualizarContador();
                this.mostrarMensaje('exito', 'Imagen eliminada exitosamente.');
            } else {
                this.mostrarMensaje('error', data.message || 'Error al eliminar la imagen.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.mostrarMensaje('error', 'Error de red al eliminar la imagen.');
        });
    }

    limpiarSeleccion() {
        this.inputFile.value = '';
        this.mostrarMensaje('advertencia', 'Selección de archivos limpiada.');
    }

    actualizarContador() {
        if (!this.contadorImagenes) return;

        const total = this.imagenesActuales.length;
        const texto = this.contadorImagenes.querySelector('.texto-contador');
        const max = this.contadorImagenes.querySelector('.max-imagenes');
        
        if (texto) {
            texto.textContent = `${total} imagen(es) subida(s)`;
        }
        
        if (max) {
            max.textContent = `Máximo: ${this.maxImagenes}`;
        }
    }

    mostrarMensaje(tipo, mensaje) {
        if (!this.mensajeEstado) return;

        this.mensajeEstado.className = `mensaje-estado ${tipo}`;
        this.mensajeEstado.textContent = mensaje;
        this.mensajeEstado.style.display = 'block';

        // Ocultar mensaje después de 5 segundos
        setTimeout(() => {
            this.mensajeEstado.style.display = 'none';
        }, 5000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar todas las instancias de FormularioImagenes en la página
    const contenedoresImagenes = document.querySelectorAll('.imagenes-container');
    contenedoresImagenes.forEach(contenedor => {
        new FormularioImagenes(contenedor);
    });
});

// Exportar para uso en otros módulos
window.FormularioImagenes = FormularioImagenes;
