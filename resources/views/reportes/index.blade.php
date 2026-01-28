<x-app-layout>
   @vite(['resources/css/pages/reportes/index.css'])

    <div class="reportes-container">
        <div class="content-wrapper">

            {{-- === COMPACT HEADER === --}}
            <div class="page-header">
                <div class="header-content">
                    <h1>Reportes y Estadísticas</h1>
                    <p>Genera reportes avanzados y visualiza estadísticas del sistema</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>

            {{-- === ERROR ALERT === --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- === SUCCESS ALERT (modal) === --}}
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    mostrarModalGovco('exampleModalExito', "{{ session('success') }}");
                });
            </script>
            @endif

            {{-- === PANEL REPORTES === --}}
            <div class="reportes-grid">
                <!-- Estadísticas de Corregimientos -->
                <a href="{{ route('reportes.estadisticas-corregimientos') }}" class="reportes-panel">
                    <div class="estadisticas-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="reportes-title">Distribución por Corregimientos de la Cz</h3>
                    <p class="reportes-description">
                        Visualiza la distribución de personas caracterizadas por corregimientos (1, 2, 3) en un gráfico interactivo.
                    </p>
                    <div class="card-footer">
        <span class="card-link btn px-4 py-2 text-white">
            Acceder al módulo
            <i class="fas fa-arrow-right ms-2"></i>
        </span>
    </div>
                </a>

                <!-- Área por corregimientos (Proyectos productivos) -->
                <a href="{{ route('reportes.area-proyectos') }}" class="reportes-panel">
                    <div class="reportes-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="reportes-title">Área por corregimientos (Proyectos productivos)</h3>
                    <p class="reportes-description">
                        Analiza la superficie agrícola por corregimiento para proyectos productivos específicos mediante selección interactiva.
                    </p>
                    <div class="card-footer">
                        <span class="card-link btn px-4 py-2 text-white">
                            Acceder al módulo
                            <i class="fas fa-arrow-right ms-2"></i>
                        </span>
                    </div>
                </a>

            </div>

            {{-- === MODAL ESTADÍSTICAS CORREGIMIENTOS === --}}
            <div class="container-modal-govco">
                <div class="modal-container-govco" id="modalEstadisticasCorregimientos" tabindex="-1" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-govco modal-lg">
                        <div class="modal-content modal-content-govco">
                            <div class="modal-header modal-header-govco">
                                <h3 class="modal-title-govco">Distribución por Corregimientos</h3>
                                <button type="button" class="btn-close" onclick="cerrarModalEstadisticas()"></button>
                            </div>
                            <div class="modal-body modal-body-govco">
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="corregimientosChart" width="300" height="300"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="estadisticas-detalles" class="mt-3">
                                            <h5>Detalles por Corregimiento</h5>
                                            <div id="detalles-lista" class="mt-3">
                                                <!-- Los detalles se cargarán aquí -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer-govco">
                                <div class="modal-buttons-govco">
                                    <button type="button" class="btn-modal-govco" onclick="cerrarModalEstadisticas()">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- === MODAL GOV.CO - Confirmación === --}}
    <div class="container-modal-govco">
        <div class="modal-container-govco" id="exampleModalConfirmacion" tabindex="-1" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-govco">
                <div class="modal-content modal-content-govco">
                    <div class="modal-header modal-header-govco">
                        <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                    </div>
                    <div class="modal-body modal-body-govco center-elements-govco">
                <div class="modal-icon">
                    <span class="govco-icon govco-info-circle"></span>
                </div>
                <h3 class="modal-title-govco confirmation-govco">
                    ¿Confirmar Acción?
                </h3>
                <p class="modal-text-govco modal-text-center-govco">
                    ¿Está seguro de que desea realizar esta acción?
                </p>
            </div>
            <div class="modal-footer-govco">
                <div class="modal-buttons-govco">
                    <button type="button" class="btn-modal-govco">
                        Confirmar
                    </button>
                    <button type="button" class="btn-modal-govco btn-contorno">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL GOV.CO - Éxito === --}}
    <div class="container-modal-govco">
        <div class="modal-container-govco" id="exampleModalExito" tabindex="-1" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-govco">
                <div class="modal-content modal-content-govco">
                    <div class="modal-header modal-header-govco">
                        <button type="button" disabled class="btn-close" aria-label="cerrar"></button>
                    </div>
                    <div class="modal-body modal-body-govco center-elements-govco">
                <div class="modal-icon">
                    <span class="govco-icon govco-check-circle"></span>
                </div>
                <h3 class="modal-title-govco success-govco">
                    ¡Operación Exitosa!
                </h3>
                <p class="modal-text-govco modal-text-center-govco">
                    La operación se realizó correctamente.
                </p>
            </div>
            <div class="modal-footer-govco">
                <div class="modal-buttons-govco" style="justify-content: center;">
                    <button type="button" class="btn-modal-govco">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- === CHART.JS === --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- === JAVASCRIPT === --}}
    <script>
        let corregimientosChart = null;

        // Función para mostrar estadísticas de corregimientos
        async function mostrarEstadisticasCorregimientos() {
            try {
                const modal = document.getElementById('modalEstadisticasCorregimientos');
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';

                let backdrop = document.querySelector('.modal-backdrop-govco');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop-govco';
                    document.body.appendChild(backdrop);
                }
                backdrop.style.display = 'block';

                // Cargar datos y crear gráfico
                await cargarEstadisticasCorregimientos();

            } catch (error) {
                console.error('Error mostrando estadísticas:', error);
                alert('Error al cargar las estadísticas');
            }
        }

        // Función para cerrar modal de estadísticas
        function cerrarModalEstadisticas() {
            const modal = document.getElementById('modalEstadisticasCorregimientos');
            modal.classList.remove('show');
            document.body.style.overflow = '';

            const backdrop = document.querySelector('.modal-backdrop-govco');
            if (backdrop) backdrop.style.display = 'none';

            // Destruir gráfico si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
                corregimientosChart = null;
            }
        }

        // Función para cargar datos y crear gráfico
        async function cargarEstadisticasCorregimientos() {
            try {
                // Mostrar indicadores de carga
                document.getElementById('chart-loading').style.display = 'block';
                document.getElementById('corregimientosChart').style.display = 'none';
                document.getElementById('detalles-lista').innerHTML = `
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="text-muted d-block mt-1">Cargando detalles...</small>
                    </div>
                `;

                const response = await fetch('/api/reportes/estadisticas-corregimientos');
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Error al obtener datos');
                }

                // Ocultar loading y mostrar contenido
                document.getElementById('chart-loading').style.display = 'none';
                document.getElementById('corregimientosChart').style.display = 'block';

                // Crear gráfico pastel
                crearGraficoPastel(data.chartData);

                // Mostrar detalles
                mostrarDetallesCorregimientos(data.detalles);

            } catch (error) {
                console.error('Error cargando estadísticas:', error);
                document.getElementById('chart-loading').innerHTML = '<div class="alert alert-danger">Error al cargar el gráfico</div>';
                document.getElementById('detalles-lista').innerHTML =
                    '<div class="alert alert-danger">Error al cargar los datos</div>';
            }
        }

        // Función para crear gráfico pastel
        function crearGraficoPastel(chartData) {
            const ctx = document.getElementById('corregimientosChart').getContext('2d');

            // Destruir gráfico anterior si existe
            if (corregimientosChart) {
                corregimientosChart.destroy();
            }

            corregimientosChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: chartData.colors,
                        borderColor: chartData.colors.map(color => color.replace('0.8', '1')),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} personas (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Función para mostrar detalles por corregimiento
        function mostrarDetallesCorregimientos(detalles) {
            const container = document.getElementById('detalles-lista');
            let html = '';

            Object.values(detalles).forEach(corregimiento => {
                const porcentaje = corregimiento.count > 0 ?
                    Math.round((corregimiento.count / Object.values(detalles).reduce((sum, c) => sum + c.count, 0)) * 100) : 0;

                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <span class="fw-bold">${corregimiento.nombre}</span>
                        <span class="badge bg-primary">${corregimiento.count} personas (${porcentaje}%)</span>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Función para mostrar modal de éxito
        window.mostrarModalGovco = function(modalId, mensaje) {
            const modal = document.getElementById(modalId);
            const titulo = modal.querySelector('.modal-title-govco');
            const texto = modal.querySelector('.modal-text-govco');
            const btnAceptar = modal.querySelector('.btn-exito-aceptar');

            // Personalizar según el mensaje
            if (mensaje.includes('creado') || mensaje.includes('guardado') || mensaje.includes('registrado')) {
                titulo.textContent = '¡Operación Creada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('eliminado')) {
                titulo.textContent = '¡Operación Eliminada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('actualizado') || mensaje.includes('subido')) {
                titulo.textContent = '¡Operación Actualizada!';
                texto.textContent = mensaje;
            } else if (mensaje.includes('Excel') || mensaje.includes('datos')) {
                titulo.textContent = '¡Datos Procesados!';
                texto.textContent = mensaje;
            } else {
                titulo.textContent = '¡Operación Exitosa!';
                texto.textContent = mensaje;
            }

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            let backdrop = document.querySelector('.modal-backdrop-govco');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop-govco';
                document.body.appendChild(backdrop);
            }
            backdrop.style.display = 'block';

            const cerrarModal = function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
                if (backdrop) backdrop.style.display = 'none';
                document.removeEventListener('keydown', handleEscapeKey);
            };

            const handleEscapeKey = function(event) {
                if (event.key === 'Escape') cerrarModal();
            };
            document.addEventListener('keydown', handleEscapeKey);

            if (btnAceptar) {
                const nuevoBtn = btnAceptar.cloneNode(true);
                btnAceptar.parentNode.replaceChild(nuevoBtn, btnAceptar);
                nuevoBtn.addEventListener('click', cerrarModal);
            }

            if (backdrop) backdrop.addEventListener('click', cerrarModal);
        };
    </script>

</x-app-layout>
