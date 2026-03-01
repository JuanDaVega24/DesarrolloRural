// Formularios Sesiones - Lógica para manejo de formularios concurrentes

class FormularioSesiones {
    constructor(proyectoId) {
        this.proyectoId = proyectoId;
        this.sesion = null;
        this.datos = [];
        this.usuariosActivos = [];
        this.intervalUsuarios = null;
        this.intervalSincronizacion = null;
        this.intervalGuardado = null;
        this.estaCargando = false;
        
        this.init();
    }

    async init() {
        try {
            this.estaCargando = true;
            // Obtener o crear sesión
            await this.obtenerSesion();
            
            // Iniciar monitoreo de usuarios activos y sincronización cada 5 segundos
            this.iniciarMonitoreoUsuarios();
            this.iniciarSincronizacionBeneficiariosTodasSesiones();
            
            // Cargar datos existentes en la sesión
            this.cargarDatos();
            
            // Iniciar guardado automático cada 20 segundos
            this.iniciarGuardadoAutomatico();
            
            this.estaCargando = false;
            console.log('Formulario colaborativo inicializado');
            
        } catch (error) {
            this.estaCargando = false;
            console.error('Error al inicializar formulario de sesiones:', error);
            
            if (error.message && error.message.includes('finalizado')) {
                this.mostrarAlertaFinalizacion(error.message);
            } else if (error.status === 429) {
                this.mostrarError('Límite de usuarios alcanzado. Intente más tarde.');
            } else {
                this.mostrarError('No se pudo iniciar el modo colaborativo. Los cambios se guardarán localmente hasta que se restablezca la conexión.');
            }
        }
    }

    async obtenerSesion() {
        try {
            const response = await fetch(`/sesiones/${this.proyectoId}`);
            if (!response.ok) {
                const errorData = await response.json();
                const error = new Error(errorData.message || 'Error de servidor');
                error.status = response.status;
                throw error;
            }
            
            const data = await response.json();
            this.sesion = data.sesion;
            this.proyecto = data.proyecto;
            this.preguntas = data.preguntas;
            this.usuario = data.usuario;
            
            this.mostrarInfoSesion();
            
        } catch (error) {
            throw error;
        }
    }

    mostrarInfoSesion() {
        const container = document.getElementById('info-sesion');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-info shadow-sm d-flex justify-content-between align-items-center mb-0">
                    <div>
                        <i class="fas fa-network-wired me-2"></i>
                        <strong>Modo Colaborativo Activo:</strong> Trabajando como <u>${this.usuario.name}</u>
                    </div>
                    <div class="d-flex align-items-center">
                        <span id="sync-status" class="badge bg-success me-2">
                            <i class="fas fa-check-circle me-1"></i>Sincronizado
                        </span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="window.formularioSesiones?.sincronizarManual()">
                            <i class="fas fa-sync-alt me-1"></i>Refrescar
                        </button>
                    </div>
                </div>
            `;
        }
    }

    async sincronizarManual() {
        const btn = document.querySelector('[onclick*="sincronizarManual"]');
        if (btn) {
            const icon = btn.querySelector('i');
            icon.classList.add('fa-spin');
            await this.sincronizarBeneficiariosTodasSesiones();
            await this.obtenerUsuariosActivos();
            setTimeout(() => icon.classList.remove('fa-spin'), 500);
        }
    }

    async obtenerUsuariosActivos() {
        try {
            const response = await fetch(`/sesiones/${this.proyectoId}/usuarios-activos`);
            const data = await response.json();
            
            if (data.success) {
                this.usuariosActivos = data.usuarios_activos;
                this.actualizarListaUsuarios();
            }
        } catch (error) {
            console.error('Error al obtener usuarios activos:', error);
        }
    }

    actualizarListaUsuarios() {
        const container = document.getElementById('usuarios-activos-list');
        if (!container) return;

        if (this.usuariosActivos.length === 0) {
            container.innerHTML = '<div class="p-3 text-center text-muted">No hay otros usuarios activos</div>';
            return;
        }

        const html = this.usuariosActivos.map(usuario => {
            const esActual = usuario.id === this.usuario.id;
            const tiempo = this.formatoTiempo(usuario.ultima_actividad);
            
            return `
                <div class="list-group-item ${esActual ? 'user-actual' : ''}">
                    <div class="user-item">
                        <div class="user-info">
                            <span class="user-name">
                                ${esActual ? '<span class="badge bg-primary me-1">Tú</span>' : ''}
                                ${usuario.name}
                            </span>
                            <span class="user-time"><i class="far fa-clock me-1"></i>${tiempo}</span>
                        </div>
                        <div class="text-end">
                            <span class="badge ${usuario.completada ? 'bg-success' : 'bg-warning text-dark'} d-block mb-1">
                                ${usuario.completada ? 'Completado' : 'Escribiendo...'}
                            </span>
                            <small class="text-muted">${usuario.beneficiarios_count} inscritos</small>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    }

    formatoTiempo(fecha) {
        const ahora = new Date();
        const ultima = new Date(fecha);
        const diff = ahora - ultima;
        const segundos = Math.floor(diff / 1000);
        
        if (segundos < 10) return 'Ahora mismo';
        if (segundos < 60) return `hace ${segundos}s`;
        const minutos = Math.floor(segundos / 60);
        if (minutos < 60) return `hace ${minutos}m`;
        return 'hace más de 1h';
    }

    iniciarMonitoreoUsuarios() {
        if (this.intervalUsuarios) clearInterval(this.intervalUsuarios);
        this.intervalUsuarios = setInterval(() => this.obtenerUsuariosActivos(), 5000);
    }

    iniciarSincronizacionBeneficiariosTodasSesiones() {
        if (this.intervalSincronizacion) clearInterval(this.intervalSincronizacion);
        this.intervalSincronizacion = setInterval(() => this.sincronizarBeneficiariosTodasSesiones(), 5000);
    }

    cargarDatos() {
        if (this.sesion && this.sesion.datos_beneficiarios) {
            // Sincronizar con el estado local de show.blade.php si es necesario
            if (window.setBeneficiariosLocales) {
                window.setBeneficiariosLocales(this.sesion.datos_beneficiarios);
            }
        }
    }

    async guardarDatos(datos) {
        const syncStatus = document.getElementById('sync-status');
        if (syncStatus) {
            syncStatus.className = 'badge bg-warning text-dark me-2';
            syncStatus.innerHTML = '<i class="fas fa-sync fa-spin me-1"></i>Guardando...';
        }

        try {
            const response = await fetch(`/sesiones/${this.proyectoId}/actualizar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    datos_beneficiarios: datos || this.getDatosLocales()
                })
            });

            const data = await response.json();
            
            if (data.success) {
                if (syncStatus) {
                    syncStatus.className = 'badge bg-success me-2';
                    syncStatus.innerHTML = '<i class="fas fa-check-circle me-1"></i>Sincronizado';
                }
                return true;
            }
            throw new Error(data.message || 'Error al guardar');
            
        } catch (error) {
            if (syncStatus) {
                syncStatus.className = 'badge bg-danger me-2';
                syncStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Error al sincronizar';
            }
            console.error('Error al guardar datos:', error);
            return false;
        }
    }

    iniciarGuardadoAutomatico() {
        if (this.intervalGuardado) clearInterval(this.intervalGuardado);
        this.intervalGuardado = setInterval(() => {
            if (!this.sesion?.completada) {
                this.guardarDatos();
            }
        }, 20000);
    }

    getDatosLocales() {
        // Obtener datos del array 'beneficiarios' definido en show.blade.php
        return window.beneficiarios || [];
    }

    async sincronizarBeneficiariosTodasSesiones() {
        try {
            const response = await fetch(`/sesiones/${this.proyectoId}/beneficiarios-todos`);
            const data = await response.json();

            if (data.success) {
                this.actualizarListaBeneficiariosTodasSesiones(data.beneficiarios);
                
                const totalCounter = document.getElementById('total-beneficiarios-todos');
                if (totalCounter) {
                    totalCounter.textContent = data.total_beneficiarios;
                }
            }
        } catch (error) {
            console.error('Error al sincronizar beneficiarios:', error);
        }
    }

    actualizarListaBeneficiariosTodasSesiones(beneficiarios) {
        const container = document.getElementById('all-beneficiarios-container');
        if (!container) return;

        if (beneficiarios.length === 0) {
            container.innerHTML = '<div class="text-muted text-center py-2">No hay registros de otros usuarios</div>';
            return;
        }

        // Agrupar por usuario para mostrar un resumen limpio
        const porUsuario = {};
        beneficiarios.forEach(b => {
            const u = b.usuario_sesion || 'Otro';
            if (!porUsuario[u]) porUsuario[u] = 0;
            porUsuario[u]++;
        });

        const html = Object.entries(porUsuario).map(([user, count]) => `
            <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                <span class="small"><i class="fas fa-user-circle me-1 text-primary"></i>${user}</span>
                <span class="badge bg-light text-dark border">${count}</span>
            </div>
        `).join('');

        container.innerHTML = `<div class="all-beneficiarios-summary">${html}</div>`;
    }

    async completarSesion() {
        const datos = this.getDatosLocales();
        if (datos.length === 0) {
            alert('Debe agregar al menos un beneficiario para completar su parte.');
            return false;
        }

        try {
            const response = await fetch(`/sesiones/${this.proyectoId}/completar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ datos_beneficiarios: datos })
            });

            const data = await response.json();
            if (data.success) {
                this.sesion.completada = true;
                this.mostrarMensajeFinalizacion();
                return true;
            }
            alert(data.message || 'Error al completar la sesión');
            return false;
        } catch (error) {
            console.error('Error:', error);
            return false;
        }
    }

    mostrarMensajeFinalizacion() {
        const formSection = document.getElementById('beneficiario-form');
        const actionsSection = document.querySelector('.beneficiario-actions-section');
        
        if (formSection) formSection.style.opacity = '0.5';
        if (actionsSection) actionsSection.innerHTML = `
            <div class="alert alert-success w-100 text-center">
                <i class="fas fa-check-double me-2"></i>
                <strong>¡Has completado tu parte!</strong><br>
                Tus datos han sido guardados. Puedes seguir viendo el progreso de tus compañeros.
            </div>
        `;
    }

    mostrarAlertaFinalizacion(mensaje) {
        const container = document.querySelector('.form-container');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-warning shadow p-4 text-center">
                    <i class="fas fa-flag-checkered fa-3x mb-3 text-warning"></i>
                    <h3>Proyecto Finalizado</h3>
                    <p>${mensaje}</p>
                    <hr>
                    <a href="/formularios" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a la lista de proyectos
                    </a>
                </div>
            `;
        }
    }

    async fusionarSesiones() {
        if (!confirm('¿Está seguro de que desea finalizar y fusionar todas las sesiones? Esta acción consolidará los datos de todos los usuarios y marcará el proyecto como completado.')) {
            return;
        }

        try {
            const response = await fetch(`/sesiones/${this.proyectoId}/fusionar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                alert(`¡Éxito! Se han fusionado ${data.beneficiarios_agregados} beneficiarios. Total en el proyecto: ${data.total_beneficiarios}.`);
                window.location.href = '/formularios';
            } else {
                alert(data.message || 'Error al fusionar sesiones');
                if (data.conflictos && data.conflictos.length > 0) {
                    console.warn('Conflictos encontrados:', data.conflictos);
                }
            }
        } catch (error) {
            console.error('Error al fusionar:', error);
            alert('Error de red al intentar fusionar las sesiones.');
        }
    }

    mostrarError(mensaje) {
        console.error(mensaje);
        // Podrías usar un toast aquí
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const proyectoId = document.getElementById('proyecto-id')?.value;
    if (proyectoId) {
        window.formularioSesiones = new FormularioSesiones(proyectoId);
    }
});
