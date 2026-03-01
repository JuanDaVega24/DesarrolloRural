<?php

namespace App\Http\Controllers;

use App\Models\FormularioSesion;
use App\Models\ProyectoProductivo;
use App\Models\FormularioPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormularioSesionController extends Controller
{
    /**
     * Obtener o crear una sesión para el usuario actual en un proyecto
     */
    public function obtenerSesion(ProyectoProductivo $proyecto)
    {
        $user = Auth::user();

        // Verificar si el proyecto ya fue fusionado (origen ya no es manual)
        if ($proyecto->origen !== 'manual') {
            return response()->json([
                'success' => false,
                'message' => 'El proyecto ya ha sido finalizado y los datos han sido guardados.',
                'finalizado' => true
            ], 403);
        }

        // Obtener sesión activa para este usuario y proyecto
        $sesion = FormularioSesion::where('proyecto_id', $proyecto->id)
            ->where('user_id', $user->id)
            ->where('completada', false)
            ->first();

        // Si no existe, crear una nueva (máximo 5 usuarios simultáneos)
        if (!$sesion) {
            $usuariosActivosCount = FormularioSesion::where('proyecto_id', $proyecto->id)
                ->where('completada', false)
                ->where('ultima_actividad', '>', now()->subMinutes(30))
                ->distinct('user_id')
                ->count();

            if ($usuariosActivosCount >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se ha alcanzado el límite máximo de 5 usuarios trabajando simultáneamente en este proyecto.'
                ], 429);
            }

            $sesion = FormularioSesion::create([
                'proyecto_id' => $proyecto->id,
                'user_id' => $user->id,
                'session_token' => bin2hex(random_bytes(16)),
                'datos_beneficiarios' => [],
                'completada' => false,
                'ultima_actividad' => now()
            ]);
        } else {
            // Actualizar actividad
            $sesion->update(['ultima_actividad' => now()]);
        }

        return response()->json([
            'success' => true,
            'sesion' => $sesion,
            'proyecto' => $proyecto,
            'preguntas' => $proyecto->preguntas,
            'usuario' => $user
        ]);
    }

    /**
     * Actualizar datos de la sesión (guardado automático)
     */
    public function actualizarDatos(Request $request, ProyectoProductivo $proyecto)
    {
        $user = Auth::user();
        $sesion = FormularioSesion::where('proyecto_id', $proyecto->id)
            ->where('user_id', $user->id)
            ->where('completada', false)
            ->first();

        if (!$sesion) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada.'
            ], 404);
        }

        $sesion->update([
            'datos_beneficiarios' => $request->input('datos_beneficiarios', []),
            'ultima_actividad' => now()
        ]);

        return response()->json([
            'success' => true,
            'ultima_actividad' => $sesion->ultima_actividad->toIso8601String()
        ]);
    }

    /**
     * Validar cédula en tiempo real (para evitar duplicados entre sesiones)
     */
    public function validarCedulaConcurrente(Request $request, ProyectoProductivo $proyecto)
    {
        try {
            $cedula = $request->input('cedula');
            $sessionToken = $request->input('session_token');

            if (!$cedula) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cédula es requerida.'
                ], 400);
            }

            // Buscar cédula en todas las sesiones activas del proyecto (excepto la actual)
            $sesiones = FormularioSesion::where('proyecto_id', $proyecto->id)
                                      ->where('completada', false)
                                      ->get();

            $cedulaEncontrada = false;
            $usuariosConCedula = [];

            foreach ($sesiones as $sesion) {
                foreach ($sesion->datos_beneficiarios as $beneficiario) {
                    if (isset($beneficiario['CÉDULA']) && trim($beneficiario['CÉDULA']) === trim($cedula)) {
                        $cedulaEncontrada = true;
                        $usuariosConCedula[] = [
                            'usuario' => $sesion->user->name,
                            'email' => $sesion->user->email
                        ];
                    }
                }
            }

            // También validar contra el proyecto principal (datos ya guardados)
            $dataProyecto = $proyecto->data;
            if ($dataProyecto && isset($dataProyecto['rows'])) {
                foreach ($dataProyecto['rows'] as $fila) {
                    if (isset($fila['CÉDULA']) && trim($fila['CÉDULA']) === trim($cedula)) {
                        $cedulaEncontrada = true;
                        $usuariosConCedula[] = [
                            'usuario' => 'Sistema',
                            'email' => 'datos existentes'
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'cedula_encontrada' => $cedulaEncontrada,
                'usuarios' => $usuariosConCedula,
                'message' => $cedulaEncontrada 
                    ? 'Esta cédula ya está siendo utilizada por otro usuario o ya existe en el proyecto.'
                    : 'Cédula disponible.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al validar cédula concurrente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al validar la cédula.'
            ], 500);
        }
    }

    /**
     * Obtener estado de usuarios activos en el proyecto
     */
    public function obtenerUsuariosActivos(ProyectoProductivo $proyecto)
    {
        try {
            // Limpiar sesiones inactivas
            FormularioSesion::limpiarSesionesInactivas();

            $sesionesActivas = FormularioSesion::where('proyecto_id', $proyecto->id)
                                             ->where('completada', false)
                                             ->with('user')
                                             ->get();

            $usuarios = $sesionesActivas->map(function($sesion) {
                return [
                    'id' => $sesion->user->id,
                    'name' => $sesion->user->name,
                    'email' => $sesion->user->email,
                    'ultima_actividad' => $sesion->ultima_actividad->toIso8601String(),
                    'beneficiarios_count' => count($sesion->datos_beneficiarios),
                    'completada' => $sesion->completada
                ];
            });

            return response()->json([
                'success' => true,
                'usuarios_activos' => $usuarios,
                'total_usuarios' => $usuarios->count(),
                'total_beneficiarios_temporales' => $sesionesActivas->sum(function($s) {
                    return count($s->datos_beneficiarios);
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios activos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios activos.'
            ], 500);
        }
    }

    /**
     * Completar sesión y fusionar datos
     */
    public function completarSesion(Request $request, ProyectoProductivo $proyecto)
    {
        try {
            $user = Auth::user();
            $datosFinales = $request->input('datos_beneficiarios', []);

            // Obtener sesión
            $sesion = FormularioSesion::where('proyecto_id', $proyecto->id)
                                    ->where('user_id', $user->id)
                                    ->where('completada', false)
                                    ->first();

            if (!$sesion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe una sesión activa para este usuario.'
                ], 404);
            }

            // Validar datos finales
            if (empty($datosFinales)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe tener al menos un beneficiario para completar la sesión.'
                ], 400);
            }

            // Validar cada beneficiario
            foreach ($datosFinales as $index => $beneficiario) {
                if (!isset($beneficiario['CÉDULA']) || empty(trim($beneficiario['CÉDULA']))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El campo CÉDULA es obligatorio para todos los beneficiarios.',
                        'beneficiario' => $index + 1
                    ], 400);
                }

                if (!isset($beneficiario['NOMBRE COMPLETO']) || empty(trim($beneficiario['NOMBRE COMPLETO']))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El campo NOMBRE COMPLETO es obligatorio para todos los beneficiarios.',
                        'beneficiario' => $index + 1
                    ], 400);
                }
            }

            // Validar duplicados con otros usuarios
            $validacion = $this->validarDuplicadosConcurrentes($proyecto, $datosFinales, $sesion->id);
            
            if (!$validacion['valido']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Existen cédulas duplicadas con otros usuarios.',
                    'duplicados' => $validacion['duplicados']
                ], 400);
            }

            // Marcar sesión como completada
            $sesion->marcarComoCompletada();

            return response()->json([
                'success' => true,
                'message' => 'Sesión completada exitosamente. Los datos serán fusionados al finalizar todos los usuarios.',
                'sesion_completada' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error al completar sesión: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al completar la sesión.'
            ], 500);
        }
    }

    /**
     * Validar duplicados entre sesiones concurrentes
     */
    private function validarDuplicadosConcurrentes($proyecto, $datosNuevos, $sesionIdExcluir = null)
    {
        $duplicados = [];
        
        $sesiones = FormularioSesion::where('proyecto_id', $proyecto->id)
                                  ->where('completada', false)
                                  ->when($sesionIdExcluir, function($query, $id) {
                                      return $query->where('id', '!=', $id);
                                  })
                                  ->get();

        foreach ($sesiones as $sesion) {
            foreach ($sesion->datos_beneficiarios as $beneficiario) {
                $cedula = trim($beneficiario['CÉDULA'] ?? '');
                if ($cedula) {
                    foreach ($datosNuevos as $nuevo) {
                        if (trim($nuevo['CÉDULA'] ?? '') === $cedula) {
                            $duplicados[] = [
                                'cedula' => $cedula,
                                'nombre' => $beneficiario['NOMBRE COMPLETO'] ?? '',
                                'usuario_conflicto' => $sesion->user->name
                            ];
                        }
                    }
                }
            }
        }

        // Validar contra datos del proyecto principal
        $dataProyecto = $proyecto->data;
        if ($dataProyecto && isset($dataProyecto['rows'])) {
            foreach ($dataProyecto['rows'] as $fila) {
                $cedula = trim($fila['CÉDULA'] ?? '');
                if ($cedula) {
                    foreach ($datosNuevos as $nuevo) {
                        if (trim($nuevo['CÉDULA'] ?? '') === $cedula) {
                            $duplicados[] = [
                                'cedula' => $cedula,
                                'nombre' => $fila['NOMBRE COMPLETO'] ?? '',
                                'usuario_conflicto' => 'Datos existentes del proyecto'
                            ];
                        }
                    }
                }
            }
        }

        return [
            'valido' => empty($duplicados),
            'duplicados' => $duplicados
        ];
    }

    /**
     * Fusionar todas las sesiones completadas al proyecto
     */
    public function fusionarSesiones(ProyectoProductivo $proyecto)
    {
        try {
            // Verificar si hay sesiones aún en progreso (no completadas y activas)
            $sesionesEnProgreso = FormularioSesion::where('proyecto_id', $proyecto->id)
                ->where('completada', false)
                ->where('ultima_actividad', '>', now()->subMinutes(30))
                ->count();

            if ($sesionesEnProgreso > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Hay {$sesionesEnProgreso} usuario(s) trabajando actualmente. Espere a que terminen o que sus sesiones expiren."
                ], 400);
            }

            // Obtener todas las sesiones (completadas o abandonadas)
            $sesionesParaFusionar = FormularioSesion::where('proyecto_id', $proyecto->id)
                ->get();

            // Recopilar todos los beneficiarios
            $todosBeneficiarios = [];
            $cedulasExistentes = [];

            // Obtener cédulas existentes del proyecto
            $dataProyecto = $proyecto->data;
            if ($dataProyecto && isset($dataProyecto['rows'])) {
                foreach ($dataProyecto['rows'] as $fila) {
                    $cedulasExistentes[] = trim($fila['CÉDULA'] ?? '');
                }
            }

            $conflictos = [];
            $beneficiariosFusionados = [];

            foreach ($sesionesParaFusionar as $sesion) {
                foreach ($sesion->datos_beneficiarios as $beneficiario) {
                    $cedula = trim($beneficiario['CÉDULA'] ?? '');
                    
                    if (empty($cedula)) continue;

                    // Validar duplicados
                    if (in_array($cedula, $cedulasExistentes)) {
                        $conflictos[] = [
                            'cedula' => $cedula,
                            'nombre' => $beneficiario['NOMBRE COMPLETO'] ?? '',
                            'motivo' => 'Existe en datos del proyecto'
                        ];
                        continue;
                    }

                    if (isset($todosBeneficiarios[$cedula])) {
                        $conflictos[] = [
                            'cedula' => $cedula,
                            'nombre' => $beneficiario['NOMBRE COMPLETO'] ?? '',
                            'motivo' => 'Duplicado entre sesiones'
                        ];
                        continue;
                    }

                    $todosBeneficiarios[$cedula] = $beneficiario;
                    $cedulasExistentes[] = $cedula;
                    $beneficiariosFusionados[] = $beneficiario;
                }
            }

            if (empty($beneficiariosFusionados)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron beneficiarios válidos para fusionar.',
                    'conflictos' => $conflictos
                ], 400);
            }

            // Fusionar con datos existentes del proyecto
            $datosActuales = $dataProyecto ? $dataProyecto['rows'] : [];
            $datosFusionados = array_merge($datosActuales, $beneficiariosFusionados);

            // Preparar datos para guardar
            $headers = $this->obtenerHeadersParaFusion($proyecto, $beneficiariosFusionados);
            
            // Agregar columnas automáticas de caracterización
            $this->addAutomaticColumns($headers, $datosFusionados, $proyecto->ano);
            
            $dataToSave = [
                'filename' => 'Formulario Manual - ' . $proyecto->nombre,
                'uploaded_by' => 'Sistema (Fusión de sesiones)',
                'headers' => $headers,
                'rows' => $datosFusionados,
                'uploaded_at' => now()->timezone('America/Bogota')->format('Y-m-d H:i:s'),
                'total_rows' => count($datosFusionados),
                'total_columns' => count($headers)
            ];

            // Actualizar proyecto
            $proyecto->update([
                'data' => $dataToSave,
                'origen' => 'excel' // Cambiar a excel para que no aparezca más en formularios
            ]);

            // Eliminar todas las sesiones del proyecto
            FormularioSesion::where('proyecto_id', $proyecto->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesiones fusionadas exitosamente.',
                'beneficiarios_agregados' => count($beneficiariosFusionados),
                'total_beneficiarios' => count($datosFusionados),
                'conflictos' => $conflictos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al fusionar sesiones: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al fusionar sesiones.'
            ], 500);
        }
    }

    /**
     * Obtener headers para la fusión de datos
     */
    private function obtenerHeadersParaFusion($proyecto, $beneficiarios)
    {
        $headers = [
            '# NUMERO',
            'CÉDULA',
            'NOMBRE COMPLETO',
            'GENERO',
            'CORREGIMIENTO',
            'VEREDA',
            'TELÉFONO'
        ];

        // Obtener preguntas personalizadas
        $preguntas = $proyecto->preguntas()->orderBy('orden')->get();
        foreach ($preguntas as $pregunta) {
            $headers[] = $pregunta->pregunta;
        }

        // Detectar headers adicionales en beneficiarios
        if (!empty($beneficiarios)) {
            foreach ($beneficiarios[0] as $key => $value) {
                if (!in_array($key, $headers)) {
                    $headers[] = $key;
                }
            }
        }

        return array_unique($headers);
    }

    /**
     * Agregar columnas automáticas de caracterización (copiado del ProyectoProductivoController)
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

            // Crear mapas de caracterización
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
                    
                    // Solo actualizar si la columna automática está vacía
                    if (empty($row['Corregimiento_CZ'])) {
                        $row['Corregimiento_CZ'] = $caracterizacionInfo['corregimiento_cz'];
                    }
                    if (empty($row['Vereda_CZ'])) {
                        $row['Vereda_CZ'] = $caracterizacionInfo['vereda_cz'];
                    }
                } else {
                    // No tiene caracterización
                    $row['Estado_Caracterizacion'] = 'NO TIENE CZ';
                }

                // Si no se llenaron por CZ, intentar con las columnas manuales del formulario
                if (empty($row['Corregimiento_CZ'])) {
                    $colCorr = $this->findCorregimientoColumn($headers);
                    $row['Corregimiento_CZ'] = $colCorr ? ($row[$colCorr] ?? '') : '';
                }
                if (empty($row['Vereda_CZ'])) {
                    $colVer = $this->findVeredaColumn($headers);
                    $row['Vereda_CZ'] = $colVer ? ($row[$colVer] ?? '') : '';
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
            Log::error('Error en addAutomaticColumns: ' . $e->getMessage());
        }
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
     * Buscar columna de género en los headers
     */
    private function findGenderColumn($headers)
    {
        $genderKeywords = ['genero', 'sexo', 'gender', 'sex'];
        foreach ($headers as $header) {
            $headerLower = $this->normalizeText($header);
            foreach ($genderKeywords as $keyword) {
                if (str_contains($headerLower, $keyword)) {
                    return $header;
                }
            }
        }
        return null;
    }

    /**
     * Buscar columna de corregimiento en los headers
     */
    private function findCorregimientoColumn($headers)
    {
        $keywords = ['corregimiento', 'correg'];
        foreach ($headers as $header) {
            $headerLower = $this->normalizeText($header);
            foreach ($keywords as $keyword) {
                if (str_contains($headerLower, $keyword)) {
                    return $header;
                }
            }
        }
        return null;
    }

    /**
     * Buscar columna de vereda en los headers
     */
    private function findVeredaColumn($headers)
    {
        $keywords = ['vereda', 'sector', 'comunidad'];
        foreach ($headers as $header) {
            $headerLower = $this->normalizeText($header);
            foreach ($keywords as $keyword) {
                if (str_contains($headerLower, $keyword)) {
                    return $header;
                }
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
     * Obtiene beneficiarios de todas las sesiones activas para sincronización
     */
    public function obtenerBeneficiariosTodasSesiones(ProyectoProductivo $proyecto)
    {
        // Limpiar sesiones inactivas (más de 2 horas sin actividad)
        FormularioSesion::limpiarSesionesInactivas();

        $sesiones = FormularioSesion::where('proyecto_id', $proyecto->id)
            ->with('user')
            ->get();

        $todosBeneficiarios = [];
        $usuariosActivos = [];

        foreach ($sesiones as $sesion) {
            $beneficiarios = $sesion->datos_beneficiarios ?? [];
            
            foreach ($beneficiarios as $beneficiario) {
                $todosBeneficiarios[] = array_merge($beneficiario, [
                    'usuario_sesion' => $sesion->user->name,
                    'sesion_id' => $sesion->id
                ]);
            }

            $usuariosActivos[] = [
                'id' => $sesion->user->id,
                'name' => $sesion->user->name,
                'email' => $sesion->user->email,
                'ultima_actividad' => $sesion->ultima_actividad->toIso8601String(),
                'completada' => $sesion->completada,
                'beneficiarios_count' => count($beneficiarios),
                'es_actual' => $sesion->user_id === Auth::id()
            ];
        }

        // Ordenar usuarios por última actividad (más reciente primero)
        usort($usuariosActivos, function($a, $b) {
            return strtotime($b['ultima_actividad']) - strtotime($a['ultima_actividad']);
        });

        return response()->json([
            'success' => true,
            'beneficiarios' => $todosBeneficiarios,
            'usuarios_activos' => $usuariosActivos,
            'total_beneficiarios' => count($todosBeneficiarios)
        ]);
    }

    /**
     * Normalizar texto para comparaciones
     */
    private function normalizeText($text)
    {
        $unwanted_array = [
            'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a',
            'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u',
            'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
        ];
        $text = strtr($text, $unwanted_array);
        return strtolower(trim($text));
    }

    /**
     * Crear mapas de caracterización para búsqueda optimizada
     */
    private function createCaracterizacionMaps()
    {
        $caracterizaciones = \App\Models\Caracterizacion::all();
        $maps = [];

        foreach ($caracterizaciones as $cz) {
            $doc = trim((string)$cz->numero_documento);
            if (!empty($doc)) {
                $maps[$doc] = [
                    'estado_caracterizacion' => $cz->estado_caracterizacion ?? 'COMPLETA',
                    'corregimiento_cz' => $cz->corregimiento ?? '',
                    'vereda_cz' => $cz->vereda ?? ''
                ];
            }
        }

        return $maps;
    }

    /**
     * Buscar información de caracterización de forma optimizada
     */
    private function findCaracterizacionInfoOptimized($documento, $maps)
    {
        return $maps[$documento] ?? null;
    }

}
