// Configuración global (se establece desde el template)
const Config = window.CaracterizacionesConfig || {};

document.addEventListener('DOMContentLoaded', function() {
    // Variables principales
    const toggleFullscreen = document.getElementById('toggleFullscreen');
    const toggleFilters = document.getElementById('toggleFilters');
    const caracterizacionContainer = document.querySelector('.caracterizacion-container');
    const searchInput = document.getElementById('searchInput');
    const exportBtn = document.getElementById('exportBtn');
    const filterRow = document.querySelector('.filter-row');

    // Variables para datos y virtualización
    let allData = []; // Todos los datos originales
    let filteredData = []; // Datos filtrados
    let currentPage = 1;
    let pageSize = 50; // Filas por página (reducido para mejor rendimiento)
    let activeFilters = {}; // Filtros activos por columna
    let searchTerm = ''; // Término de búsqueda
    let isInitialized = false;

    // Elementos DOM
    const tableBody = document.querySelector('.table tbody');
    const paginationContainer = document.getElementById('client-pagination');
    const infoCard = document.querySelector('.info-card');
    const recordsInfo = document.getElementById('records-info');

    // Inicializar datos desde el servidor
    function initializeData() {
        // Obtener datos del DOM (pasados desde Blade)
        const dataScript = document.querySelector('script[data-table-data]');
        if (dataScript) {
            try {
                const tableData = JSON.parse(dataScript.textContent);
                allData = tableData.rows || [];
                filteredData = [...allData];

                // Remover placeholder de carga ya que tenemos datos iniciales renderizados
                const loadingPlaceholder = document.querySelector('.table-loading-placeholder');
                if (loadingPlaceholder) {
                    loadingPlaceholder.remove();
                }

                // Actualizar componentes de interfaz
                updatePagination();
                updateInfoCard();
                updateFilterIcons();
                isInitialized = true;

                // Opcional: Log para debugging
                console.log(`Caracterizaciones cargadas: ${allData.length} registros (${Math.min(25, allData.length)} mostrados inicialmente)`);
            } catch (e) {
                console.error('Error parsing table data:', e);
                // Fallback: mostrar mensaje de error
                const tbody = document.querySelector('.table tbody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="' + Config.headers.length + '" class="text-center py-5 text-danger">Error al cargar los datos</td></tr>';
                }
            }
        } else {
            console.warn('No se encontraron datos de tabla en el DOM');
        }
    }

    // Función de filtrado cliente-side optimizada
    function applyClientFilters() {
        filteredData = [...allData];

        // Aplicar filtros de columna
        Object.keys(activeFilters).forEach(column => {
            const selectedValues = activeFilters[column];
            if (selectedValues && selectedValues.length > 0) {
                filteredData = filteredData.filter(row => {
                    const cellValue = String(row[column] || '').trim();
                    return selectedValues.includes(cellValue);
                });
            }
        });

        // Aplicar búsqueda global
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            filteredData = filteredData.filter(row => {
                return Object.values(row).some(value =>
                    String(value || '').toLowerCase().includes(term)
                );
            });
        }

        currentPage = 1; // Reset a primera página
        renderTable();
        updatePagination();
        updateInfoCard();
        updateFilterIcons();
    }

    // Renderizado virtualizado de tabla (solo filas visibles)
    function renderTable() {
        if (!tableBody) return;

        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = startIndex + pageSize;
        const visibleRows = filteredData.slice(startIndex, endIndex);

        // Renderizar solo las filas visibles
        const html = visibleRows.map(row => {
            return '<tr>' + Config.headers.map(header =>
                `<td>${row[header] || ''}</td>`
            ).join('') + '</tr>';
        }).join('');

        tableBody.innerHTML = html;
    }

    // Actualizar paginación cliente-side
    function updatePagination() {
        if (!paginationContainer) return;

        const totalPages = Math.ceil(filteredData.length / pageSize);
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHtml = '<nav aria-label="Navegación de páginas"><ul class="pagination justify-content-center">';

        // Botón Anterior
        if (currentPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
        }

        // Páginas (mostrar máximo 5 páginas)
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
            if (startPage > 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }

        // Botón Siguiente
        if (currentPage < totalPages) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.innerHTML = paginationHtml;
    }

    // Actualizar información en la tarjeta
    function updateInfoCard() {
        if (!infoCard) return;

        const spans = infoCard.querySelectorAll('span strong');
        if (spans.length >= 3) {
            const totalFiltered = filteredData.length;
            const totalOriginal = allData.length;

            spans[0].nextSibling.textContent = ` ${totalFiltered}`;
            spans[1].nextSibling.textContent = ` ${currentPage} de ${Math.ceil(totalFiltered / pageSize) || 1}`;
            spans[2].nextSibling.textContent = ` ${pageSize}`;
        }

        updateRecordsInfo();
    }

    // Actualizar contador de registros
    function updateRecordsInfo() {
        if (!recordsInfo) return;

        const totalFiltered = filteredData.length;
        const totalOriginal = allData.length;
        const visibleCount = Math.min(pageSize, totalFiltered - (currentPage - 1) * pageSize);

        recordsInfo.textContent = `${totalFiltered} caracterizaciones de ${totalOriginal}`;
    }

    // Actualizar iconos de filtro activos
    function updateFilterIcons() {
        const filterIcons = document.querySelectorAll('.filter-icon');
        filterIcons.forEach(icon => {
            const column = icon.getAttribute('data-column');
            const hasActiveFilters = activeFilters[column] && activeFilters[column].length > 0;

            if (hasActiveFilters) {
                icon.classList.add('filter-active');
            } else {
                icon.classList.remove('filter-active');
            }
        });
    }

    // Pantalla completa
    if (toggleFullscreen) {
        toggleFullscreen.addEventListener('click', function() {
            caracterizacionContainer.classList.toggle('fullscreen-mode');

            const icon = this.querySelector('i');
            if (icon.classList.contains('bi-arrows-fullscreen')) {
                icon.classList.remove('bi-arrows-fullscreen');
                icon.classList.add('bi-fullscreen-exit');
                this.setAttribute('title', 'Salir de pantalla completa');
            } else {
                icon.classList.remove('bi-fullscreen-exit');
                icon.classList.add('bi-arrows-fullscreen');
                this.setAttribute('title', 'Pantalla completa');
            }
        });
    }

    // Alternar filtros
    if (toggleFilters) {
        toggleFilters.addEventListener('click', function() {
            if (filterRow.classList.contains('d-none')) {
                filterRow.classList.remove('d-none');
                this.innerHTML = '<i class="bi bi-funnel"></i> Eliminar filtros';
                this.setAttribute('title', 'Eliminar filtros');
            } else {
                filterRow.classList.add('d-none');
                this.innerHTML = '<i class="bi bi-funnel"></i> Activar filtros';
                this.setAttribute('title', 'Activar filtros');
                // Limpiar filtros aplicados
                activeFilters = {};
                document.querySelectorAll('.column-filter').forEach(cb => cb.checked = false);
                applyClientFilters();
            }
        });
    }

    // Búsqueda en tabla (cliente-side)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            searchTerm = this.value.toLowerCase().trim();

            // Debounce para mejor rendimiento
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyClientFilters();
            }, 150);
        });
    }

    // Exportar a Excel
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Construir URL con filtros activos del cliente
            let exportUrl = Config.exportUrl;
            const params = new URLSearchParams();

            // Usar filtros activos del cliente-side
            for (const [column, values] of Object.entries(activeFilters)) {
                if (values && values.length > 0) {
                    values.forEach(value => {
                        params.append(`filters[${column}][]`, value);
                    });
                }
            }

            // Agregar término de búsqueda si existe
            if (searchTerm) {
                params.append('search', searchTerm);
            }

            if (params.toString()) {
                exportUrl += '?' + params.toString();
            }

            // Redireccionar a la URL de exportación con filtros
            window.location.href = exportUrl;

            // Feedback visual
            const originalHTML = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="bi bi-check-circle"></i>';
            exportBtn.classList.add('btn-success');
            exportBtn.classList.remove('btn-light');

            setTimeout(() => {
                exportBtn.innerHTML = originalHTML;
                exportBtn.classList.remove('btn-success');
                exportBtn.classList.add('btn-light');
            }, 2000);
        });
    }

    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F para buscar
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }

        // F11 o Escape para pantalla completa
        if (e.key === 'F11' || (e.key === 'Escape' && caracterizacionContainer.classList.contains('fullscreen-mode'))) {
            e.preventDefault();
            if (toggleFullscreen) toggleFullscreen.click();
        }
    });

    // Auto-focus en búsqueda con un pequeño delay
    setTimeout(() => {
        if (searchInput && allData.length > 0) {
            searchInput.focus();
        }
    }, 500);

    // Event listeners para filtros de columna (cliente-side)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('column-filter')) {
            const checkbox = e.target;
            const column = checkbox.getAttribute('data-column');
            const value = checkbox.value;

            // Actualizar filtros activos
            if (!activeFilters[column]) {
                activeFilters[column] = [];
            }

            if (checkbox.checked) {
                if (!activeFilters[column].includes(value)) {
                    activeFilters[column].push(value);
                }
            } else {
                activeFilters[column] = activeFilters[column].filter(v => v !== value);
            }

            applyClientFilters();
        }
    });

    // Event listener para paginación (delegación de eventos)
    if (paginationContainer) {
        paginationContainer.addEventListener('click', function(e) {
            e.preventDefault();
            const link = e.target.closest('.page-link');
            if (link && !link.parentElement.classList.contains('disabled') && !link.parentElement.classList.contains('active')) {
                const page = parseInt(link.getAttribute('data-page'));
                if (page && page > 0) {
                    currentPage = page;
                    renderTable();
                    updatePagination();
                    updateInfoCard();
                }
            }
        });
    }

    // Inicializar datos al cargar la página
    initializeData();
});

// Función para mostrar modal de éxito
window.mostrarModalGovco = function(modalId, mensaje) {
    const modal = document.getElementById(modalId);
    const titulo = modal.querySelector('.modal-title-govco');
    const texto = modal.querySelector('.modal-text-govco');
    const btnAceptar = modal.querySelector('.btn-exito-aceptar');

    // Personalizar según el mensaje
    if (mensaje.includes('creado') || mensaje.includes('guardado') || mensaje.includes('registrado')) {
        titulo.textContent = '¡Caracterización Creada!';
        texto.textContent = mensaje;
    } else if (mensaje.includes('eliminado')) {
        titulo.textContent = '¡Caracterización Eliminada!';
        texto.textContent = mensaje;
    } else if (mensaje.includes('actualizado') || mensaje.includes('subido')) {
        titulo.textContent = '¡Caracterización Actualizada!';
        texto.textContent = mensaje;
    } else if (mensaje.includes('Excel') || mensaje.includes('datos')) {
        titulo.textContent = '¡Datos Cargados!';
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

// Función para confirmar eliminación
function confirmarEliminacion(event, caracterizacionNombre) {
    event.preventDefault();

    const modal = document.getElementById('exampleModalConfirmacion');
    const modalContainer = document.getElementById('modalConfirmacionContainer');
    const titulo = modal.querySelector('.modal-title-govco');
    const texto = modal.querySelector('.modal-text-govco');
    const btnConfirmar = document.querySelector('.btn-eliminar-confirmar');
    const btnCancelar = document.querySelector('.btn-eliminar-cancelar');

    // Personalizar contenido del modal
    titulo.textContent = '¿Eliminar Caracterización?';
    texto.innerHTML = '¿Está seguro de que desea eliminar la caracterización "<strong>' + caracterizacionNombre + '</strong>"? Esta acción no se puede deshacer.';

    // Mostrar modal y backdrop - agregar clase show al contenedor
    modal.classList.add('show');
    modalContainer.classList.add('show');
    document.body.style.overflow = 'hidden';

    const backdrop = modalContainer.querySelector('.modal-backdrop-govco');

    // Función para cerrar modal
    const cerrarModal = function() {
        modal.classList.remove('show');
        modalContainer.classList.remove('show');
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleEscapeKey);
    };

    // Event listener para Escape
    const handleEscapeKey = function(event) {
        if (event.key === 'Escape') {
            cerrarModal();
        }
    };
    document.addEventListener('keydown', handleEscapeKey);

    // Configurar botón confirmar
    if (btnConfirmar) {
        const nuevoBtnConfirmar = btnConfirmar.cloneNode(true);
        btnConfirmar.parentNode.replaceChild(nuevoBtnConfirmar, btnConfirmar);
        nuevoBtnConfirmar.addEventListener('click', function() {
            event.target.closest('form').submit();
            cerrarModal();
        });
    }

    // Configurar botón cancelar
    if (btnCancelar) {
        const nuevoBtnCancelar = btnCancelar.cloneNode(true);
        btnCancelar.addEventListener('click', cerrarModal);
    }

    // Cerrar con click en backdrop
    if (backdrop) {
        backdrop.addEventListener('click', cerrarModal);
    }
}