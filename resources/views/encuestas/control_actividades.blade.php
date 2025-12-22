<x-app-layout>
    <x-steps :progress="100" :current="9" :steps="['Personales','Vivienda','Descripción','Producción','Pecuario','Maquinaria','Gestión Agropecuaria','Predio','Control Actividades','Final']" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="POST" action="{{ route('control_actividades.guardarControlActividade') }}" class="bg-white shadow-lg rounded p-4 p-md-5">
                    @csrf

                    {{-- ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Faltan datos por llenar:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- SECCIÓN UNIDAD PRODUCTIVA --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-building me-2"></i>Registro y control de actividades
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
<div class="col-md-9">
    <label class="form-label fw-semibold">¿Lleva registros de las actividades productivas que desarrolla en la unidad productiva?</label>
    <select name="unidad_productiva" class="form-select border-primary">
        <option value="" disabled
            {{ old('unidad_productiva', $controlActividade->unidad_productiva ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('unidad_productiva', $controlActividade->unidad_productiva ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('unidad_productiva', $controlActividade->unidad_productiva ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                               <div class="col-md-3">
    <label class="form-label fw-semibold">¿De cuáles?</label>
    <select
        name="cuales"
        id="cuales"
        class="form-select border-primary"
        onchange="toggleCualesOtro()"
    >
        <option value="" disabled
            {{ old('cuales', $controlActividade->cuales ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Egresos"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Egresos' ? 'selected' : '' }}>
            Egresos
        </option>

        <option value="Ingresos"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Ingresos' ? 'selected' : '' }}>
            Ingresos
        </option>

        <option value="Agroquimicos aplicados"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Agroquimicos aplicados' ? 'selected' : '' }}>
            Agroquímicos aplicados
        </option>

        <option value="Produccion"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Produccion' ? 'selected' : '' }}>
            Producción
        </option>

        <option value="Inventarios"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Inventarios' ? 'selected' : '' }}>
            Inventarios (insumos, herramientas, etc.)
        </option>

        <option value="Mano de obra empleada"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Mano de obra empleada' ? 'selected' : '' }}>
            Mano de obra empleada
        </option>

        <option value="Condiciones climaticas"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Condiciones climaticas' ? 'selected' : '' }}>
            Condiciones climáticas
        </option>

        <option value="Monitoreo sanitario"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Monitoreo sanitario' ? 'selected' : '' }}>
            Monitoreo sanitario
        </option>

        <option value="Ventas"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Ventas' ? 'selected' : '' }}>
            Ventas
        </option>

        <option value="Otros"
            {{ old('cuales', $controlActividade->cuales ?? '') == 'Otros' ? 'selected' : '' }}>
            Otros
        </option>
    </select>
</div>
<div class="col-md-5 mt-2" id="cualesOtroDiv" style="display: none;">
    <label class="form-label fw-semibold">Especifique</label>
    <input
        type="text"
        name="cuales_otro"
        class="form-control border-primary"
        value="{{ old('cuales_otro') }}"
        placeholder="Especifique cuál"
    >
</div>


                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN FERTILIZANTES --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-droplet me-2"></i>Fertilizantes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                    <label class="form-label fw-semibold">¿Aplica fertilizantes?</label>

                                    <select name="fertilizantes" class="form-select border-primary">
        <option value="" disabled
            {{ old('fertilizantes', $controlActividade->fertilizantes ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('fertilizantes', $controlActividade->fertilizantes ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('fertilizantes', $controlActividade->fertilizantes ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
 </div>
                               <div class="col-md-4">
    <label class="form-label fw-semibold">Tipo de Fertilizantes</label>
    <select name="tipo_fertilizantes" class="form-select border-primary">
        <option value="" disabled
            {{ old('tipo_fertilizantes', $controlActividade->tipo_fertilizantes ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Quimico"
            {{ old('tipo_fertilizantes', $controlActividade->tipo_fertilizantes ?? '') == 'Quimico' ? 'selected' : '' }}>
            Químico
        </option>

        <option value="Organico"
            {{ old('tipo_fertilizantes', $controlActividade->tipo_fertilizantes ?? '') == 'Organico' ? 'selected' : '' }}>
            Orgánico
        </option>

        <option value="Mixto"
            {{ old('tipo_fertilizantes', $controlActividade->tipo_fertilizantes ?? '') == 'Mixto' ? 'selected' : '' }}>
            Mixto
        </option>
    </select>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">Frecuencia de Aplicación</label>
    <select name="frecuencia_aplicacion" class="form-select border-primary">
        <option value="" disabled
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Semanal"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Semanal' ? 'selected' : '' }}>
            Semanal
        </option>

        <option value="Quincenal"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Quincenal' ? 'selected' : '' }}>
            Quincenal
        </option>

        <option value="Mensual"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Mensual' ? 'selected' : '' }}>
            Mensual
        </option>

        <option value="Trimestral"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Trimestral' ? 'selected' : '' }}>
            Trimestral
        </option>

        <option value="Semestral"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Semestral' ? 'selected' : '' }}>
            Semestral
        </option>

        <option value="Anual"
            {{ old('frecuencia_aplicacion', $controlActividade->frecuencia_aplicacion ?? '') == 'Anual' ? 'selected' : '' }}>
            Anual
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN ANÁLISIS --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-search me-2"></i>Análisis de Suelo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-2">
    <label class="form-label fw-semibold">Mecanismos</label>
    <select name="mecanismos" class="form-select border-primary">
        <option value="" disabled
            {{ old('mecanismos', $controlActividade->mecanismos ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Edafica"
            {{ old('mecanismos', $controlActividade->mecanismos ?? '') == 'Edafica' ? 'selected' : '' }}>
            Edáfica
        </option>

        <option value="Foliar"
            {{ old('mecanismos', $controlActividade->mecanismos ?? '') == 'Foliar' ? 'selected' : '' }}>
            Foliar
        </option>
    </select>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">¿Ha hecho análisis de suelos en la finca?</label>
    <select name="analisis" class="form-select border-primary">
        <option value="" disabled
            {{ old('analisis', $controlActividade->analisis ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('analisis', $controlActividade->analisis ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('analisis', $controlActividade->analisis ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                               <div class="col-md-3">
    <label class="form-label fw-semibold">¿El análisis ayuda?</label>
    <select name="analisis_ayuda" class="form-select border-primary">
        <option value="" disabled
            {{ old('analisis_ayuda', $controlActividade->analisis_ayuda ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('analisis_ayuda', $controlActividade->analisis_ayuda ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('analisis_ayuda', $controlActividade->analisis_ayuda ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Fecha del Análisis</label>
                                    <input type="number" name="fecha_analisis"
                                           class="form-control border-primary"
                                           value="{{ old('fecha_analisis', $controlActividade->fecha_analisis ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN CONTROL DE PLAGAS --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield-check me-2"></i>Control de Plagas y Enfermedades
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
    <label class="form-label fw-semibold">Control de arvenses</label>
    <select name="control" class="form-select border-primary">
        <option value="" disabled
            {{ old('control', $controlActividade->control ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Manual"
            {{ old('control', $controlActividade->control ?? '') == 'Manual' ? 'selected' : '' }}>
            Manual
        </option>

        <option value="Mecanico"
            {{ old('control', $controlActividade->control ?? '') == 'Mecanico' ? 'selected' : '' }}>
            Mecánico
        </option>

        <option value="Quimico"
            {{ old('control', $controlActividade->control ?? '') == 'Quimico' ? 'selected' : '' }}>
            Químico
        </option>

        <option value="Biologico"
            {{ old('control', $controlActividade->control ?? '') == 'Biologico' ? 'selected' : '' }}>
            Biológico
        </option>

        <option value="No realiza"
            {{ old('control', $controlActividade->control ?? '') == 'No realiza' ? 'selected' : '' }}>
            No realiza
        </option>
    </select>
</div>

                               <div class="col-md-3">
    <label class="form-label fw-semibold">Frecuencia</label>
    <select name="frecuencia" class="form-select border-primary">
        <option value="" disabled
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Semanal"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Semanal' ? 'selected' : '' }}>
            Semanal
        </option>

        <option value="Quincenal"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Quincenal' ? 'selected' : '' }}>
            Quincenal
        </option>

        <option value="Mensual"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Mensual' ? 'selected' : '' }}>
            Mensual
        </option>

        <option value="Trimestral"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Trimestral' ? 'selected' : '' }}>
            Trimestral
        </option>

        <option value="Semestral"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Semestral' ? 'selected' : '' }}>
            Semestral
        </option>

        <option value="Anual"
            {{ old('frecuencia', $controlActividade->frecuencia ?? '') == 'Anual' ? 'selected' : '' }}>
            Anual
        </option>
    </select>
</div>

                               <div class="col-md-5">
    <label class="form-label fw-semibold">¿Realiza control de plagas y enfermedades?</label>
    <select name="control_plagas" class="form-select border-primary">
        <option value="" disabled
            {{ old('control_plagas', $controlActividade->control_plagas ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('control_plagas', $controlActividade->control_plagas ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('control_plagas', $controlActividade->control_plagas ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Tipo de Control</label>
    <select name="tipo_control" class="form-select border-primary">
        <option value="" disabled
            {{ old('tipo_control', $controlActividade->tipo_control ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Quimico"
            {{ old('tipo_control', $controlActividade->tipo_control ?? '') == 'Quimico' ? 'selected' : '' }}>
            Químico
        </option>

        <option value="Biologico"
            {{ old('tipo_control', $controlActividade->tipo_control ?? '') == 'Biologico' ? 'selected' : '' }}>
            Biológico
        </option>
    </select>
</div>

                                <div class="col-md-6">
    <label class="form-label fw-semibold">¿Conoce acerca de Buenas Prácticas Agricolas -BPA?</label>
    <select name="conoce_BPA" class="form-select border-primary">
        <option value="" disabled
            {{ old('conoce_BPA', $controlActividade->conoce_BPA ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('conoce_BPA', $controlActividade->conoce_BPA ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('conoce_BPA', $controlActividade->conoce_BPA ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN INOCUIDAD --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-check-circle me-2"></i>Inocuidad y Protección
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                               <div class="col-md-3">
    <label class="form-label fw-semibold">¿Conoce el concepto de Inocuidad?</label>
    <select name="conoce_inocuidad" class="form-select border-primary">
        <option value="" disabled
            {{ old('conoce_inocuidad', $controlActividade->conoce_inocuidad ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('conoce_inocuidad', $controlActividade->conoce_inocuidad ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('conoce_inocuidad', $controlActividade->conoce_inocuidad ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                                <div class="col-md-5">
    <label class="form-label fw-semibold">¿Acostumbra a lavar y desinfectar las herramientas que emplea en el cultivo?</label>
    <select name="desinfectar" class="form-select border-primary">
        <option value="" disabled
            {{ old('desinfectar', $controlActividade->desinfectar ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('desinfectar', $controlActividade->desinfectar ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('desinfectar', $controlActividade->desinfectar ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">¿Conoce los grados de toxicidad de los plaguicidas?</label>
    <select name="toxicidad" class="form-select border-primary">
        <option value="" disabled
            {{ old('toxicidad', $controlActividade->toxicidad ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('toxicidad', $controlActividade->toxicidad ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('toxicidad', $controlActividade->toxicidad ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

               <div class="col-md-6">
    <label class="form-label fw-semibold">¿Usa protección para la aplicación de plaguicidas?</label>
    <select name="proteccion" class="form-select border-primary">
        <option value="" disabled
            {{ old('proteccion', $controlActividade->proteccion ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('proteccion', $controlActividade->proteccion ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('proteccion', $controlActividade->proteccion ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                              <div class="col-md-6">
    <label class="form-label fw-semibold">¿Cuáles?</label>

    <select name="cuales_proteccion" id="cuales_proteccion"
            class="form-select border-primary"
            onchange="toggleOtroProteccion()">
        <option value="" disabled
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Gafas"
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') == 'Gafas' ? 'selected' : '' }}>
            Gafas
        </option>

        <option value="Mascarillas"
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') == 'Mascarillas' ? 'selected' : '' }}>
            Mascarillas
        </option>

        <option value="Botas"
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') == 'Botas' ? 'selected' : '' }}>
            Botas
        </option>

        <option value="Traje impermeable"
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') == 'Traje impermeable' ? 'selected' : '' }}>
            Traje impermeable
        </option>

        <option value="Otro"
            {{ old('cuales_proteccion', $controlActividade->cuales_proteccion ?? '') == 'Otro' ? 'selected' : '' }}>
            Otro
        </option>
    </select>
</div>

<div class="col-md-5 mt-2" id="otroProteccionDiv" style="display: none;">
    <label class="form-label fw-semibold">Especifique otra protección</label>
    <input type="text" name="otro_proteccion"
           class="form-control border-primary"
           value="{{ old('otro_proteccion') }}">
</div>


                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN PLAGUICIDAS --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-bug me-2"></i>Manejo de Plaguicidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
    <label class="form-label fw-semibold">¿Una vez terminada la aplicación de plaguicidas los trabajadores se bañan?</label>
    <select name="plaguicidas" class="form-select border-primary">
        <option value="" disabled
            {{ old('plaguicidas', $controlActividade->plaguicidas ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>
        <option value="Si"
            {{ old('plaguicidas', $controlActividade->plaguicidas ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>
        <option value="No"
            {{ old('plaguicidas', $controlActividade->plaguicidas ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label fw-semibold">¿Conoce y respecta el tiempo de carencia de los plaguicidas?</label>
    <select name="tiempo_plaguicida" class="form-select border-primary">
        <option value="" disabled
            {{ old('tiempo_plaguicida', $controlActividade->tiempo_plaguicida ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>
        <option value="Si"
            {{ old('tiempo_plaguicida', $controlActividade->tiempo_plaguicida ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>
        <option value="No"
            {{ old('tiempo_plaguicida', $controlActividade->tiempo_plaguicida ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label fw-semibold">¿Ingresan al cultivo nuevamente después de la aplicación de plaguicidas?</label>
    <select name="cultivo_plaguicida" class="form-select border-primary">
        <option value="" disabled
            {{ old('cultivo_plaguicida', $controlActividade->cultivo_plaguicida ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>
        <option value="Si"
            {{ old('cultivo_plaguicida', $controlActividade->cultivo_plaguicida ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>
        <option value="No"
            {{ old('cultivo_plaguicida', $controlActividade->cultivo_plaguicida ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                                <div class="col-md-6">
    <label class="form-label fw-semibold">¿Que hace con los envases de plaguicidas vacíos?</label>
    <select name="envases_plaguicida" class="form-select border-primary">
        <option value="" disabled
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Triple lavado"
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') == 'Triple lavado' ? 'selected' : '' }}>
            Triple lavado
        </option>

        <option value="Los entierra"
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') == 'Los entierra' ? 'selected' : '' }}>
            Los entierra
        </option>

        <option value="Los quema"
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') == 'Los quema' ? 'selected' : '' }}>
            Los quema
        </option>

        <option value="Los tira en el lote"
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') == 'Los tira en el lote' ? 'selected' : '' }}>
            Los tira en el lote
        </option>

        <option value="Los rompe o perfora"
            {{ old('envases_plaguicida', $controlActividade->envases_plaguicida ?? '') == 'Los rompe o perfora' ? 'selected' : '' }}>
            Los rompe o perfora y los entrega a la empresa de aseo municipal
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN CALIDAD --}}
                    <div class="card mb-4 border-0" style="background-color:#f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-water me-2"></i>Calidad del Predio y Análisis de Agua
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                               <div class="col-md-4">
    <label class="form-label fw-semibold">¿Cual es la calidad de agua en su predio?</label>
    <select name="calidad_predio" class="form-select border-primary">
        <option value="" disabled
            {{ old('calidad_predio', $controlActividade->calidad_predio ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Buena"
            {{ old('calidad_predio', $controlActividade->calidad_predio ?? '') == 'Buena' ? 'selected' : '' }}>
            Buena
        </option>

        <option value="Regular"
            {{ old('calidad_predio', $controlActividade->calidad_predio ?? '') == 'Regular' ? 'selected' : '' }}>
            Regular
        </option>

        <option value="Mala"
            {{ old('calidad_predio', $controlActividade->calidad_predio ?? '') == 'Mala' ? 'selected' : '' }}>
            Mala
        </option>
    </select>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">¿Ha realizado análisis de Agua</label>
    <select name="analisis_agua" class="form-select border-primary">
        <option value="" disabled
            {{ old('analisis_agua', $controlActividade->analisis_agua ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Si"
            {{ old('analisis_agua', $controlActividade->analisis_agua ?? '') == 'Si' ? 'selected' : '' }}>
            Sí
        </option>

        <option value="No"
            {{ old('analisis_agua', $controlActividade->analisis_agua ?? '') == 'No' ? 'selected' : '' }}>
            No
        </option>
    </select>
</div>

                               <div class="col-md-4">
    <label class="form-label fw-semibold">¿Cuál?</label>
    <select name="cual_analisis" class="form-select border-primary">
        <option value="" disabled
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') ? '' : 'selected' }}>
            Seleccione una opción
        </option>

        <option value="Fisico"
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') == 'Fisico' ? 'selected' : '' }}>
            Físico
        </option>

        <option value="Quimico"
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') == 'Quimico' ? 'selected' : '' }}>
            Químico
        </option>

        <option value="Microbiologico"
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') == 'Microbiologico' ? 'selected' : '' }}>
            Microbiológico
        </option>

        <option value="Residuos de plaguicidas"
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') == 'Residuos de plaguicidas' ? 'selected' : '' }}>
            Residuos de plaguicidas
        </option>

        <option value="Residuos de metales pesados"
            {{ old('cual_analisis', $controlActividade->cual_analisis ?? '') == 'Residuos de metales pesados' ? 'selected' : '' }}>
            Residuos de metales pesados
        </option>
    </select>
</div>

                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('encuestas.predio') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-arrow-left-circle me-2"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right-circle me-2"></i> Siguiente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function toggleCualesOtro() {
        const select = document.getElementById('cuales');
        const otroDiv = document.getElementById('cualesOtroDiv');

        if (!select || !otroDiv) return;

        otroDiv.style.display = select.value === 'Otros' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleCualesOtro();
    });
      function toggleOtroProteccion() {
        const select = document.getElementById('cuales_proteccion');
        const otroDiv = document.getElementById('otroProteccionDiv');

        if (select.value === 'Otro') {
            otroDiv.style.display = 'block';
        } else {
            otroDiv.style.display = 'none';
        }
    }

    // Ejecutar al cargar la página (para old())
    document.addEventListener('DOMContentLoaded', function () {
        toggleOtroProteccion();
    });
</script>


    <style>
         .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(45, 95, 63, 0.25);
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background-color: #1e4430 !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: #0f5132 !important;
        }
    </style>

    

</x-app-layout>
