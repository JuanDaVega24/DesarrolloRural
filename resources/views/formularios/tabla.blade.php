<x-app-layout>

    <div class="tabla-container">
        <div class="tabla-header">
            <div class="header-content">
                <i class="fas fa-table"></i>
                <div>
                    <h1>Tabla de Respuestas</h1>
                    <p>Proyecto: {{ $proyecto->nombre }} ({{ $proyecto->ano }})</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('formularios.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Volver a proyectos
                </a>
                <button onclick="exportarExcel()" class="btn-export">
                    <i class="fas fa-file-excel"></i>
                    Exportar Excel
                </button>
            </div>
        </div>

        <div class="tabla-stats">
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <div>
                    <span class="stat-label">Total Beneficiarios</span>
                    <span class="stat-value">{{ $beneficiarios->count() }}</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-male"></i>
                <div>
                    <span class="stat-label">Hombres</span>
                    <span class="stat-value">{{ $beneficiarios->where('genero', 'Masculino')->count() }}</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-female"></i>
                <div>
                    <span class="stat-label">Mujeres</span>
                    <span class="stat-value">{{ $beneficiarios->where('genero', 'Femenino')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="tabla-content">
            <div class="table-responsive">
                <table class="tabla-respuestas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Completo</th>
                            <th>Cédula</th>
                            <th>Género</th>
                            <th>Corregimiento</th>
                            <th>Vereda</th>
                            <th>Teléfono</th>
                            @foreach($preguntas as $pregunta)
                                <th>{{ $pregunta->pregunta }}</th>
                            @endforeach
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beneficiarios as $index => $beneficiario)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $beneficiario['NOMBRE COMPLETO'] ?? 'N/A' }}</td>
                                <td>{{ $beneficiario['CÉDULA'] ?? 'N/A' }}</td>
                                <td>{{ $beneficiario['GENERO'] ?? 'N/A' }}</td>
                                <td>{{ $beneficiario['CORREGIMIENTO'] ?? 'N/A' }}</td>
                                <td>{{ $beneficiario['VEREDA'] ?? 'N/A' }}</td>
                                <td>{{ $beneficiario['TELÉFONO'] ?? $beneficiario['TELEFONO'] ?? 'N/A' }}</td>
                                @foreach($preguntas as $pregunta)
                                    <td>
                                        @if(isset($beneficiario[$pregunta->pregunta]))
                                            @if($pregunta->tipo_campo === 'checkbox')
                                                @php
                                                    // Limpiar cualquier residuo de HTML o imágenes del valor
                                                    $valorOriginal = $beneficiario[$pregunta->pregunta];
                                                    // Eliminar cualquier etiqueta HTML que pueda haber quedado
                                                    $valorLimpio = strip_tags($valorOriginal);
                                                    // Eliminar espacios extra y dividir por comas
                                                    $valores = array_filter(array_map('trim', explode(', ', $valorLimpio)));
                                                @endphp
                                                <div class="checkbox-values">
                                                    @foreach($valores as $valor)
                                                        <span class="badge badge-checkbox">{{ $valor }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                {{ $beneficiario[$pregunta->pregunta] }}
                                            @endif
                                        @else
                                            <span class="text-muted">No respondido</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <button class="btn-accion btn-editar" onclick="editarBeneficiario({{ $index }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-accion btn-eliminar" onclick="eliminarBeneficiario({{ $index }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($beneficiarios->isEmpty())
            <div class="empty-state">
                <i class="fas fa-table fa-3x"></i>
                <h3>No hay datos registrados</h3>
                <p>Aún no se han registrado beneficiarios para este proyecto.</p>
            </div>
        @endif
    </div>

    <!-- Modal de edición -->
    <div class="modal-overlay" id="modalEdicion" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Beneficiario</h3>
                <button class="modal-close" onclick="cerrarModalEdicion()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdicion">
                    <input type="hidden" id="beneficiarioIndex">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" id="editNombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Cédula</label>
                            <input type="number" id="editCedula" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Género</label>
                            <select id="editGenero" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="number" id="editTelefono" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Corregimiento</label>
                            <select id="editCorregimiento" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Vereda</label>
                            <select id="editVereda" class="form-control" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Preguntas dinámicas -->
                    @foreach($preguntas as $pregunta)
                        <div class="form-group">
                            <label>{{ $pregunta->pregunta }}</label>
                            @if($pregunta->tipo_campo === 'texto' || $pregunta->tipo_campo === 'numero')
                                <input type="{{ $pregunta->tipo_campo === 'texto' ? 'text' : 'number' }}" 
                                       id="editPregunta_{{ $pregunta->id }}" 
                                       class="form-control">
                            @elseif($pregunta->tipo_campo === 'fecha')
                                <input type="date" id="editPregunta_{{ $pregunta->id }}" class="form-control">
                            @elseif($pregunta->tipo_campo === 'select')
                                <select id="editPregunta_{{ $pregunta->id }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @if($pregunta->opciones)
                                        @foreach($pregunta->opciones as $opcion)
                                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($pregunta->tipo_campo === 'checkbox')
                                <div class="checkbox-group">
                                    @if($pregunta->opciones)
                                        @foreach($pregunta->opciones as $opcion)
                                            <label class="checkbox-label">
                                                <input type="checkbox" value="{{ $opcion['texto'] ?? $opcion }}">
                                                {{ $opcion['texto'] ?? $opcion }}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="cerrarModalEdicion()">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarEdicion()">Guardar Cambios</button>
            </div>
        </div>
    </div>

    <script>
        // Datos de beneficiarios
        let beneficiarios = @json($beneficiarios);
        let preguntas = @json($preguntas);

        // Lógica para veredas
        const veredasMap = @json(json_decode(file_get_contents(resource_path('js/veredas.json')), true));
        
        function poblarVeredas(selectId, corregimiento) {
            const select = document.getElementById(selectId);
            if (!select) return;

            // Limpiar opciones
            select.innerHTML = '<option value="">Seleccione vereda</option>';
            
            if (corregimiento && veredasMap[corregimiento]) {
                veredasMap[corregimiento].forEach(vereda => {
                    const option = document.createElement('option');
                    option.value = vereda;
                    option.textContent = vereda;
                    select.appendChild(option);
                });
            }
        }

        // Eventos para edición de veredas
        document.getElementById('editCorregimiento').addEventListener('change', function() {
            poblarVeredas('editVereda', this.value);
        });

        // Funciones de edición
        window.editarBeneficiario = function(index) {
            const beneficiario = beneficiarios[index];
            document.getElementById('beneficiarioIndex').value = index;
            
            // Llenar campos básicos
            document.getElementById('editNombre').value = beneficiario['NOMBRE COMPLETO'] || '';
            document.getElementById('editCedula').value = beneficiario['CÉDULA'] || '';
            document.getElementById('editGenero').value = beneficiario['GENERO'] || '';
            document.getElementById('editTelefono').value = beneficiario['TELÉFONO'] || beneficiario['TELEFONO'] || '';
            document.getElementById('editCorregimiento').value = beneficiario['CORREGIMIENTO'] || '';
            
            // Poblar veredas
            poblarVeredas('editVereda', beneficiario['CORREGIMIENTO']);
            document.getElementById('editVereda').value = beneficiario['VEREDA'] || '';

            // Llenar preguntas dinámicas
            preguntas.forEach(pregunta => {
                const campo = document.getElementById(`editPregunta_${pregunta.id}`);
                if (campo) {
                    if (pregunta.tipo_campo === 'checkbox') {
                        // Para checkboxes, limpiar y marcar según corresponda
                        const checkboxes = campo.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(cb => cb.checked = false);
                        
                        if (beneficiario[pregunta.pregunta]) {
                            const valores = beneficiario[pregunta.pregunta].split(', ');
                            valores.forEach(valor => {
                                const checkbox = campo.querySelector(`input[type="checkbox"][value="${valor}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        }
                    } else {
                        campo.value = beneficiario[pregunta.pregunta] || '';
                    }
                }
            });

            document.getElementById('modalEdicion').style.display = 'flex';
        };

        window.cerrarModalEdicion = function() {
            document.getElementById('modalEdicion').style.display = 'none';
        };

        window.eliminarBeneficiario = function(index) {
            if (confirm('¿Está seguro de que desea eliminar este beneficiario?')) {
                // Aquí deberías hacer una petición AJAX para eliminar el beneficiario
                alert('Funcionalidad de eliminación no implementada aún');
            }
        };

        window.guardarEdicion = function() {
            const index = document.getElementById('beneficiarioIndex').value;
            const beneficiario = beneficiarios[index];

            // Obtener nuevos valores
            beneficiario['NOMBRE COMPLETO'] = document.getElementById('editNombre').value;
            beneficiario['CÉDULA'] = document.getElementById('editCedula').value;
            beneficiario['GENERO'] = document.getElementById('editGenero').value;
            beneficiario['TELÉFONO'] = document.getElementById('editTelefono').value;
            beneficiario['CORREGIMIENTO'] = document.getElementById('editCorregimiento').value;
            beneficiario['VEREDA'] = document.getElementById('editVereda').value;

            // Obtener preguntas dinámicas
            preguntas.forEach(pregunta => {
                const campo = document.getElementById(`editPregunta_${pregunta.id}`);
                if (campo) {
                    if (pregunta.tipo_campo === 'checkbox') {
                        const checkboxes = campo.querySelectorAll('input[type="checkbox"]:checked');
                        const valores = Array.from(checkboxes).map(cb => cb.value);
                        beneficiario[pregunta.pregunta] = valores.join(', ');
                    } else {
                        beneficiario[pregunta.pregunta] = campo.value;
                    }
                }
            });

            // Aquí deberías hacer una petición AJAX para guardar los cambios
            alert('Funcionalidad de guardado no implementada aún');
            cerrarModalEdicion();
            
            // Recargar la página para ver los cambios
            location.reload();
        };

        function exportarExcel() {
            // Crear un enlace temporal para descargar
            const data = [
                ['Nombre Completo', 'Cédula', 'Género', 'Corregimiento', 'Vereda', 'Teléfono', ...preguntas.map(p => p.pregunta)],
                ...beneficiarios.map(b => [
                    b['NOMBRE COMPLETO'] || '',
                    b['CÉDULA'] || '',
                    b['GENERO'] || '',
                    b['CORREGIMIENTO'] || '',
                    b['VEREDA'] || '',
                    b['TELÉFONO'] || '',
                    ...preguntas.map(p => b[p.pregunta] || '')
                ])
            ];

            let csvContent = "data:text/csv;charset=utf-8,";
            data.forEach(row => {
                csvContent += row.join(",") + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "respuestas_{{ $proyecto->nombre }}.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalEdicion').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalEdicion();
            }
        });
    </script>
</x-app-layout>