// Manejar envío del formulario de upload AJAX para caracterizaciones
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ Script de upload de caracterizaciones cargado');

    const uploadForm = document.querySelector('.upload-form');
    console.log('Formulario encontrado:', !!uploadForm);

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('✓ Envío del formulario interceptado');

            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const btnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Subiendo...';

            console.log('Enviando a:', this.action);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Respuesta status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    console.log('✓ Upload exitoso, mostrando modal');
                    mostrarModalExito(data.message);
                    uploadForm.reset();
                    
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                alert('Error al subir el archivo: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = btnText;
            });
        });
    }
});

// Función para mostrar modal de éxito
function mostrarModalExito(mensaje) {
    console.log('Mostrando modal de éxito con mensaje:', mensaje);
    
    const modalExitoContainer = document.getElementById('modalExitoContainer');
    const modal = document.getElementById('exampleModalExito');
    
    console.log('Contenedor existe:', !!modalExitoContainer);
    console.log('Modal existe:', !!modal);

    if (modalExitoContainer && modal) {
        // Actualizar texto
        const textoModal = modal.querySelector('.modal-text-govco');
        if (textoModal && mensaje) {
            textoModal.textContent = mensaje;
        }

        // Mostrar modal
        modal.classList.add('show');
        modalExitoContainer.classList.add('show');
        document.body.style.overflow = 'hidden';
        console.log('✓ Modal mostrado');

        const backdrop = modalExitoContainer.querySelector('.modal-backdrop-govco');
        const btnAceptar = modal.querySelector('.btn-exito-aceptar');
        const btnClose = modal.querySelector('.btn-close');

        // Función para cerrar
        const cerrar = () => {
            console.log('Cerrando modal');
            modal.classList.remove('show');
            modalExitoContainer.classList.remove('show');
            document.body.style.overflow = '';
        };

        // Eventos para cerrar
        if (btnAceptar) {
            btnAceptar.addEventListener('click', cerrar);
        }
        if (btnClose) {
            btnClose.addEventListener('click', cerrar);
        }
        if (backdrop) {
            backdrop.addEventListener('click', cerrar);
        }

        // Cerrar con Escape
        document.addEventListener('keydown', function handleEscape(e) {
            if (e.key === 'Escape') {
                cerrar();
                document.removeEventListener('keydown', handleEscape);
            }
        });
    } else {
        console.error('Modal no encontrado en el DOM');
    }
}
