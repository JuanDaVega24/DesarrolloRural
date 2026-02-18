<x-app-layout>
    <style>
        /* === ESTILOS ESPECÍFICOS DE ESTA PÁGINA === */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .header-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--negro);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-content p {
            color: var(--gris);
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-input {
            width: 280px;
            border-radius: 0.5rem;
            border: 1px solid #cfe3d3;
            padding: 0.5rem 0.75rem;
            outline: none;
        }
        .search-input:focus {
            border-color: #4A7C2F;
            box-shadow: 0 0 0 3px rgba(74,124,47,0.15);
        }

        .pecuarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .pecuario-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            border-top: 4px solid #4A7C2F;
        }

        .pecuario-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .pecuario-icon {
            font-size: 1.5rem;
            color: #4A7C2F;
        }

        .pecuario-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2f5d23;
        }

        .card-body .total-count {
            font-size: 2rem;
            font-weight: 800;
            color: #4A7C2F;
            text-align: center;
            margin-bottom: 1rem;
        }

        .card-body .total-label {
            font-size: 0.9rem;
            color: var(--gris);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .details-section {
            margin-top: 1rem;
        }

        .details-title {
            font-size: 1rem;
            font-weight: 600;
            color: #2f5d23;
            margin-bottom: 0.5rem;
        }

        .details-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .details-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .details-list li:last-child {
            border-bottom: none;
        }

        .details-list .name {
            color: #3b8d2c;
        }

        .details-list .count {
            font-weight: 600;
            color: var(--negro);
        }

        /* Acordeón de Corregimientos y Veredas */
        .expand-btn {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: #4A7C2F;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
        }

        .expand-btn:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .expand-btn i {
            transition: transform 0.3s;
        }

        .expand-btn.active i {
            transform: rotate(180deg);
        }

        .expand-content {
            display: none;
            margin-top: 1rem;
            border-top: 1px dashed #e2e8f0;
            padding-top: 1rem;
        }

        .expand-content.show {
            display: block;
        }

        .corregimiento-item {
            margin-bottom: 1rem;
            border: 1px solid #f1f5f9;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .corregimiento-header {
            background: #f8fafc;
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 1px solid #f1f5f9;
        }

        .corregimiento-header:hover {
            background: #f1f5f9;
        }

        .veredas-list {
            display: none;
            padding: 0.5rem 1rem;
            background: white;
            list-style: none;
            margin: 0;
        }

        .veredas-list.show {
            display: block;
        }

        .vereda-item {
            display: flex;
            justify-content: space-between;
            padding: 0.35rem 0;
            font-size: 0.85rem;
            color: #4a5568;
            border-bottom: 1px solid #f8fafc;
        }

        .vereda-item:last-child {
            border-bottom: none;
        }

        .vereda-count {
            font-weight: 600;
            color: #4A7C2F;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="header-content">
                <h1>Estadísticas de Actividades Pecuarias</h1>
                <p>Análisis detallado de las actividades pecuarias por corregimiento y vereda.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('filtros.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
                <input id="pecuarioSearch" class="search-input" type="text" placeholder="Buscar actividad pecuaria..." list="pecuarioList" />
            </div>
        </div>

        @if(empty($pecuariosData))
            <div class="no-data-message">
                <p>No se encontraron datos de actividades pecuarias para mostrar.</p>
            </div>
        @else
            <div class="pecuarios-grid">
                @foreach($pecuariosData as $pecuario => $data)
                    <div class="pecuario-card" data-pecuario="{{ strtolower($pecuario) }}">
                        <div class="card-header">
                          
                            <h2 class="pecuario-title">{{ $pecuario }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="total-count">{{ $data['total'] }}</div>
                            <div class="total-label">Total de registros</div>

                            @if(!empty($data['corregimientos']))
                                <button class="expand-btn" onclick="toggleDetails('{{ Str::slug($pecuario) }}')">
                                    <i class="fas fa-chevron-down"></i>
                                    <span>Ver Corregimientos</span>
                                </button>

                                <div id="details-{{ Str::slug($pecuario) }}" class="expand-content">
                                    @foreach($data['corregimientos'] as $corregimiento => $cData)
                                        <div class="corregimiento-item">
                                            <div class="corregimiento-header" onclick="toggleVeredas(this)">
                                                <span><i class="fas fa-map-marker-alt me-2 text-success"></i>{{ $corregimiento }}</span>
                                                <span class="badge bg-success rounded-pill">{{ $cData['total'] }}</span>
                                            </div>
                                            <ul class="veredas-list">
                                                @foreach($cData['veredas'] as $vereda => $count)
                                                    <li class="vereda-item">
                                                        <span>{{ $vereda }}</span>
                                                        <span class="vereda-count">{{ $count }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <datalist id="pecuarioList">
                @foreach(array_keys($pecuariosData) as $p)
                    <option value="{{ $p }}"></option>
                @endforeach
            </datalist>
        @endif
    </div>
    <script>
        function toggleDetails(slug) {
            const content = document.getElementById(`details-${slug}`);
            const btn = content.previousElementSibling;

            content.classList.toggle('show');
            btn.classList.toggle('active');

            const icon = btn.querySelector('i');
            const span = btn.querySelector('span');

            if (content.classList.contains('show')) {
                span.textContent = 'Ocultar Detalles';
            } else {
                span.textContent = 'Ver Corregimientos';
            }
        }

        function toggleVeredas(header) {
            const list = header.nextElementSibling;
            list.classList.toggle('show');
        }

        (function() {
            const input = document.getElementById('pecuarioSearch');
            const cards = Array.from(document.querySelectorAll('.pecuario-card'));
            function applyFilter() {
                const term = (input.value || '').toLowerCase().trim();
                cards.forEach(card => {
                    const name = (card.getAttribute('data-pecuario') || '').toLowerCase();
                    const show = !term || name.includes(term);
                    card.style.display = show ? '' : 'none';
                });
            }
            if (input) {
                input.addEventListener('input', applyFilter);
            }
        })();
    </script>
</x-app-layout>