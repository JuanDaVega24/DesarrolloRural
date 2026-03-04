<?php

namespace App\Http\Controllers;

use App\Models\ProyectoProductivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormularioController extends Controller
{
    public function __construct()
    {
        Log::info('FormularioController cargado');
    }

    /**
     * Validar si una cédula existe en proyectos recientes
     */
    public function validarCedula(Request $request)
    {
        $cedula = trim((string)$request->input('cedula'));
        $currentYear = $request->input('current_year');
        $proyectosSeleccionados = $request->input('proyectos_ids', []);

        if (!$cedula) {
            return response()->json(['error' => 'Cédula es requerida'], 400);
        }

        if (!$currentYear) {
            return response()->json(['error' => 'Año actual es requerido'], 400);
        }

        try {
            // 1. Obtener mapas de caracterización para identificar al grupo familiar
            $caracterizacionMaps = $this->createCaracterizacionMaps();
            $mapaDirectos = $caracterizacionMaps['directos'] ?? [];
            $mapaFamiliares = $caracterizacionMaps['familiares'] ?? [];
            $mapaGrupos = $caracterizacionMaps['grupos_familiares'] ?? [];

            // 2. Identificar a TODOS los miembros del núcleo familiar (incluyendo al consultado)
            $miembrosFamilia = [$cedula];
            if (isset($mapaGrupos[$cedula])) {
                $miembrosFamilia = $mapaGrupos[$cedula];
            }

            // 3. Determinar tipo de caracterización de la persona consultada (CORREGIDO: Swap Titular/Familiar)
            $tipoCaracterizacion = 'Ninguno';
            if (isset($mapaDirectos[$cedula])) {
                $tipoCaracterizacion = 'Titular';
            } elseif (isset($mapaFamiliares[$cedula])) {
                $tipoCaracterizacion = 'Familiar';
            }

            // 4. Buscar si ALGUIEN de la familia está en proyectos recientes
            $query = ProyectoProductivo::whereNotNull('data')
                ->where('data->total_rows', '>', 0);

            if (!empty($proyectosSeleccionados)) {
                $query->whereIn('id', $proyectosSeleccionados);
            } else {
                $query->where('ano', '>=', $currentYear - 1);
            }

            $proyectosRecent = $query->get();
            
            $projectsFound = [];
            $proyectosUnicosIds = []; // Para evitar duplicados
            $foundRecent = false;
            $foundOld = false;
            $familiarDuplicado = null; 

            foreach ($proyectosRecent as $proyecto) {
                $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
                $rows = $data['rows'] ?? [];
                $documentColumn = $this->findDocumentColumn($data['headers'] ?? [], $rows);

                if ($documentColumn) {
                    foreach ($rows as $row) {
                        $docEnProyecto = trim((string)($row[$documentColumn] ?? ''));
                        
                        if (in_array($docEnProyecto, $miembrosFamilia)) {
                            // Evitar duplicar el mismo proyecto para la misma persona
                            $llaveUnica = $proyecto->id . '_' . $docEnProyecto;
                            if (in_array($llaveUnica, $proyectosUnicosIds)) continue;
                            $proyectosUnicosIds[] = $llaveUnica;

                            $projectsFound[] = [
                                'id' => $proyecto->id,
                                'nombre' => $proyecto->nombre,
                                'ano' => $proyecto->ano,
                                'persona_inscrita' => $row['NOMBRE COMPLETO'] ?? $docEnProyecto,
                                'es_mismo' => ($docEnProyecto === $cedula)
                            ];
                            
                            if ($proyecto->ano >= $currentYear - 1) {
                                $foundRecent = true;
                                if ($docEnProyecto !== $cedula) {
                                    $familiarDuplicado = $row['NOMBRE COMPLETO'] ?? $docEnProyecto;
                                }
                            } else {
                                $foundOld = true;
                            }
                        }
                    }
                }
            }

            return response()->json([
                'projects' => $projectsFound,
                'foundRecent' => $foundRecent,
                'foundOld' => $foundOld,
                'tipo_caracterizacion' => $tipoCaracterizacion,
                'familiar_duplicado' => $familiarDuplicado
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar cédula: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar lista de proyectos manuales
     */
    public function index()
    {
        // Obtener proyectos creados manualmente
        $proyectosManuales = ProyectoProductivo::where('origen', 'manual')
            ->latest()
            ->paginate(20);

        return view('formularios.index', compact('proyectosManuales'));
    }

    /**
     * Mostrar formulario para completar proyecto manual
     */
    public function show(ProyectoProductivo $proyecto)
    {
        // Verificar si el proyecto ya fue finalizado
        if ($proyecto->origen !== 'manual') {
            return redirect()->route('formularios.index')
                ->with('warning', 'Este proyecto ya ha sido finalizado y los datos han sido consolidados.');
        }

        // Obtener preguntas personalizadas del proyecto
        $preguntasPersonalizadas = $proyecto->preguntas()->orderBy('orden')->get();

        // Obtener todos los años únicos para el filtro
        $anos = ProyectoProductivo::whereNotNull('ano')
            ->where('id', '!=', $proyecto->id)
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano');

        // Obtener todos los proyectos para el multiselect (excepto el actual) que tengan un año definido
        $proyectosParaComparar = ProyectoProductivo::where('id', '!=', $proyecto->id)
            ->whereNotNull('ano')
            ->orderBy('ano', 'desc')
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre', 'ano']);

        return view('formularios.show', compact('proyecto', 'preguntasPersonalizadas', 'anos', 'proyectosParaComparar'));
    }

    /**
     * Actualizar proyecto manual con datos completos
     */
    public function update(Request $request, ProyectoProductivo $proyecto)
    {
        // Verificar que sea un proyecto manual
        if ($proyecto->origen !== 'manual') {
            abort(404, 'Proyecto no encontrado');
        }

        // Validar datos básicos
        $validated = $request->validate([
            'beneficiarios_acumulados' => 'required|string',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        // Decodificar el JSON de beneficiarios acumulados
        $beneficiariosJson = json_decode($validated['beneficiarios_acumulados'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($beneficiariosJson) || empty($beneficiariosJson)) {
            return back()->withInput()->with('error', 'Los datos de beneficiarios no son válidos.');
        }

        if (count($beneficiariosJson) > 50) {
            return back()->withInput()->with('error', 'No puede agregar más de 50 beneficiarios.');
        }

        // Obtener preguntas personalizadas del proyecto
        $preguntasPersonalizadas = $proyecto->preguntas()->orderBy('orden')->get();

        // Construir headers basados en campos estáticos + preguntas personalizadas
        $headers = [
            '# NUMERO',
            'CÉDULA',
            'NOMBRE COMPLETO',
            'GENERO',
            'CORREGIMIENTO',
            'VEREDA',
            'TELÉFONO'
        ];

        // Agregar preguntas personalizadas al final de los headers
        foreach ($preguntasPersonalizadas as $pregunta) {
            $headers[] = $pregunta->pregunta;
        }

        // Procesar datos de beneficiarios
        $rows = [];
        $errores = [];

        foreach ($beneficiariosJson as $beneficiarioId => $beneficiarioData) {
            $rowData = [];

            // Validar que cada beneficiario tenga CÉDULA y NOMBRE COMPLETO (campos requeridos)
            $cedulaVal = $this->findValueInArray($beneficiarioData, ['CÉDULA', 'CEDULA', 'cedula']);
            $nombreVal = $this->findValueInArray($beneficiarioData, ['NOMBRE COMPLETO', 'nombre completo']);

            if (empty($cedulaVal) || empty($nombreVal)) {
                $errores[] = "Beneficiario " . ($beneficiarioId + 1) . ": Debe tener cédula y nombre completo";
                continue;
            }

            // Procesar cada campo usando los headers definidos
            foreach ($headers as $header) {
                // Buscar el valor en los datos recibidos usando el nombre del header
                $valor = $this->findValueInArray($beneficiarioData, [$header]);
                $rowData[$header] = trim((string)$valor);
            }

            $rows[] = $rowData;
        }

        // Si hay errores, devolver con errores
        if (!empty($errores)) {
            return back()->withInput()->withErrors($errores);
        }

        // Si no hay filas válidas, error
        if (empty($rows)) {
            return back()->withInput()->with('error', 'Debe ingresar al menos un beneficiario válido.');
        }

        try {
            // Preparar datos para guardar
            $dataToSave = [
                'filename' => 'Formulario Manual - ' . $proyecto->nombre,
                'uploaded_by' => Auth::user()->name,
                'headers' => array_unique($headers), // Eliminar duplicados
                'rows' => $rows,
                'uploaded_at' => now()->timezone('America/Bogota')->format('Y-m-d H:i:s'),
                'total_rows' => count($rows),
                'total_columns' => count($headers)
            ];

            // Actualizar proyecto
            $proyecto->update([
                'data' => $dataToSave,
                'descripcion' => $validated['descripcion'] ?? $proyecto->descripcion,
            ]);

            // Agregar columnas automáticas de caracterización
            $this->addAutomaticColumns($dataToSave['headers'], $dataToSave['rows'], $proyecto->ano);

            // Actualizar el proyecto con las columnas automáticas
            $proyecto->update([
                'data' => $dataToSave,
                'origen' => 'excel' // Cambiar origen para que no aparezca más en la lista de formularios manuales
            ]);

            $mensaje = count($rows) === 1 ?
                '¡Proyecto completado exitosamente!' :
                "¡Proyecto completado exitosamente con " . count($rows) . " beneficiarios!";

            return redirect()->route('formularios.index')
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la tabla de respuestas de un proyecto
     */
    public function tabla(ProyectoProductivo $proyecto)
    {
        // Obtener beneficiarios del proyecto
        $beneficiarios = collect(json_decode($proyecto->data, true) ?? []);
        
        // Obtener preguntas personalizadas del proyecto
        $preguntas = $proyecto->preguntas()->orderBy('orden')->get();
        
        return view('formularios.tabla', compact('proyecto', 'beneficiarios', 'preguntas'));
    }

    /**
     * Buscar un valor en un array usando múltiples nombres de llave posibles (insensible a mayúsculas/acentos)
     */
    private function findValueInArray($array, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            // 1. Coincidencia exacta
            if (isset($array[$key]) && trim((string)$array[$key]) !== '') {
                return trim((string)$array[$key]);
            }

            // 2. Coincidencia normalizada
            $keyNorm = $this->normalizeText($key);
            foreach ($array as $actualKey => $value) {
                if ($this->normalizeText($actualKey) === $keyNorm && trim((string)$value) !== '') {
                    return trim((string)$value);
                }
            }
        }
        return '';
    }

    /**
     * Verificar si una cédula existe en las filas de datos
     */
    private function cedulaExistsInRows($cedula, $rows, $documentColumn)
    {
        foreach ($rows as $row) {
            if (isset($row[$documentColumn]) && trim((string)$row[$documentColumn]) === trim((string)$cedula)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Eliminar proyecto manual
     */
    public function destroy(ProyectoProductivo $proyecto)
    {
        // Verificar que sea un proyecto manual
        if ($proyecto->origen !== 'manual') {
            abort(404, 'Proyecto no encontrado');
        }

        $proyecto->delete();

        return redirect()->route('formularios.index')
                       ->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Obtener columnas de referencia para el formulario manual
     */
    private function getColumnasReferencia()
    {
        // Retornamos un array no vacío para que la vista muestre el formulario.
        // La lógica de inferencia anterior no se estaba utilizando en la vista (campos hardcoded).
        return ['ok']; 
    }


    /**
     * Buscar columna de documento en los headers
     */
    private function findDocumentColumn($headers, $rows)
    {
        // Estrategia 1: Buscar por nombres de columna específicos
        $documentKeywords = [
            ['Numero de documento de identidad1', 'número de documento de identidad', 'cédula de ciudadanía', 'cedula de ciudadania', 'cedula ciudadanía', 'cedula ciudadania', 'número cédula', 'numero cedula'],
            ['cédula', 'cedula', 'cc', 'ced'],
            ['documento identidad', 'documento nacional', 'numero documento', 'número documento'],
            ['documento', 'doc', 'id', 'identificación', 'identificacion', 'dni'],
            ['cedula', 'numero', 'número', 'identidad']
        ];

        foreach ($documentKeywords as $priorityGroup) {
            foreach ($headers as $header) {
                $headerNormalized = $this->normalizeText($header);
                foreach ($priorityGroup as $keyword) {
                    if (str_contains($headerNormalized, $keyword)) {
                        if ($this->validateDocumentColumn($header, $rows)) {
                            return $header;
                        }
                    }
                }
            }
        }

        // Estrategia 2: Búsqueda por contenido puro
        foreach ($headers as $header) {
            if ($this->validateDocumentColumn($header, $rows)) {
                return $header;
            }
        }

        return null;
    }

    /**
     * Validar que una columna contenga números de documento válidos
     */
    private function validateDocumentColumn($columnName, $rows)
    {
        if (empty($rows)) return false;

        $validCount = 0;
        $totalChecked = min(20, count($rows));

        for ($i = 0; $i < $totalChecked; $i++) {
            if (!isset($rows[$i][$columnName])) continue;

            $value = trim((string)$rows[$i][$columnName]);
            if ($this->isValidDocumentNumber($value)) {
                $validCount++;
            }
        }

        return $validCount >= $totalChecked * 0.6; // 60% válidos
    }

    /**
     * Verificar si un valor parece ser un número de documento válido
     */
    private function isValidDocumentNumber($value)
    {
        if (!is_numeric($value)) return false;
        $length = strlen((string)$value);
        return $length >= 6 && $length <= 12 && (int)$value > 100000;
    }

    /**
     * Normalizar texto para búsqueda (maneja acentos y caracteres especiales)
     */
    private function normalizeText($text)
    {
        // Convertir a minúsculas
        $text = strtolower(trim((string)$text));

        // Reemplazar caracteres con acentos
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'ã' => 'a', 'õ' => 'o', 'ñ' => 'n',
            'ç' => 'c'
        ];

        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Reemplazar múltiples espacios por uno solo
        $text = preg_replace('/\s+/', ' ', $text);

        // Quitar caracteres especiales pero mantener letras, números y espacios
        $text = preg_replace('/[^a-zA-Z0-9\s]/', '', $text);

        return trim($text);
    }

    /**
     * Agregar columnas automáticas de caracterización a los datos
     * Implementación simplificada basada en la lógica del ProyectoProductivoController
     */
    private function addAutomaticColumns(&$headers, &$rows, $currentYear)
    {
        try {
            // Verificar y agregar las nuevas columnas a los headers solo si no existen
            $automaticColumns = ['Estado_Caracterizacion', 'Corregimiento_CZ', 'Vereda_CZ', 'Proyectos_Anteriores'];

            foreach ($automaticColumns as $column) {
                if (!in_array($column, $headers)) {
                    $headers[] = $column;
                }
            }

            // Encontrar columna de documento
            $documentColumn = $this->findDocumentColumn($headers, $rows);

            // Crear mapas de caracterización (copiado del ProyectoProductivoController)
            $caracterizacionMaps = $this->createCaracterizacionMaps();

            // Obtener proyectos del año inmediatamente anterior
            $proyectosAnteriores = \App\Models\ProyectoProductivo::where('ano', $currentYear - 1)
                ->whereNotNull('data')
                ->where('data->total_rows', '>', 0)
                ->get();

            // Crear mapa de documentos por proyecto anterior
            $documentosPorProyecto = [];
            foreach ($proyectosAnteriores as $proyecto) {
                $data = is_string($proyecto->data) ? json_decode($proyecto->data, true) : $proyecto->data;
                $proyectoRows = $data['rows'] ?? [];

                $proyectoDocumentos = [];
                foreach ($proyectoRows as $row) {
                    if (!is_array($row)) continue;

                    $documento = '';
                    if ($documentColumn && isset($row[$documentColumn])) {
                        $documento = trim((string)$row[$documentColumn]);
                    }

                    if (!empty($documento)) {
                        $proyectoDocumentos[] = $documento;
                    }
                }

                $documentosPorProyecto[$proyecto->id] = [
                    'nombre' => $proyecto->nombre,
                    'ano' => $proyecto->ano,
                    'documentos' => $proyectoDocumentos
                ];
            }

            // Procesar cada fila para agregar información automática
            foreach ($rows as &$row) {
                $documento = '';
                if ($documentColumn && isset($row[$documentColumn])) {
                    $documento = trim((string)$row[$documentColumn]);
                }

                if (empty($documento)) {
                    // Si no hay documento, dejar columnas vacías
                    $row['Estado_Caracterizacion'] = '';
                    $row['Corregimiento_CZ'] = '';
                    $row['Vereda_CZ'] = '';
                    $row['Proyectos_Anteriores'] = '';
                    continue;
                }

                // Buscar información de caracterización usando mapas
                $caracterizacionInfo = $this->findCaracterizacionInfoOptimized($documento, $caracterizacionMaps);

                if ($caracterizacionInfo) {
                    // Tiene caracterización - usar la información de los mapas
                    $row['Estado_Caracterizacion'] = $caracterizacionInfo['estado_caracterizacion'];
                    $row['Corregimiento_CZ'] = $caracterizacionInfo['corregimiento_cz'];
                    $row['Vereda_CZ'] = $caracterizacionInfo['vereda_cz'];
                } else {
                    // No tiene caracterización
                    $row['Estado_Caracterizacion'] = 'NO TIENE CZ';
                    $row['Corregimiento_CZ'] = '';
                    $row['Vereda_CZ'] = '';
                }

                // Buscar proyectos anteriores donde aparece este documento
                $proyectosEncontrados = [];
                foreach ($documentosPorProyecto as $proyectoId => $proyectoData) {
                    if (in_array($documento, $proyectoData['documentos'])) {
                        $proyectosEncontrados[] = "Recibió en {$proyectoData['nombre']} el año {$proyectoData['ano']}";
                    }
                }

                $row['Proyectos_Anteriores'] = !empty($proyectosEncontrados) ? implode('; ', $proyectosEncontrados) : '';
            }

        } catch (\Exception $e) {
            // Si falla la caracterización, continuar sin columnas automáticas
            // Esto evita que el formulario falle completamente
            Log::error('Error en addAutomaticColumns: ' . $e->getMessage());
        }
    }

    /**
     * Crear mapas optimizados para búsquedas de caracterización
     */
    private function createCaracterizacionMaps()
    {
        $caracterizacion = \App\Models\Caracterizacion::find(1);
        if (!$caracterizacion || !$caracterizacion->data) {
            return ['directos' => [], 'familiares' => [], 'grupos_familiares' => []];
        }

        $data = is_string($caracterizacion->data) ? json_decode($caracterizacion->data, true) : $caracterizacion->data;
        $rows = $data['rows'] ?? [];
        $headers = $data['headers'] ?? [];

        $documentColumn = $this->findDocumentColumn($headers, $rows);
        if (!$documentColumn) {
            return ['directos' => [], 'familiares' => [], 'grupos_familiares' => []];
        }

        $mapaDirectos = [];
        $mapaFamiliares = [];
        $mapaGrupos = []; // documento => [lista de todos los documentos del nucleo]

        foreach ($rows as $row) {
            if (!is_array($row)) continue;

            $documento = trim((string)($row[$documentColumn] ?? ''));
            if (empty($documento)) continue;

            $corregimiento = $this->findColumnValue($row, $headers, ['corregimiento', 'corregimiento_cz']);
            $vereda = $this->findColumnValue($row, $headers, ['vereda', 'vereda_cz']);

            $familiares = $this->extractFamiliaresInfo($row, $headers);

            // Crear el grupo familiar
            $miembros = [$documento];
            foreach ($familiares as $f) {
                if (!empty(trim($f['numero']))) {
                    $miembros[] = trim($f['numero']);
                }
            }
            
            // Mapear cada miembro al grupo completo
            foreach ($miembros as $m) {
                $mapaGrupos[$m] = $miembros;
            }

            $estado = 'Si';
            if (!empty($familiares)) {
                $familiaresStr = implode(', ', array_map(fn($f) => "{$f['nombre']} {$f['tipo']} {$f['numero']}", $familiares));
                $estado .= ', ' . $familiaresStr;
            }

            $mapaDirectos[$documento] = [
                'estado_caracterizacion' => $estado,
                'corregimiento_cz' => $corregimiento,
                'vereda_cz' => $vereda,
            ];

            foreach ($familiares as $familiar) {
                $familiarDoc = trim($familiar['numero']);
                if (!empty($familiarDoc)) {
                    $mapaFamiliares[$familiarDoc] = [
                        'estado_caracterizacion' => 'Si, pertenece al núcleo familiar',
                        'corregimiento_cz' => $corregimiento,
                        'vereda_cz' => $vereda,
                    ];
                }
            }
        }

        return [
            'directos' => $mapaDirectos, 
            'familiares' => $mapaFamiliares, 
            'grupos_familiares' => $mapaGrupos
        ];
    }

    /**
     * Buscar información de caracterización usando mapas optimizados
     */
    private function findCaracterizacionInfoOptimized($documento, $caracterizacionMaps)
    {
        return $caracterizacionMaps['directos'][$documento] ??
               $caracterizacionMaps['familiares'][$documento] ?? null;
    }

    /**
     * Buscar valor de columna usando múltiples nombres posibles
     */
    private function findColumnValue($row, $headers, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            // Buscar en el row directamente, no solo en headers
            if (isset($row[$name])) {
                return trim((string)$row[$name]);
            }
            // También buscar en headers normalizados
            $nameNormalized = strtolower(str_replace(['_', ' '], '', $name));
            foreach ($headers as $header) {
                $headerNormalized = strtolower(str_replace(['_', ' '], '', $header));
                if (str_contains($headerNormalized, $nameNormalized)) {
                    return trim((string)($row[$header] ?? ''));
                }
            }
        }
        return '';
    }

    /**
     * Extraer información de familiares
     * Copiado del ProyectoProductivoController para mantener consistencia
     */
    private function extractFamiliaresInfo($row, $headers)
    {
        $familiares = [];
        
        // 1. Buscar el nombre principal (encuestado)
        $nombrePrincipal = $this->encontrarNombrePrincipal($row, $headers);
        
        // 2. Buscar nombres, documentos y tipos de familiares emparejando por sufijo numérico
        for ($i = 1; $i <= 20; $i++) {
            $familiar = [];
            
            // Buscar nombre del familiar i
            $nombre = $this->encontrarNombreFamiliarPorIndice($row, $headers, $i);
            
            // Buscar documento del familiar i
            $documento = $this->encontrarDocumentoFamiliarPorIndice($row, $headers, $i);
            
            // Buscar tipo de documento del familiar i
            $tipo = $this->encontrarTipoDocumentoFamiliarPorIndice($row, $headers, $i);
            
            // Solo agregar si hay al menos nombre o documento
            if (!empty($nombre) || !empty($documento)) {
                $familiar['nombre'] = $nombre;
                $familiar['tipo'] = $tipo;
                $familiar['numero'] = $documento;
                
                $familiares[] = $familiar;
            }
        }

        return $familiares;
    }

    /**
     * Buscar nombre de familiar por índice específico
     */
    private function encontrarNombreFamiliarPorIndice($row, $headers, $indice)
    {
        $patterns = [
            "Nombres y apellidos{$indice}", "Nombres y apellidos {$indice}",
            "Nombres y apellidos familiar {$indice}", "Nombre familiar {$indice}",
            "Nombre{$indice}", "Nombre {$indice}"
        ];
        
        foreach ($patterns as $pattern) {
            if (in_array($pattern, $headers) && isset($row[$pattern])) {
                $value = trim((string)$row[$pattern]);
                if (!empty($value)) {
                    return $value;
                }
            }
        }
        
        return '';
    }

    /**
     * Buscar documento de familiar por índice específico
     */
    private function encontrarDocumentoFamiliarPorIndice($row, $headers, $indice)
    {
        $patterns = [
            "Número de documento de identidad{$indice}", "Número de documento de identidad {$indice}",
            "Numero de documento de identidad{$indice}", "Numero de documento de identidad {$indice}",
            "Número de documento familiar {$indice}", "Numero de documento familiar {$indice}"
        ];
        
        foreach ($patterns as $pattern) {
            if (in_array($pattern, $headers) && isset($row[$pattern])) {
                $value = trim((string)$row[$pattern]);
                if (!empty($value) && is_numeric($value)) {
                    return $value;
                }
            }
        }
        
        return '';
    }

    /**
     * Buscar tipo de documento de familiar por índice específico
     */
    private function encontrarTipoDocumentoFamiliarPorIndice($row, $headers, $indice)
    {
        $patterns = [
            "Tipo de documento familiar {$indice}",
            "Tipo de documento{$indice}", "Tipo de documento {$indice}",
            "Tipo de documento de identidad{$indice}", "Tipo de documento de identidad {$indice}"
        ];
        
        foreach ($patterns as $pattern) {
            if (in_array($pattern, $headers) && isset($row[$pattern])) {
                $value = trim((string)$row[$pattern]);
                if (!empty($value)) {
                    return $value;
                }
            }
        }
        
        return '';
    }

    /**
     * Buscar el nombre principal (encuestado) en la fila
     */
    private function encontrarNombrePrincipal($row, $headers)
    {
        // Patrones para nombre principal
        $namePatterns = [
            'Nombre Completo', 'nombre completo',
            'Nombre completo', 'nombre Completo',
            'Nombres y apellidos', 'nombres y apellidos',
            'Nombre', 'nombre'
        ];
        
        foreach ($namePatterns as $pattern) {
            if (in_array($pattern, $headers) && isset($row[$pattern])) {
                $value = trim((string)$row[$pattern]);
                if (!empty($value)) {
                    return $value;
                }
            }
        }
        
        return '';
    }
}
