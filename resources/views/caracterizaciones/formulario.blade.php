
<x-app-layout>
    

        @vite(['resources/css/pages/caracterizaciones/formulario.css'])



    <div class="form-container">
        <div class="form-card">
            {{-- Información de la caracterización --}}
            <div class="project-info">
                <i class="fas fa-users-cog"></i>
                <div class="project-info-content">
                    <h4>Agregar Registros a Caracterización</h4>
                    <p>Complete el formulario para agregar nuevos registros a la caracterización existente.</p>
                </div>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('caracterizaciones.formulario.update') }}" method="POST" id="caracterizacionForm">
                @csrf
                @method('PUT')

                {{-- Sistema de registros acumulativos --}}
                @php
                    // Mapeo de columnas con opciones predefinidas
                    $opcionesColumnas = [
                        'Tipo de documento' => ['Cédula de Ciudadanía(CC)', 'Tarjeta de Identidad(TI)', 'Registro Civil (RC)','Cédula de Extranjería(CE)', 'Carné diplomático(CD)', 'Salvoconducto(SC)', 'Permiso especial de Permanencia(PEP)',  'Documento extranjero(DE)','Otro'],
                        'Tipo de documento del encuestado' => ['Cédula de Ciudadanía(CC)','Cédula de Extranjería(CE)', 'Carné diplomático(CD)', 'Salvoconducto(SC)', 'Permiso especial de Permanencia(PEP)',  'Documento extranjero(DE)','Otro'],
                        'Corregimiento' => ['1', '2', '3'],
                        'Vereda' => [], // Se llenará dinámicamente
                        'Veredas' => [], // Se llenará dinámicamente
                        'Nombre de la Vereda' => [], // Se llenará dinámicamente
                        '¿Cuenta con un medio de transporte propio? ¿Cual?' => ['Motocicleta', 'Automóvil', 'Furgón/Camión','Ninguno', 'Otras'],
                         'Tenencia o relación con la tierra'=> ['Propia', 'Arriendo', 'Aparcería', 'Usufructo', 'Comodato', 'Ocupación de hecho', 'Propiedad colectiva', 'Adjudicatario o Comunero', 'Adjudicatario(a)/ Viviente', 'Otras'],
                         '¿Pertenece a una población de especial protección constitucional?'=> ['Campesino', 'Mujer rural', 'Joven rural', 'Persona mayor', 'Persona con discapacidad', 'Cuidador/a', 'Víctima del conflicto (RUV)','Población étnica', 'LGBTIQ+',  'Cabeza de familia',  'Desmovilizado', 'Firmante del Acuerdo de Paz','Otro'],
                        'Población'=> ['Población étnica',  'Persona con discapacidad', 'Víctima del conflicto armado', 'Persona mayor', 'Población con orientación sexualmente diversa','Joven rural', 'Mujer rural', 'Cuidador/a', 'Desmovilizado/Firmante del Acuerdo de Paz',  'Otro'],
                        'Material predominante de los pisos de esta vivienda' => ['Mármol, parqué, madera pulida y lacada', 'Baldosa, vinilo, tableta, ladrillo, laminado', 'Cemento, gravilla', 'Madera sin pulir, otros', 'Tierra, arena, barro'],
                        'Material predominante de las paredes exteriores de la vivienda' => ['Bloque, ladrillo, piedra, madera pulida', 'Concreto vaciado', 'Material prefabricado', 'Tapia pisada, bahareque, adobe', 'Madera burda, tabla, guadua, otros materiales de origen vegetal', 'Otros materiales (Zinc, tela, cartón, plásticos)'],
                        'Fuente de agua para el consumo humano'=> ['Acueducto metropolitano', 'Acueducto veredal', 'Nacimiento / quebrada', 'Pila comunitaria','Otras'],
                        'Combustible y o fuente energética para cocinar'=> ['Madera', 'Gas Natural', 'Gas propano','Electricidad', 'Carbón', 'Biogás', 'Ninguno'],
                        'Medios de comunicación de los cuales dispone en la finca'=> ['Prensa', 'Radio', 'Televisión','Correo electrónico', 'Internet', 'Celular', 'Telefono fijo'],
                        'Uso de las fuentes hídricas con que cuenta el predio'=> ['Agropecuario', 'Doméstico'],
                        'Vías de acceso a la finca'=> ['Carretera pavimentada', 'Carretera destapada', 'Camino de herradura','Otro'],
                        '¿Donde almacena las herramientas e insumos agropecuarios que emplea en sus labores?'=> ['En la vivienda', 'En bodega contigua a la vivienda', 'Al aire libre'],
                        'Tipo de servicio sanitario (inodoro) que tiene la vivienda' => ['Inodoro conectado al alcantarillado', 'Inodoro conectado a pozo séptico', 'Inodoro sin conexión', 'Letrina', 'Inodoro con descarga directa a fuente de agua', 'No cuenta con servicio sanitario'],
                        'Tipo de sistema de riego empleado' => ['Superficial (Por gravedad o inundación)', 'Prenomsurizado (Goteo, aspersión, microaspersión)', 'Manual o por mateo'],
                        'El destino final de la producción es' => ['Autoconsumo', 'Venta a intermediarios (plazas de mercado/ Central de abastos)', 'Venta a cooperativa', 'Mercadillos campesinos', 'Exportación', 'Otras'],
                        'Tipo de vivienda' => ['Casa', 'Apartamento', 'Tipo cuarto', 'No hay vivienda', 'Otro'],
                        'género' => ['Masculino', 'Femenino', 'No Binario','Otro'],
                        'Parentesco con el jefe del hogar'=> ['Cabeza del hogar(jefe o jefa) ', 'Pareja (Cónyuge, compañero/a, esposo/a)','Hijo/a, hijastro/a', 'Yerno, nuera', 'Nieto/a', 'Hermano/a, hermanastro/a', 'Otro pariente','Empleado/a domestico/a', 'Otro no pariente'],

                        'tipodeserviciosanitarioinodoroquetienelavivienda' => ['Inodoro conectado al alcantarillado', 'Inodoro conectado a pozo séptico', 'Inodoro sin conexión', 'Letrina', 'Inodoro con descarga directa a fuente de agua', 'No cuenta con servicio sanitario'],
                        'Tipo de fuente hídrica con que cuenta el predio' => ['Nacimiento', 'Rio', 'Quebrada', 'Lago', 'Pozo', 'Otro'],
                        'condiciones de ocupación de la vivienda' => ['Ocupada por la familia', 'Vivienda temporal (Vacaciones, trabajo, etc.)', 'Desocupada', 'Ocupada por viviente(s) y los dueños no viven en le predio'],
                        'nivel educativo' => ['Primaria Completa', 'Primaria incompleta', 'Secundaria incompleta', 'Secundaria completa', 'Técnico', 'Tecnológica', 'Profesional', 'Especializacion', 'Maestria','Doctorado', 'Ninguna'],
                        'Tipo de maquinaria y o equipo'=> ['Ahoyadora', 'Equipo de inseminacion', 'Fumigadora', 'Guadañadora', 'Motosierra', 'Picadora de pasto', 'Hidrolavadora', 'Motobomba','Sistema de riego', 'Tostadora de café / cacao', 'Trilladora', 'Molino', 'Despulpadora', 'Módulo ecólogico para el despulpado de café', 'Planta eléctrica ', 'Báscula', 'Minitractor / Motocultor', 'Cajón de fermentador de cacao', 'Otros'],
                        'Con que Frecuencia realiza control de arvenses'=> ['Mensual', 'Trimestral','Semestral', 'Anual'],
                        'Tipo de control'=> ['Químico', 'Biologico', 'Otro'],

                        'Tipo de infraestructura'=> ['Aprisco', 'Bodega de almacenamiento de agro insumos ', 'Manga', 'Corral', 'Embarcado', 'Área de manejo de residuos sólidos (ordinarios y peligrosos)', 'Beneficiadero de café ', 'Biodigestor','Brete', 'Compostera', 'Establo', 'Galpón', 'Invernadero', 'Pesebrera', 'Silo', 'Marquesina', 'Casa elva', 'Vivero', 'Trapiche', 'Otro'],
                        'En que consistió el proyecto'=> ['Entrega de insumos, herramientas y/o equipos', 'Transferencia de conocimientos', 'Transferencia económica', 'Construcción o adecuación de infraestructura', 'Otro'],
                        'Qué barreras enfrentó'=> ['Falta de garantías (recursos económicos o tierra para exigidos como garantía para otorgar crédito', 'Falta de información y/o educación financiera (falta de conocimiento sobre productos financieros, tasas de interés y cómo gestionarlos)', 'Ingresos irregulares', 'Otro'],
                        'Qué tipo de fertilizantes empleó'=> ['Químico', 'Orgánico', 'Mixto'],
                        'Método de aplicacion'=> ['Edáfica', 'Foliar', 'Mixto'],
                        'Frecuencia de aplicación'=> ['Semanal', 'Mensual', 'Trimestral', 'Anual'],
                        'Realiza control'=> ['Manual', 'Mecánico', 'Químico', 'Biológico', 'No'],
                        'Qué elementos de protección emplea'=> ['Gafas', 'Guantes', 'Mascarilla', 'Botas', 'Traje impermeable', 'Otro'],
                        'Qué información registra'=> ['Ingresos y egresos', 'Aplicación de fertilizantes', 'Cosecha', 'Inventario de insumos, herramientas y/o equipos', 'Mano de obra empleada', 'Otro'],
                        'Qué fenómeno natural lo afecto' => ['Lluvia torrencial', 'Sequía', 'Ola de calor', 'Ola de frío', 'Vientos fuertes', 'Terremoto','Deslizamiento / Remoción de masa', 'Inundación', 'Desboradmiento de ríos / quebradas', 'Otro'],
                        'Qué solución propone para superar la afectación' => ['Implementar sistemas de riego por goteo', 'Entrega de tanques para el almacenamiento de agua', 'Reconversión de cultivos con variedades mejoradas', 'Reforestación', 'Transferencia de conocimientos', 'Complementos nutricionales','Apoyo para el acceso a crédito y/o alivios en obligaciones crediticias (reducción de intereses, acuerdo de pago y condonación parcial de la deuda)', 'Entrega de insumos y/o materialespara la resiembra de cultivos', 'Entrega de materiales para la reparación y/o adecuación de vivienda', 'Construcción de vivienda nueva', 'Reubicación', 'Otro'],
                        'Destino de aguas residuales' => ['Alcantarillado', 'Pozo séptico', 'Ninguno'],
                        'La mayor parte del terreno que conforma esta unidad productiva agropecuaria es:'=> ['Plano', 'Quebrado (con pendiente)'],
                        'Realiza actividades productivas agrícolas'=> ['Si', 'No'],
                        'Realiza actividades agroindustriales'=> ['Si', 'No'],                    
                        'Realiza actividades pecuarias'=> ['Si', 'No'],
                        'Orientación de la actividad'=> ['Cría', 'Levante','Ceba',  'Ciclo completo', 'Genética', 'Engorde', 'Producción de huevo', 'Ornamentales', 'Mascotas', 'Otro'],
                        'Qué entidad lo gestionó'=> ['Alcaldía', 'Gobernación','Ministerio de Agricultura',  'Agencia de Desarrollo Rural', 'Entidad prestadora de Extensión Agropecuaria (EPSEA)', 'Otro'],
                        'Ha solicitado crédito para el desarrollo de las actividades agropecuarias'=> ['Si', 'No'],
                        'Qué hace con los envases de plaguicidas vacíos'=> ['Triple lavado', 'Los entierra', 'Los quema', 'Los tira en el lote', 'Los reutiliza', 'Los rompe o perfora y los entrega a la empresa de aseo municipal'],
                        'Fuente de la electricidad'=> ['Redes eléctricas', 'Generador', 'Panel solar', 'Otro'],
                        'Principales fuentes de ingresos del hogar actividades Agricolas'=> ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
                        'Actividades Pecuarias'=> ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
                        'Empleo Formal'=> ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
                        'Actividades Comerciales'=> ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
                        'Lugar de aplicación'=> ['Casa', 'Finca', 'Lote'],
                        'En qué entidad financiera lo solicitó'=> ['Banco Agrario', 'Cooperativa Financiera', 'Otro'],
        'Que afectación o daño hubo en la unidad productiva'=> ['Destrucción de cultivos', 'Destrucción de Infraestructura', 'Pérdida de ganado/animales', 'Pérdida de cosecha', 'Pérdida de terreno', 'Alteración del ciclo productivo', 'Reducción del rendimiento y calidad de los productos agrícolas', 'Destrucción parcial o total de la vivienda',  'Otro'],
        'Tipo de cultivo'=> ['Café', 'Cacao', 'Aguacate', 'Banano','Platano', 'Yuca','Mango','Mango Tomy','Citricos','Limón','Limón Tahiti', 'Naranja','Mandarina','Uva','Mora', 'Maíz', 'Guanabana','Guayaba', 'Zapote','Maracuya','Pitahaya', 'Hortalizas','Apio','Pimentón', 'Tomate', 'Frijol','Habichuela','Hierbas aromáticas','Otro'],
                        'Tiene registro Sanitario INVIMA'=> ['Si', 'No','No aplica'],
                        'Qué porcentaje representa los ingresos de esta actividad frente al total de los ingresos del hogar'=> ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Actividad productiva'=> ['Café', 'Cacao', 'Aguacate', 'Banano','Platano', 'Yuca','Mango','Mango Tomy','Citricos','Limón','Limón Tahiti', 'Naranja','Mandarina','Uva','Mora','Maíz', 'Guanabana','Guayaba', 'Zapote','Maracuya','Pitahaya', 'Hortalizas','Apio','Pimentón', 'Tomate', 'Frijol','Habichuela','Hierbas aromáticas','Otro'],
                        'Afectación'=> ['Plantas secas por estrés hidrico', 'Golpe de calor en animales','Perdida de la floración', 'Pasma o aborto de frutos', 'Escasez de alimento por perdida de forrajes', 'Plantas muertas por sequía', 'Muerte de animales', 'Pudrición por exceso de agua','Pérdida de cultivos por deslizamiento', 'Pérdida de animales por deslizamiento','Inundaciones', 'Pérdida de cultivos por heladas', 'Proliferación de enfermedades en animales por ola invernal', 'Proliferación de hongos y enfermedades fitosanitarias en plantas por ola invernal', 'Otro'],
                        'Acuicultura'=> ['Mojarra', 'Cachama','Bocachico', 'Trucha invernal', 'No'],
                        'Otras especies'=> ['Cerdos (traspatio)', 'Gallos, pollos y gallinas de traspatio','Gallos de pelea', 'Picos o pavos', 'Patos y gansos', 'Codornices', 'Avestruces', 'Cuyes', 'Conejos', 'Colmenas de abejas para producción de miel', 'Colmenas de abejas para produccción de polen','Colmenas de abejas para subproductos', 'Colmenas de abejas meliponas', 'Aves ornamentales', 'Caninos hembra', 'Caninos macho', 'Felinos hembra','Felinos macho', 'Tortuga / Morrocoy', 'No'],      
                        'Búfalos, equinos, ovinos o caprinos'=> ['Caballos', 'Yeguas','Mulos', 'Mulas', 'Burros', 'Burras', 'Cabros', 'Cabras','Ovejos', 'Ovejas','Búfalos machos', 'Búfalos hembras', 'No'],


                        ];
                @endphp

                @if(count($columnasReferencia) > 0)
                    {{-- Reglas condicionales --}}
                    @php
                        // DEFINICIÓN MANUAL DE REGLAS DE RAMIFICACIÓN
                        // Aquí puedes definir qué campos ocultar cuando la respuesta sea "No"
                        $manualRules = [
                            // EJEMPLO 1: SALTO DE SECCIÓN (Range Rule)
                            // Si la respuesta es "No", oculta todo hasta llegar al campo destino
                          

                            [
                                'trigger_field' => 'Nucleo familiar', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Tipo de Vivienda'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Agregar otro familiar', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Tipo de Vivienda'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Cuenta con electricidad', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuenta con agua para el consumo humano'              // Campo donde se reanuda el formulario
                            ],

                              [
                                'trigger_field' => 'Cuenta con agua para el consumo humano', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuenta con filtro para el agua de consumo en el hogar'              // Campo donde se reanuda el formulario
                            ],

                            

                              [
                                'trigger_field' => 'Realiza actividades productivas agrícolas', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza actividades Agroindustriales'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Agregar otro cultivo', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza actividades Agroindustriales'              // Campo donde se reanuda el formulario
                            ],

                               [
                                'trigger_field' => 'Realiza actividades Agroindustriales', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza actividades pecuarias'              // Campo donde se reanuda el formulario
                            ],

                              [
                                'trigger_field' => 'Agregar otro producto', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Tiene una marca para sus productos agroindustriales'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Tiene una marca para sus productos agroindustriales', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza actividades pecuarias'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Realiza actividades productivas agrícolas', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza actividades Agroindustriales'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Realiza actividades pecuarias', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza otras actividades productivas no agropecuarias o agroindustriales en el predio'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Agregar otra especie', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza otras actividades productivas no agropecuarias o agroindustriales en el predio'              // Campo donde se reanuda el formulario
                            ],
                            
                             [
                                'trigger_field' => 'Realiza otras actividades productivas no agropecuarias o agroindustriales en el predio', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva'              // Campo donde se reanuda el formulario
                            ],
                            
                             [
                                'trigger_field' => 'Cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuenta con infraestructura para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva'              // Campo donde se reanuda el formulario
                            ],
                            
                             [
                                'trigger_field' => 'Cuenta con infraestructura para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Durante el último año ha recibido asistencia técnica'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Durante el último año ha recibido asistencia técnica', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Ha sido beneficiario de algún proyecto para el desarrollo agropecuario'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Ha sido beneficiario de algún proyecto para el desarrollo agropecuario', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Hace parte de alguna Asociación u organización de productores agropecuarios'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Hace parte de alguna Asociación u organización de productores agropecuarios', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Ha solicitado crédito para el desarrollo de las actividades agropecuarias'              // Campo donde se reanuda el formulario
                            ],
                            

                            [
                                'trigger_field' => 'Ha solicitado crédito para el desarrollo de las actividades agropecuarias', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuántas personas (incluido el productor y los miembros del núcleo trabajaron de manera permanente en la Unidad Productiva Agropecuaria para realizar las actividades productivas en los últimos 30 días'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Ha realizado control de plagas y enfermedades en la Unidad productiva', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Conoce y aplica Buenas Prácticas Agrícolas Ganaderas en la Unidad Productiva'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Usa protección para la aplicación de plaguicidas', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Los trabajadores se bañan una vez terminada la aplicación de plaguicidas'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Tiene o posee otro(s) predio(s) no continuos en los que desarrolla actividades productivas agropecuarias', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Se ha visto afectado por algún fenómeno natural extremo en el último año'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Se ha visto afectado por algún fenómeno natural extremo en el último año', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Nombres y apellidos del encuestador'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Agregar otra actividad', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Qué solución propone para superar la afectación'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Cuenta con fuentes hídricas dentro del predio', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Vías de acceso a la finca'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Ha enfrentado barreras para el acceso a crédito', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Cuántas personas (incluido el productor y los miembros del núcleo trabajaron de manera permanente en la Unidad Productiva Agropecuaria para realizar las actividades productivas en los últimos 30 días'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Lleva registros de las actividades que desarrolla en la unidad productiva', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Tiene o posee otro(s) predio(s) no continuos en los que desarrolla actividades productivas agropecuarias'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Aplicó el fertilizante siguiendo parámetros técnicos y o indicaciones de un profesional', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Realiza control de arvenses'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Realiza control de arvenses', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Ha realizado control de plagas y enfermedades en la Unidad productiva'              // Campo donde se reanuda el formulario
                            ],

                             [
                                'trigger_field' => 'Posee sistema de riego para los cultivos', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Uso del suelo en el predio: Agricultura (ha)'              // Campo donde se reanuda el formulario
                            ],
                           
                            [
                                'trigger_field' => 'Acuicultura', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Búfalos, equinos, ovinos o caprinos'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Búfalos, equinos, ovinos o caprinos', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Otras especies'              // Campo donde se reanuda el formulario
                            ],

                            [
                                'trigger_field' => 'Otras especies', // Campo que dispara la acción
                                'trigger_value' => 'No',                                        // Valor que activa el salto
                                'skip_to_field' => 'Especie diferente a las anteriores'              // Campo donde se reanuda el formulario
                            ],
                            
                           


                          
                            // EJEMPLO 2: DEPENDENCIA INDIVIDUAL
                            // Muestra campos específicos SOLO si la respuesta es la indicada (ej: "Si")
                            /*
                            [
                                'trigger_field' => '¿Tiene vehículo?',
                                'trigger_value' => 'Si',
                                'dependent_fields' => ['Placa', 'Modelo']
                            ],
                            */
                        ];

                        // Combinar reglas manuales con las existentes
                        $conditionalRules = array_merge($conditionalRules ?? [], $manualRules);

                        $conditionalFieldMap = [];
                        $rangeRules = [];
                        foreach ($conditionalRules as $rule) {
                            if (isset($rule['skip_to_field'])) {
                                $rangeRules[] = $rule;
                            } else {
                                foreach ($rule['dependent_fields'] as $field) {
                                    $conditionalFieldMap[$field] = [
                                        'trigger_field' => $rule['trigger_field'],
                                        'trigger_value' => $rule['trigger_value']
                                    ];
                                }
                            }
                        }
                    @endphp

                    {{-- Variables JavaScript para reglas condicionales --}}
                    <script>
                        @if(!empty($rangeRules))
                            window.conditionalRangeRules = @json($rangeRules);
                        @else
                            window.conditionalRangeRules = [];
                        @endif
                    </script>

                    <div class="form-header">
                        <h2 class="form-title">Datos del Registro</h2>
                    </div>

                    {{-- Mensaje de advertencia --}}
                    <div id="mensaje-advertencia" class="mensaje-advertencia" style="display: none;">
                        <div class="mensaje-advertencia-content">
                            <div class="mensaje-advertencia-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="mensaje-advertencia-text">
                                <h4>⚠️ Documento Duplicado</h4>
                                <div id="mensaje-advertencia-detalles"></div>
                                <p><strong>No se puede agregar este registro porque el documento ya existe en la caracterización.</strong></p>
                            </div>
                            <div class="mensaje-advertencia-actions">
                                <button type="button" id="btn-aceptar" class="btn-aceptar">
                                    <i class="fas fa-check"></i>
                                    Aceptar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Información del registro actual --}}
                    <div class="registro-info">
                        <div class="registro-current">
                            <i class="fas fa-user-edit"></i>
                            <span>Completando registro <span id="registro-actual">1</span></span>
                        </div>
                        <div class="registros-total">
                            <span id="total-registros">0</span> registro(s) agregado(s)
                        </div>
                    </div>

                    {{-- Formulario dinámico basado en columnas de caracterización --}}
                    <div id="registro-form" class="registro-form-section">
                        <div class="form-grid">
                            @foreach($columnasReferencia as $columna)
                                @php
                                    $tipoCampo = 'text'; // Default
                                    $columnaLower = strtolower($columna);
                                    $esCampoAutomatico = false;

                                    // Generar ID válido para JavaScript (versión inline)
                                    $fieldId = strtolower($columna);
                                    // Primero eliminar caracteres especiales
                                    $fieldId = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $fieldId);
                                    // Reemplazar caracteres específicos por guion bajo
                                    $fieldId = str_replace(['#', '/'], '_', $fieldId);
                                    // Reemplazar CUALQUIER espacio en blanco (incluyendo espacios no rompibles) por guion bajo
                                    $fieldId = preg_replace('/\s+/u', '_', $fieldId);
                                    
                                    if (!preg_match('/^[a-zA-Z]/', $fieldId)) {
                                        $fieldId = 'field_' . $fieldId;
                                    }

                                    // Detectar si es campo automático
                                    if (preg_match('/(^|[\s_])id($|[\s_])/', $columnaLower) && !str_contains($columnaLower, 'cedula') && !str_contains($columnaLower, 'documento')) {
                                        $esCampoAutomatico = true;
                                        $tipoCampo = 'text';
                                    } elseif (str_contains($columnaLower, 'hora') && str_contains($columnaLower, 'inicio') && !str_contains($columnaLower, 'nombres') && !str_contains($columnaLower, 'apellidos')) {
                                        $esCampoAutomatico = true;
                                        $tipoCampo = 'datetime-local';
                                    } elseif (str_contains($columnaLower, 'hora') && str_contains($columnaLower, 'final') && !str_contains($columnaLower, 'nombres') && !str_contains($columnaLower, 'apellidos')) {
                                        $esCampoAutomatico = true;
                                        $tipoCampo = 'datetime-local';
                                    } elseif (str_contains($columnaLower, 'correo electrónico del tabulador') || str_contains($columnaLower, 'correo electronico del tabulador') || str_contains($columnaLower, 'email del tabulador')) {
                                        $esCampoAutomatico = true;
                                        $tipoCampo = 'email';
                                    }

                                    // Detectar tipos de campo automáticamente
                                    if (!$esCampoAutomatico) {
                                        // Primero verificar si la columna tiene opciones específicas definidas
                                        $tieneOpcionesEspecificas = false;
                                        $columnaNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $columna));
                                        $columnaNormalizedNoDigits = preg_replace('/\d+/', '', $columnaNormalizedFull);
                                        foreach ($opcionesColumnas as $key => $opciones) {
                                            $keyNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $key));
                                            $keyNormalizedNoDigits = preg_replace('/\d+/', '', $keyNormalizedFull);
                                            if ($columnaNormalizedFull === $keyNormalizedFull || $columnaNormalizedFull === $keyNormalizedNoDigits || $columnaNormalizedNoDigits === $keyNormalizedFull || $columnaNormalizedNoDigits === $keyNormalizedNoDigits || str_contains($columnaNormalizedFull, $keyNormalizedFull)) {
                                                $tieneOpcionesEspecificas = true;
                                                break;
                                            }
                                        }

                                        if ($tieneOpcionesEspecificas && (str_contains($columnaLower, 'numero de documento') || str_contains($columnaLower, 'numero de documento de identidad del encuestado'))) {
                                            $tieneOpcionesEspecificas = false;
                                        }

                                        if ($tieneOpcionesEspecificas) {
                                            // Definir qué campos deben ser checkbox (selección múltiple)
                                            if (str_contains($columnaLower, 'fuente de la electricidad') || str_contains($columnaLower, 'medios de comunicación') || str_contains($columnaLower, 'maquinaria') || str_contains($columnaLower, 'infraestructura') ||
                                            str_contains($columnaLower, 'cual') || str_contains($columnaLower, 'combustible y o fuente energética para cocinar') ||
                                            str_contains($columnaLower, 'que afectación o daño hubo en la unidad productiva')


                                            ) {
                                                $tipoCampo = 'checkbox';
                                            } else {
                                                $tipoCampo = 'select';
                                            }
                                        } elseif (str_contains($columnaLower, 'genero') || str_contains($columnaLower, 'tenencia') ||
                                                  str_contains($columnaLower, 'categoria') ||
                                                 (str_contains($columnaLower, 'estado') && !str_contains($columnaLower, 'encuestador')) || str_contains($columnaLower, 'condicion') ||
                                                 str_contains($columnaLower, 'corregimiento') || str_contains($columnaLower, 'está legalmente constituida')||
                                                 str_contains($columnaLower, 'cuenta') ||
                                                 str_contains($columnaLower, 'pertenece a una población de especial protección constitucional') || str_contains($columnaLower, 'población') ||
                                                 str_contains($columnaLower, 'sabe leer y escribir') || str_contains($columnaLower, 'agregar') ||
                                                 str_contains($columnaLower, 'nucleo familiar') || str_contains($columnaLower, 'posee') || 
                                                 str_contains($columnaLower, 'alimentario') || str_contains($columnaLower, 'tiene una marca para sus productos agroindustriales') ||
                                                 str_contains($columnaLower, 'está registrada ante cámara de comercio') || str_contains($columnaLower, 'tiene registro sanitario invima') ||
                                                 str_contains($columnaLower, 'hace parte de alguna asociación u organización de productores agropecuarios') || str_contains($columnaLower, 'ha enfrentado barreras para el acceso a crédito') ||
                                                 str_contains($columnaLower, 'aplicó fertilizantes a los cultivos en el último año') || str_contains($columnaLower, 'ha realizado análisis de suelos en los últimos 3 años') ||
                                                 str_contains($columnaLower, 'aplicó el fertilizante siguiendo parámetros técnicos y o indicaciones de un profesional') || str_contains($columnaLower, 'ha realizado control de plagas y enfermedades en la Unidad productiva') ||
                                                str_contains($columnaLower, 'conoce y aplica Buenas Prácticas Agrícolas/Ganaderas en la Unidad Productiva') || str_contains($columnaLower, 'acostumbra a lavar y desinfectar las herramientas que emplea en el cultivo') ||
                                                str_contains($columnaLower, 'conoce los grados de toxicidad de los plaguicidas') || str_contains($columnaLower, 'usa protección para la aplicación de plaguicidas') ||
                                                str_contains($columnaLower, 'los trabajadores se bañan una vez terminada la aplicación de plaguicidas') || str_contains($columnaLower, 'conoce y respeta el tiempo de carencia de los plaguicidas') ||
                                                str_contains($columnaLower, 'se ha visto afectado por algún fenómeno natural extremo en el último año') || str_contains($columnaLower, 'realiza otras actividades productivas no agropecuarias o agroindustriales en el predio') ||
                                                str_contains($columnaLower, 'esta actividad es principal o primaria frente a la generacion de ingresos para el productor') || str_contains($columnaLower, 'cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva') ||
                                                str_contains($columnaLower, 'durante el último año ha recibido asistencia técnica') || str_contains($columnaLower, 'cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva') ||
                                                str_contains($columnaLower, 'ha sido beneficiario de algún proyecto para el desarrollo agropecuario') || str_contains($columnaLower, 'cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva') ||
                                                str_contains($columnaLower, 'empleó trabajo colectivo para realizar las actividades agropecuarias en los últimos 30 días (minga, convite, mano de obra prestada)') || str_contains($columnaLower, 'cuenta con maquinaria y o equipo para el desarrollo de actividades agropecuarias o agroindustriales en la unidad productiva') ||
                                                str_contains($columnaLower, 'ha realizado control de plagas y enfermedades en la unidad productiva') || str_contains($columnaLower, 'conoce y aplica buenas prácticas agrícolas ganaderas en la unidad productiva') ||
                                                str_contains($columnaLower, 'ingresan al cultivo nuevamente después de la aplicación de plaguicidas') || str_contains($columnaLower, 'lleva registros de las actividades que desarrolla en la unidad productiva') ||
                                                str_contains($columnaLower, 'la mayor parte del terreno que conforma esta unidad productiva agropecuaria es:') || 
                                                str_contains($columnaLower, 'principales fuentes de ingresos del hogar actividades agricolas') || str_contains($columnaLower, 'comercialización') || str_contains($columnaLower, 'autoconsumo') ||
                                                str_contains($columnaLower, 'búfalos, equinos, ovinos o caprinos') ||
                                            str_contains($columnaLower, 'acuicultura') || str_contains($columnaLower, 'otras especies')

                                                 ) {
                                            $tipoCampo = 'select';
                                        } elseif (str_contains($columnaLower, 'fecha') || str_contains($columnaLower, 'date')) {
                                            $tipoCampo = 'date';
                                        
                                        } elseif (str_contains($columnaLower, 'correo') || str_contains($columnaLower, 'email') || str_contains($columnaLower, 'mail')) {
                                            $tipoCampo = 'email';
                                        } elseif (str_contains($columnaLower, 'evidencia') && (str_contains($columnaLower, 'foto') || str_contains($columnaLower, 'fotograf'))) {
                                            $tipoCampo = 'file';
                                        } elseif (str_contains($columnaLower, 'cedula') || str_contains($columnaLower, 'cédula') ||
                                                  str_contains($columnaLower, 'dni')) {
                                            $tipoCampo = 'text'; // Mantener como text para números largos
                                        } elseif (str_contains($columnaLower, 'precio') || str_contains($columnaLower, 'valor') || str_contains($columnaLower, 'numero predial') ||
                                                  str_contains($columnaLower, 'numero de documento de identidad del encuestado') || str_contains($columnaLower, 'número de documento') || 
                                                  str_contains($columnaLower, 'edad') || str_contains($columnaLower, 'Área (ha)') || str_contains($columnaLower, 'hombres') ||
                                                  str_contains($columnaLower, 'área') || str_contains($columnaLower, 'cantidad') || str_contains($columnaLower, 'mujeres') ||  str_contains($columnaLower, 'cuántas personas (incluido el productor y los miembros del núcleo trabajaron de manera permanente en la unidad productiva agropecuaria para realizar las actividades productivas en los últimos 30 días') ||
                                                  str_contains($columnaLower, 'celular') || str_contains($columnaLower, 'altitud') || str_contains($columnaLower, 'cuántos de los trabajadores permanentes pertenecen al hogar del productor') ||
                                                str_contains($columnaLower, 'uso del suelo en el predio agricultura (ha) ') ||  str_contains($columnaLower, 'ganaderia (ha)') ||  str_contains($columnaLower, 'conservacion (ha)') || str_contains($columnaLower, 'jornales') || str_contains($columnaLower, 'rastrojo (ha)') ||
                                               str_contains($columnaLower, 'hembras') || str_contains($columnaLower, 'machos') || str_contains($columnaLower, 'número de plantas') || str_contains($columnaLower, 'nivel de producción anual apróx (kilos)')
                                               || str_contains($columnaLower, 'capacidad de producción mensual') || str_contains($columnaLower, 'número de contacto del encuestador')
                                               || str_contains($columnaLower, 'unidades afectadas')
                                               
                                               
                                                  ) {
                                            $tipoCampo = 'number';
                                        }

                                        // Corrección: Forzar campos de ingresos y pecuaria (incluyendo typos reportados) a number
                                        // No sobrescribir si ya fue clasificado explícitamente como 'select'
                                        if ($tipoCampo !== 'select') {
                                            if (str_contains($columnaLower, 'funete') || 
                                                str_contains($columnaLower, 'ingrsos') || 
                                                (str_contains($columnaLower, 'pecuaria') && 
                                                 !str_contains($columnaLower, 'asociación') && 
                                                 !str_contains($columnaLower, 'maquinaria') && 
                                                 !str_contains($columnaLower, 'actividades') && 
                                                 !str_contains($columnaLower, 'buenas prácticas'))) {
                                                $tipoCampo = 'number';
                                            }
                                        }
                                        if (trim($columnaLower) === 'actividades pecuarias') {
                                            $tipoCampo = 'select';
                                        }
                                        if (trim($columnaLower) === 'realiza actividades pecuarias') {
                                            $tipoCampo = 'select';
                                        }
                                        
                                        // Forzar que el campo de documento del encuestado sea numérico
                                        if (str_contains($columnaLower, 'numero de documento de identidad del encuestado')) {
                                            $tipoCampo = 'number';
                                        }
                                    }
                                @endphp

                                @php
                                    $triggerFieldId = '';
                                    if (isset($conditionalFieldMap[$columna])) {
                                        $tf = strtolower($conditionalFieldMap[$columna]['trigger_field']);
                                        $tf = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $tf);
                                        $tf = str_replace(['#', '/'], '_', $tf);
                                        $tf = preg_replace('/\s+/u', '_', $tf);
                                        if (!preg_match('/^[a-zA-Z]/', $tf)) {
                                            $tf = 'field_' . $tf;
                                        }
                                        $triggerFieldId = $tf;
                                    }
                                @endphp

                                <div class="form-group" @if(isset($conditionalFieldMap[$columna])) data-conditional="true" data-trigger-field="{{ $triggerFieldId }}" data-trigger-value="{{ $conditionalFieldMap[$columna]['trigger_value'] }}" style="display: none;" @endif>
                                    <label class="form-label">
                                        <i class="fas fa-tag"></i>
                                        {{ $columna }}
                                        <span class="required-indicator">*</span>
                                        @if($esCampoAutomatico)
                                            <span style="color: var(--verde); font-size: 0.8rem;">(Automático)</span>
                                        @endif
                                    </label>
                                    @php
                                        $columnaNormalizedExact = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $columna));
                                        $isDocField = trim($columnaNormalizedExact) === 'numero de documento de identidad del encuestado';
                                    @endphp
                                    @if($isDocField)
                                        <div id="duplicate-{{ $fieldId }}" class="field-error" style="color:#dc3545"></div>
                                    @endif

                                    @if($tipoCampo === 'select')
                                        <select id="{{ $fieldId }}" class="form-control form-select select-otro-trigger"
                                                @if($esCampoAutomatico) readonly disabled @endif>
                                            <option value="">Seleccione</option>
                                            @php
                                                $columnaNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $columna));
                                                $columnaNormalizedNoDigits = preg_replace('/\d+/', '', $columnaNormalizedFull);
                                                $opcionesEncontradas = null;
                                                $tieneOpcionOtro = false;

                                                // Buscar opciones específicas para esta columna (priorizar coincidencia exacta y luego la coincidencia más larga)
                                                $bestLen = 0;
                                                foreach ($opcionesColumnas as $key => $opciones) {
                                                    $keyNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $key));
                                                    $keyNormalizedNoDigits = preg_replace('/\d+/', '', $keyNormalizedFull);
                                                    if ($columnaNormalizedFull === $keyNormalizedFull) {
                                                        $opcionesEncontradas = $opciones;
                                                        $bestLen = strlen($keyNormalizedFull);
                                                        break;
                                                    } elseif ($columnaNormalizedFull === $keyNormalizedNoDigits) {
                                                        $opcionesEncontradas = $opciones;
                                                        $bestLen = strlen($keyNormalizedNoDigits);
                                                        break;
                                                    } elseif ($columnaNormalizedNoDigits === $keyNormalizedFull) {
                                                        $opcionesEncontradas = $opciones;
                                                        $bestLen = strlen($keyNormalizedFull);
                                                        break;
                                                    } elseif ($columnaNormalizedNoDigits === $keyNormalizedNoDigits) {
                                                        $opcionesEncontradas = $opciones;
                                                        $bestLen = strlen($keyNormalizedNoDigits);
                                                        break;
                                                    } elseif (str_contains($columnaNormalizedFull, $keyNormalizedFull)) {
                                                        $len = strlen($keyNormalizedFull);
                                                        if ($len > $bestLen) {
                                                            $bestLen = $len;
                                                            $opcionesEncontradas = $opciones;
                                                        }
                                                    }
                                                }

                                                if ($opcionesEncontradas) {
                                                    foreach ($opcionesEncontradas as $opcion) {
                                                        echo "<option value=\"{$opcion}\">{$opcion}</option>";
                                                        if (in_array(strtolower($opcion), ['otro', 'otros', 'otras', 'otra'])) {
                                                            $tieneOpcionOtro = true;
                                                        }
                                                    }
                                                } elseif (str_contains(strtolower($columna), 'género') || str_contains(strtolower($columna), 'sexo')) {
                                                    echo "<option value=\"Masculino\">Masculino</option>";
                                                    echo "<option value=\"Femenino\">Femenino</option>";
                                                   echo "<option value=\"No binario\">No Binario</option>";
                                                    echo "<option value=\"Otro\">Otro</option>";
                                                    $tieneOpcionOtro = true;
                                                } elseif (str_contains(strtolower($columna), 'condicion')) {
                                                    $condiciones = ['Ninguno', 'Afrocolombiano', 'Campesino', 'Indígena', 'LGBTIQ+', 'Persona mayor', 'Cabeza de familia', 'Mujer rural', 'Desmovilizado', 'Reinsertado', 'Joven rural', 'Persona con discapacidad', 'Víctima del conflicto (RUV)', 'Cuidador/a', 'Otro'];
                                                    foreach ($condiciones as $condicion) {
                                                        echo "<option value=\"{$condicion}\">{$condicion}</option>";
                                                    }
                                                    $tieneOpcionOtro = true;
                                                } elseif (str_contains(strtolower($columna), 'vereda')) {
                                                    // Campo de Vereda: opciones se poblarán dinámicamente vía JS según Corregimiento
                                                    // No agregar opciones "Sí/No" por defecto
                                                } else {
                                                    echo "<option value=\"Sí\">Sí</option>";
                                                    echo "<option value=\"No\">No</option>";
                                                }
                                            @endphp
                                        </select>
                                        @if($tieneOpcionOtro)
                                            <input type="text" id="{{ $fieldId }}_otro" class="form-control mt-2 input-otro-especificar" style="display: none;" placeholder="Especifique cuál...">
                                        @endif
                                    @elseif($tipoCampo === 'checkbox')
                                        {{-- Campo oculto que almacenará los valores separados por coma (para compatibilidad con el JS existente) --}}
                                        <input type="hidden" id="{{ $fieldId }}" class="form-control checkbox-result">
                                        
                                        <div class="checkbox-group-container p-2 border rounded" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                                            @php
                                                $columnaNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $columna));
                                                $columnaNormalizedNoDigits = preg_replace('/\d+/', '', $columnaNormalizedFull);
                                                $opcionesEncontradas = null;
                                                $tieneOpcionOtroCheckbox = false;

                                                foreach ($opcionesColumnas as $key => $opciones) {
                                                    $keyNormalizedFull = strtolower(str_replace([' ', '_', '-', '(', ')', ':', '¿', '?', '¡', '!', ',', '.', ';'], ['', '', '', '', '', '', '', '', '', '', '', ''], $key));
                                                    $keyNormalizedNoDigits = preg_replace('/\d+/', '', $keyNormalizedFull);
                                                    if ($columnaNormalizedFull === $keyNormalizedFull || $columnaNormalizedFull === $keyNormalizedNoDigits || $columnaNormalizedNoDigits === $keyNormalizedFull || $columnaNormalizedNoDigits === $keyNormalizedNoDigits) {
                                                        $opcionesEncontradas = $opciones;
                                                        break;
                                                    }
                                                }

                                                if ($opcionesEncontradas) {
                                                    foreach ($opcionesEncontradas as $index => $opcion) {
                                                        echo '<div class="form-check mb-1">';
                                                        echo '<input class="form-check-input checkbox-dynamic" type="checkbox" value="' . $opcion . '" id="' . $fieldId . '_' . $index . '" data-target="' . $fieldId . '">';
                                                        echo '<label class="form-check-label" for="' . $fieldId . '_' . $index . '">' . $opcion . '</label>';
                                                        echo '</div>';
                                                        
                                                        if (in_array(strtolower($opcion), ['otro', 'otros', 'otras', 'otra'])) {
                                                            $tieneOpcionOtroCheckbox = true;
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </div>
                                        @if($tieneOpcionOtroCheckbox)
                                            <input type="text" id="{{ $fieldId }}_otro" class="form-control mt-2 input-otro-especificar-checkbox" style="display: none;" placeholder="Especifique cuál..." data-target="{{ $fieldId }}">
                                        @endif
                                    @elseif($tipoCampo === 'file')
                                        <input type="file" id="{{ $fieldId }}_file" class="form-control" accept="image/*">
                                        <input type="text" id="{{ $fieldId }}" class="form-control mt-2" readonly>
                                        <div id="{{ $fieldId }}_status" class="small text-muted mt-1"></div>
                                    @else
                                        <input type="{{ $tipoCampo }}" id="{{ $fieldId }}"
                                               class="form-control"
                                               placeholder="@if($esCampoAutomatico) Se generará automáticamente @else Ingrese {{ strtolower($columna) }} @endif"
                                               @if($esCampoAutomatico || str_contains($columnaLower, 'numero predial')) readonly @endif
                                               @if($tipoCampo === 'number') step="any" @endif>
                                    @endif

                                    <div id="error-{{ $fieldId }}" class="field-error"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lista de registros agregados --}}
                    <div id="registros-agregados" class="registros-agregados" style="display: none;">
                        <h4 class="agregados-title">
                            <i class="fas fa-users"></i>
                            Registros Agregados
                        </h4>
                        <div id="lista-registros" class="lista-registros">
                            <!-- Los registros agregados se mostrarán aquí -->
                        </div>
                    </div>

                    {{-- Botones de acción eliminados por requerimiento --}}

                    {{-- Campo oculto para datos acumulados --}}
                    <input type="hidden" name="beneficiarios_acumulados" id="beneficiarios_acumulados" value="[]">

                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No hay columnas definidas en la caracterización.
                    </div>
                @endif

                {{-- Acciones --}}
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Terminar y Guardar Registro
                    </button>
                    <a href="{{ auth()->user()->hasRole('Administrador') ? route('caracterizaciones.index') : route('dashboard') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('caracterizacionForm');
    const btnAgregar = document.getElementById('btn-agregar-registro');
    const btnLimpiar = document.getElementById('btn-limpiar-formulario');
    const inputAcumulados = document.getElementById('beneficiarios_acumulados');
    const lista = document.getElementById('lista-registros');
    const total = document.getElementById('total-registros');
    const actual = document.getElementById('registro-actual');
    const contenedorAgregados = document.getElementById('registros-agregados');

    let registros = [];
    let contador = 1;

    // Variables para campos automáticos
    let horaInicioFormulario = null;
    let horaFinFormulario = null;
    let contadorRegistros = {{ $siguienteId ?? 1 }};
    const loggedEmail = "{{ Auth::user()->email ?? '' }}";

    // Mapeo de columnas con opciones predefinidas
    const opcionesColumnas = {
        'Tipo de documento' : ['Cédula de Ciudadanía(CC)', 'Tarjeta de Identidad(TI)', 'Registro Civil (RC)','Cédula de Extranjería(CE)', 'Carné diplomático(CD)', 'Salvoconducto(SC)', 'Permiso especial de Permanencia(PEP)',  'Documento extranjero(DE)','Otro'],
        'Tipo de documento del encuestado': ['Cédula de Ciudadanía(CC)','Cédula de Extranjería(CE)', 'Carné diplomático(CD)', 'Salvoconducto(SC)', 'Permiso especial de Permanencia(PEP)',  'Documento extranjero(DE)','Otro'],

        'Corregimiento': ['1', '2', '3'],
        '¿Cuenta con un medio de transporte propio? ¿Cual?': ['Motocicleta', 'Automóvil', 'Furgón/Camión','Ninguno', 'Otras'],
        'Tenencia o relación con la tierra': ['Propia', 'Arriendo', 'Aparcería', 'Usufructo', 'Comodato', 'Ocupación de hecho', 'Propiedad colectiva', 'Adjudicatario o Comunero', 'Adjudicatario(a)/ Viviente', 'Otras'],
        '¿Pertenece a una población de especial protección constitucional?': ['Campesino', 'Mujer rural', 'Joven rural', 'Persona mayor', 'Persona con discapacidad', 'Cuidador/a', 'Víctima del conflicto (RUV)','Población étnica', 'LGBTIQ+',  'Cabeza de familia',  'Desmovilizado', 'Firmante del Acuerdo de Paz','Otro'],
        'Población': ['Población étnica',  'Persona con discapacidad', 'Víctima del conflicto armado', 'Persona mayor', 'Población con orientación sexualmente diversa','Joven rural', 'Mujer rural', 'Cuidador/a', 'Desmovilizado/Firmante del Acuerdo de Paz',  'Otro'],
        'Material predominante de los pisos de esta vivienda': ['Mármol, parqué, madera pulida y lacada', 'Baldosa, vinilo, tableta, ladrillo, laminado', 'Cemento, gravilla', 'Madera sin pulir, otros', 'Tierra, arena, barro'],
        'Material predominante de las paredes exteriores de la vivienda': ['Bloque, ladrillo, piedra, madera pulida', 'Concreto vaciado', 'Material prefabricado', 'Tapia pisada, bahareque, adobe', 'Madera burda, tabla, guadua, otros materiales de origen vegetal', 'Otros materiales (Zinc, tela, cartón, plásticos)'],
        'Fuente de agua para el consumo humano': ['Acueducto metropolitano', 'Acueducto veredal', 'Nacimiento / quebrada', 'Pila comunitaria','Otras'],
        'Combustible y o fuente energética para cocinar': ['Madera', 'Gas Natural', 'Gas Natural', 'Gas propano','Electricidad', 'Carbón', 'Biogás', 'Ninguno'],
        'Medios de comunicación de los cuales dispone en la finca': ['Prensa', 'Radio', 'Televisión','Correo electrónico', 'Internet', 'Celular', 'Telefono fijo'],
        'Uso de las fuentes hídricas con que cuenta el predio': ['Agropecuario', 'Doméstico'],
        'Vías de acceso a la finca': ['Carretera pavimentada', 'Carretera destapada', 'Camino de herradura', 'Otro'],
        '¿Donde almacena las herramientas e insumos agropecuarios que emplea en sus labores?': ['En la vivienda', 'En bodega contigua a la vivienda', 'Al aire libre'],
        'Tipo de servicio sanitario (inodoro) que tiene la vivienda': ['Inodoro conectado al alcantarillado', 'Inodoro conectado a pozo séptico', 'Inodoro sin conexión', 'Letrina', 'Inodoro con descarga directa a fuente de agua', 'No cuenta con servicio sanitario'],
        'Tipo de sistema de riego empleado': ['Superficial (Por gravedad o inundación)', 'Presurizado (Goteo, aspersión, microaspersión)', 'Manual o por mateo'],
        'El destino final de la producción es': ['Autoconsumo', 'Venta a intermediarios (plazas de mercado/ Central de abastos)', 'Venta a cooperativa', 'Mercadillos campesinos', 'Exportación', 'Otras'],
        'género': ['Masculino', 'Femenino','No Binario', 'Otro'],
        'Parentesco con el jefe del hogar': ['Cabeza del hogar(jefe o jefa) ', 'Pareja (Cónyuge, compañero/a, esposo/a)','Hijo/a, hijastro/a', 'Yerno, nuera', 'Nieto/a', 'Hermano/a, hermanastro/a', 'Otro pariente','Empleado/a domestico/a', 'Otro no pariente'],

        'nivel educativo': ['Primaria Completa', 'Primaria incompleta', 'Secundaria incompleta', 'Secundaria completa', 'Técnico', 'Tecnológica', 'Profesional', 'Especializacion', 'Maestria','Doctorado', 'Ninguna'],
        'Tipo de vivienda': ['Casa', 'Apartamento', 'Tipo cuarto', 'No hay vivienda', 'Otro'],
        'condiciones de ocupación de la vivienda': ['Ocupada por la familia', 'Vivienda temporal (Vacaciones, trabajo, etc.)', 'Desocupada', 'Ocupada por viviente(s) y los dueños no viven en le predio'],  
        'Tipo de fuente hídrica con que cuenta el predio': ['Nacimiento', 'Rio', 'Quebrada', 'Lago', 'Pozo', 'Otro'],
        'Tipo de maquinaria y o equipo': ['Ahoyadora', 'Equipo de inseminacion', 'Fumigadora', 'Guadañadora', 'Motosierra', 'Picadora de pasto', 'Hidrolavadora', 'Motobomba','Sistema de riego', 'Tostadora de café / cacao', 'Trilladora', 'Molino', 'Despulpadora', 'Módulo ecólogico para el despulpado de café', 'Planta eléctrica ', 'Báscula', 'Minitractor / Motocultor', 'Cajón de fermentador de cacao', 'Otro'],
        'Con que Frecuencia realiza control de arvenses': ['Mensual', 'Trimestral','Semestral', 'Anual'],

        'Tipo de infraestructura': ['Aprisco', 'Bodega de almacenamiento de agro insumos ', 'Manga', 'Corral', 'Embarcado', 'Área de manejo de residuos sólidos (ordinarios y peligrosos)', 'Beneficiadero de café ', 'Biodigestor','Brete', 'Compostera', 'Establo', 'Galpón', 'Invernadero', 'Pesebrera', 'Silo', 'Marquesina', 'Casa elva', 'Vivero', 'Trapiche', 'Otro'],
        'En que consistió el proyecto': ['Entrega de insumos, herramientas y/o equipos', 'Transferencia de conocimientos', 'Transferencia económica', 'Construcción o adecuación de infraestructura', 'Otro'],
        'Qué barreras enfrentó': ['Falta de garantías (recursos económicos o tierra para exigidos como garantía para otorgar crédito', 'Falta de información y/o educación financiera (falta de conocimiento sobre productos financieros, tasas de interés y cómo gestionarlos)', 'Ingresos irregulares', 'Otro'],
        'Qué tipo de fertilizantes empleó': ['Químico', 'Orgánico', 'Mixto'],
        'Método de aplicacion': ['Edáfica', 'Foliar', 'Mixto'],
        'Frecuencia de aplicación': ['Semanal', 'Mensual', 'Trimestral', 'Anual'],
        'Realiza control': ['Manual', 'Mecánico', 'Químico', 'Biológico', 'No'],
        'Tipo de control': ['Químico', 'Biologico', 'Otro'],
        'Qué elementos de protección emplea': ['Gafas', 'Guantes', 'Mascarilla', 'Botas', 'Traje impermeable', 'Otro'],
        'Qué información registra': ['Ingresos y egresos', 'Aplicación de fertilizantes', 'Cosecha', 'Inventario de insumos, herramientas y/o equipos', 'Mano de obra empleada', 'Otro'],
        'Qué fenómeno natural lo afecto': ['Lluvia torrencial', 'Sequía', 'Ola de calor', 'Ola de frío', 'Vientos fuertes', 'Terremoto','Deslizamiento / Remoción de masa', 'Inundación', 'Desboradmiento de ríos / quebradas', 'Otro'],
        'Qué solución propone para superar la afectación': ['Implementar sistemas de riego por goteo', 'Entrega de tanques para el almacenamiento de agua', 'Reconversión de cultivos con variedades mejoradas', 'Reforestación', 'Transferencia de conocimientos', 'Complementos nutricionales','Apoyo para el acceso a crédito y/o alivios en obligaciones crediticias (reducción de intereses, acuerdo de pago y condonación parcial de la deuda)', 'Entrega de insumos y/o materialespara la resiembra de cultivos', 'Entrega de materiales para la reparación y/o adecuación de vivienda', 'Construcción de vivienda nueva', 'Reubicación', 'Otro'],
        'Destino de aguas residuales': ['Alcantarillado', 'Pozo séptico', 'Ninguno'],
        'La mayor parte del terreno que conforma esta unidad productiva agropecuaria es:': ['Plano', 'Quebrado (con pendiente)'],
        'Realiza actividades productivas agrícolas': ['Si', 'No'],
        'Realiza actividades agroindustriales': ['Si', 'No'],                    
        'Realiza actividades pecuarias': ['Si', 'No'],
        'Orientación de la actividad': ['Cría', 'Levante','Ceba',  'Ciclo completo', 'Genética', 'Engorde', 'Producción de huevo', 'Ornamentales', 'Mascotas', 'Otro'],
        'Qué entidad lo gestionó': ['Alcaldía', 'Gobernación','Ministerio de Agricultura',  'Agencia de Desarrollo Rural', 'Entidad prestadora de Extensión Agropecuaria (EPSEA)', 'Otro'],
        'Ha solicitado crédito para el desarrollo de las actividades agropecuarias': ['Si', 'No'],
        'Qué hace con los envases de plaguicidas vacíos':['Triple lavado', 'Los entierra', 'Los quema', 'Los tira en el lote', 'Los reutiliza', 'Los rompe o perfora y los entrega a la empresa de aseo municipal'],
        'Fuente de la electricidad': ['Redes eléctricas', 'Generador', 'Panel solar', 'Otro'],
        'Que afectación o daño hubo en la unidad productiva': ['Destrucción de cultivos', 'Destrucción de Infraestructura', 'Pérdida de ganado/animales', 'Pérdida de cosecha', 'Pérdida de terreno', 'Alteración del ciclo productivo', 'Reducción del rendimiento y calidad de los productos agrícolas', 'Destrucción parcial o total de la vivienda', 'Otro'],
        'Principales fuentes de ingresos del hogar actividades Agricolas': ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Actividades Pecuarias': ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Empleo Formal': ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Actividades Comerciales': ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Lugar de aplicación': ['Casa', 'Finca', 'Lote'],
        'En qué entidad financiera lo solicitó': ['Banco Agrario', 'Cooperativa Financiera', 'Otro'],
        'Tipo de cultivo': ['Café', 'Cacao', 'Aguacate', 'Banano','Platano', 'Yuca','Mango','Mango Tomy','Citricos','Limón','Limón Tahiti', 'Naranja','Mandarina','Uva', 'Mora','Maíz', 'Guanabana','Guayaba', 'Zapote','Maracuya','Pitahaya', 'Hortalizas','Apio','Pimentón', 'Tomate', 'Frijol','Habichuela','Hierbas aromáticas','Otro'],
        'Tiene registro Sanitario INVIMA': ['Si', 'No','No aplica'],
        'Qué porcentaje representa los ingresos de esta actividad frente al total de los ingresos del hogar': ['0%', '10%-20%', '30%-40%', '50%-60%', '70%-80%', '90%-100%'],
        'Actividad productiva': ['Café', 'Cacao', 'Aguacate', 'Banano','Platano', 'Yuca','Mango','Mango Tomy','Citricos','Limón','Limón Tahiti', 'Naranja','Mandarina','Uva','Mora','Maíz', 'Guanabana','Guayaba', 'Zapote','Maracuya','Pitahaya', 'Hortalizas','Apio','Pimentón', 'Tomate', 'Frijol','Habichuela','Hierbas aromáticas','Otro'],
        'Afectación': ['Plantas secas por estrés hidrico', 'Golpe de calor en animales','Perdida de la floración', 'Pasma o aborto de frutos', 'Escasez de alimento por perdida de forrajes', 'Plantas muertas por sequía', 'Muerte de animales', 'Pudrición por exceso de agua','Pérdida de cultivos por deslizamiento', 'Pérdida de animales por deslizamiento','Inundaciones', 'Pérdida de cultivos por heladas', 'Proliferación de enfermedades en animales por ola invernal', 'Proliferación de hongos y enfermedades fitosanitarias en plantas por ola invernal', 'Otro'],
        'Búfalos, equinos, ovinos o caprinos': ['Caballos', 'Yeguas','Mulos', 'Mulas', 'Burros', 'Burras', 'Cabros', 'Cabras','Ovejos', 'Ovejas','Búfalos machos', 'Búfalos hembras', 'No'],
        'Acuicultura': ['Mojarra', 'Cachama','Bocachico', 'Trucha invernal', 'No'],
        'Otras especies': ['Cerdos (traspatio)', 'Gallos, pollos y gallinas de traspatio','Gallos de pelea', 'Picos o pavos', 'Patos y gansos', 'Codornices', 'Avestruces', 'Cuyes', 'Conejos', 'Colmenas de abejas para producción de miel', 'Colmenas de abejas para produccción de polen','Colmenas de abejas para subproductos', 'Colmenas de abejas meliponas', 'Aves ornamentales', 'Caninos hembra', 'Caninos macho', 'Felinos hembra','Felinos macho', 'Tortuga / Morrocoy', 'No'],
        
    };

    // --- LÓGICA DINÁMICA DE VEREDAS ---
    const veredasMap = @json(json_decode(file_get_contents(resource_path('js/veredas.json')), true));

    // Buscar campos de Corregimiento y Vereda
    const corregimientoFields = Array.from(document.querySelectorAll('select, input')).filter(el => el.id.includes('corregimiento'));
    const veredaFields = Array.from(document.querySelectorAll('select, input')).filter(el => el.id.includes('vereda'));

    // Función auxiliar: obtener sufijo numérico al final del id
    function getNumericSuffix(id) {
        const m = (id || '').match(/(\d+)$/);
        return m ? parseInt(m[1], 10) : null;
    }

    // Poblar un select de vereda según un id de corregimiento
    function poblarVeredaDesde(veredaEl, idCorregimiento) {
        if (!veredaEl || veredaEl.tagName !== 'SELECT') return;
        while (veredaEl.options.length > 0) veredaEl.remove(0);
        const defaultOption = document.createElement('option');
        defaultOption.value = "";
        defaultOption.textContent = "Seleccione Vereda";
        veredaEl.add(defaultOption);
        if (idCorregimiento && veredasMap[idCorregimiento]) {
            veredasMap[idCorregimiento].forEach(vereda => {
                const option = document.createElement('option');
                option.value = vereda;
                option.textContent = vereda;
                veredaEl.add(option);
            });
        }
    }

    // Emparejar cada vereda con su corregimiento por sufijo
    if (corregimientoFields.length > 0 && veredaFields.length > 0) {
        const corrList = corregimientoFields.map(el => ({ el, id: el.id, suffix: getNumericSuffix(el.id) }));

        veredaFields.forEach(veredaEl => {
            const vSuffix = getNumericSuffix(veredaEl.id);

            // 1) Si tiene sufijo: buscar corregimiento con el mismo sufijo
            let pair = corrList.find(c => c.suffix !== null && c.suffix === vSuffix);

            // 2) Si no hay, buscar corregimiento con sufijo inmediatamente anterior (ej: vereda11 -> corregimiento10)
            if (!pair && vSuffix !== null) {
                pair = corrList.find(c => c.suffix !== null && c.suffix === (vSuffix - 1));
            }

            // 3) Si aún no hay, y vereda no tiene sufijo, usar el corregimiento sin sufijo (campo base)
            if (!pair && vSuffix === null) {
                pair = corrList.find(c => c.suffix === null) || corrList[0];
            }

            // 4) Si sigue sin emparejar y tiene sufijo, usar el primer corregimiento con cualquier sufijo (pero no el base para evitar cruces)
            if (!pair && vSuffix !== null) {
                pair = corrList.find(c => c.suffix !== null) || corrList[0];
            }

            if (pair && pair.el) {
                // Listener para poblar SOLO este vereda cuando cambie su corregimiento emparejado
                pair.el.addEventListener('change', function () {
                    poblarVeredaDesde(veredaEl, this.value);
                });

                // Poblar inicial si ya hay valor
                if (pair.el.value) {
                    poblarVeredaDesde(veredaEl, pair.el.value);
                } else {
                    // Asegurar estado default
                    poblarVeredaDesde(veredaEl, null);
                }
            }
        });
    }

    // Inicializar campos automáticos al cargar la página
    function inicializarCamposAutomaticos() {
        horaInicioFormulario = new Date().toISOString().slice(0, 16);
            @php
                $loggedEmail = auth()->check() ? auth()->user()->email : (auth()->guard('admin')->check() ? auth()->guard('admin')->user()->email : '');
            @endphp
            const loggedEmail = "{{ $loggedEmail }}";
        
        // Inicializar listeners para checkboxes
        document.querySelectorAll('.checkbox-dynamic').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const container = this.closest('.checkbox-group-container');
                
                // Recopilar todos los marcados en este grupo
                const checkedValues = Array.from(container.querySelectorAll('.checkbox-dynamic:checked'))
                    .map(cb => cb.value);
                
                // Actualizar el input oculto (separado por comas)
                if (targetInput) {
                    targetInput.value = checkedValues.join(', ');
                }
            });
        });

        // --- MANEJO DE OPCIÓN "OTRO" ---
        document.querySelectorAll('.select-otro-trigger').forEach(select => {
            const inputOtro = document.getElementById(select.id + '_otro');
            
            if (inputOtro) {
                // Función para alternar visibilidad
                const toggleOtro = () => {
                    const val = select.value.toLowerCase();
                    const variants = ['otro', 'otros', 'otras', 'otra'];
                    const isOtro = variants.some(v => val.includes(v));
                    
                    if (isOtro) {
                        inputOtro.style.display = 'block';
                        inputOtro.focus();
                    } else {
                        inputOtro.style.display = 'none';
                        inputOtro.value = '';
                    }
                };
                
                // Listener
                select.addEventListener('change', toggleOtro);
                
                // Estado inicial
                // Necesitamos asegurar que el name esté correcto al inicio
                // Como Blade genera el name en el select, si el valor inicial es Otro, debemos moverlo
                // Pero aquí asumimos valor inicial vacío o no-otro.
                // Si estamos editando y viene con "Otro", sería más complejo, pero para nuevos registros:
                toggleOtro();
            }
        });

        const fileInputs = document.querySelectorAll('input[type="file"][id$="_file"]');
        fileInputs.forEach(fi => {
            fi.addEventListener('change', function () {
                const baseId = this.id.replace(/_file$/, '');
                const textInput = document.getElementById(baseId);
                const statusEl = document.getElementById(baseId + '_status');
                if (this.files && this.files[0]) {
                    if (textInput) textInput.value = this.files[0].name;
                    if (statusEl) statusEl.textContent = 'Archivo seleccionado';
                    limpiarErrorCampo(baseId);
                } else {
                    if (textInput) textInput.value = '';
                    if (statusEl) statusEl.textContent = '';
                }
            });
        });

        // Establecer valores en los campos automáticos
        @foreach($columnasReferencia as $columna)
            @php
                $columnaLower = strtolower($columna);
                // Usar EXACTAMENTE la misma lógica de generación de ID que en la parte HTML
                $fieldId = strtolower($columna);
                $fieldId = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $fieldId);
                $fieldId = str_replace(['#', '/'], '_', $fieldId);
                $fieldId = preg_replace('/\s+/u', '_', $fieldId);
                
                if (!preg_match('/^[a-zA-Z]/', $fieldId)) {
                    $fieldId = 'field_' . $fieldId;
                }
                
                $esCampoAutomatico = false;

                if (preg_match('/(^|[\s_])id($|[\s_])/', $columnaLower) && !str_contains($columnaLower, 'cedula') && !str_contains($columnaLower, 'documento')) {
                    $esCampoAutomatico = true;
                } elseif (str_contains($columnaLower, 'hora') && str_contains($columnaLower, 'inicio') && !str_contains($columnaLower, 'nombres') && !str_contains($columnaLower, 'apellidos')) {
                    $esCampoAutomatico = true;
                } elseif (str_contains($columnaLower, 'hora') && str_contains($columnaLower, 'final') && !str_contains($columnaLower, 'nombres') && !str_contains($columnaLower, 'apellidos')) {
                    $esCampoAutomatico = true;
                } elseif (str_contains($columnaLower, 'correo electrónico del tabulador') || str_contains($columnaLower, 'correo electronico del tabulador') || str_contains($columnaLower, 'email del tabulador')) {
                    $esCampoAutomatico = true;
                }
            @endphp

            @if($esCampoAutomatico)
                const campo{{ $fieldId }} = document.getElementById('{{ $fieldId }}');
                if (campo{{ $fieldId }}) {
                    @if(str_contains(strtolower($columna), 'hora') && str_contains(strtolower($columna), 'inicio'))
                        campo{{ $fieldId }}.value = horaInicioFormulario;
                    @elseif(str_contains(strtolower($columna), 'hora') && str_contains(strtolower($columna), 'final'))
                        // Se establecerá al enviar el formulario
                    @elseif(str_contains(strtolower($columna), 'id'))
                        campo{{ $fieldId }}.value = contadorRegistros;
                    @elseif(str_contains(strtolower($columna), 'correo electrónico del tabulador') || str_contains(strtolower($columna), 'correo electronico del tabulador') || str_contains(strtolower($columna), 'email del tabulador'))
                        campo{{ $fieldId }}.value = loggedEmail;
                        // Forzar actualización visual
                        campo{{ $fieldId }}.setAttribute('value', loggedEmail);
                    @endif
                } else {
                    console.warn('Campo automático no encontrado: {{ $fieldId }}');
                }
            @endif
        @endforeach
    }

    /* =========================
       UTILIDADES DE ERRORES
    ========================== */
    function mostrarErrorCampo(id, mensaje) {
        const input = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);
        if (input && error) {
            input.classList.add('error');
            error.textContent = mensaje;
            error.classList.add('show');
        }
    }

    function limpiarErrorCampo(id) {
        const input = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);
        if (input && error) {
            input.classList.remove('error');
            error.textContent = '';
            error.classList.remove('show');
        }
    }

    function limpiarTodosErrores() {
        document.querySelectorAll('.field-error').forEach(e => {
            e.textContent = '';
            e.classList.remove('show');
        });
        document.querySelectorAll('.form-control').forEach(i => {
            i.classList.remove('error');
        });
    }

    /* =========================
       LÓGICA CHECKBOX OTRO
    ========================== */
    // Delegación de eventos para checkboxes dinámicos
    const formContainer = document.getElementById('caracterizacionForm');
    if (formContainer) {
        formContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('checkbox-dynamic')) {
                handleCheckboxChange(e.target);
            }
        });

        // Inicializar estado de checkboxes "Otro" pre-seleccionados
        // Esto es necesario por si el navegador recuerda la selección al recargar
        // o si volvemos de una validación fallida
        setTimeout(() => {
            const checkboxes = formContainer.querySelectorAll('.checkbox-dynamic:checked');
            checkboxes.forEach(checkbox => {
                const value = checkbox.value.toLowerCase();
                if (['otro', 'otros', 'otras', 'otra'].includes(value)) {
                    handleCheckboxChange(checkbox);
                }
            });
        }, 100);
    }

    function handleCheckboxChange(checkbox) {
        const fieldId = checkbox.getAttribute('data-target');
        const value = checkbox.value.toLowerCase();
        
        // Verificar si es una opción "Otro"
        if (['otro', 'otros', 'otras', 'otra'].includes(value)) {
            const inputOtro = document.getElementById(fieldId + '_otro');
            if (inputOtro) {
                if (checkbox.checked) {
                    inputOtro.style.display = 'block';
                    inputOtro.focus();
                } else {
                    inputOtro.style.display = 'none';
                    inputOtro.value = '';
                }
            }
        }
        
        // Actualizar el campo oculto con los valores seleccionados
        updateCheckboxHiddenField(fieldId);
    }

    function updateCheckboxHiddenField(fieldId) {
        // Encontrar el contenedor correcto
        // El checkbox está dentro de un div.form-check, que está dentro de .checkbox-group-container
        const hiddenField = document.getElementById(fieldId);
        if (!hiddenField) return;
        
        // Buscar los checkboxes asociados a este campo
        // Usamos el atributo data-target para asegurar que seleccionamos los correctos
        const checkboxes = document.querySelectorAll(`.checkbox-dynamic[data-target="${fieldId}"]:checked`);
        const values = Array.from(checkboxes).map(cb => cb.value);
        
        hiddenField.value = values.join(',');
        
        // Disparar evento change para que las reglas de salto (skip logic) se activen
        hiddenField.dispatchEvent(new Event('change', { bubbles: true }));
    }

    /* =========================
       RECOPILAR DATOS
    ========================== */
    function obtenerDatos() {
        const data = {};

        // Recopilar datos de todos los campos del formulario
        @foreach($columnasReferencia as $columna)
            @php
                $fieldId = strtolower($columna);
                // Primero eliminar caracteres especiales
                $fieldId = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $fieldId);
                // Reemplazar caracteres específicos por guion bajo
                $fieldId = str_replace(['#', '/'], '_', $fieldId);
                // Reemplazar CUALQUIER espacio en blanco (incluyendo espacios no rompibles) por guion bajo
                $fieldId = preg_replace('/\s+/u', '_', $fieldId);
                
                if (!preg_match('/^[a-zA-Z]/', $fieldId)) {
                    $fieldId = 'field_' . $fieldId;
                }
            @endphp
            const {{ $fieldId }} = document.getElementById('{{ $fieldId }}');
            let val_{{ $fieldId }} = {{ $fieldId }} ? {{ $fieldId }}.value.trim() : '';
            const {{ $fieldId }}_otro = document.getElementById('{{ $fieldId }}_otro');
            
            if ({{ $fieldId }}_otro && {{ $fieldId }}_otro.style.display !== 'none') {
                const otroVal = {{ $fieldId }}_otro.value.trim();
                
                if ({{ $fieldId }}.classList.contains('checkbox-result')) {
                     // Lógica para checkbox
                     // Dividir por coma y limpiar espacios de cada valor
                     let values = val_{{ $fieldId }}.split(',').map(v => v.trim()).filter(v => v);
                     // Remover 'Otro' (en todas sus variantes) de la lista
                     const variantesOtro = ['otro', 'otros', 'otras', 'otra'];
                     values = values.filter(v => !variantesOtro.includes(v.toLowerCase()));
                     // Agregar el valor especificado si existe
                     if (otroVal) {
                         values.push(otroVal);
                     }
                     val_{{ $fieldId }} = values.join(', '); // Usar espacio para mejor visualización en tabla
                } else {
                     // Lógica para select (reemplazo total)
                     val_{{ $fieldId }} = otroVal;
                }
            }
            data[{!! json_encode($columna) !!}] = val_{{ $fieldId }};
            const {{ $fieldId }}_file = document.getElementById('{{ $fieldId }}_file');
            if ({{ $fieldId }}_file && {{ $fieldId }}_file.files && {{ $fieldId }}_file.files[0]) {
                data[{!! json_encode($columna) !!}] = {{ $fieldId }}_file.files[0].name;
            }
        @endforeach

        return data;
    }

    /* =========================
       VALIDACIÓN
    ========================== */
    function validar() {
        limpiarTodosErrores();
        let valido = true;

        // Validar campos requeridos (cédula, documento, nombre)
        @foreach($columnasReferencia as $columna)
            @php
                $fieldId = strtolower($columna);
                // Primero eliminar caracteres especiales
                $fieldId = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $fieldId);
                // Reemplazar caracteres específicos por guion bajo
                $fieldId = str_replace(['#', '/'], '_', $fieldId);
                // Reemplazar CUALQUIER espacio en blanco (incluyendo espacios no rompibles) por guion bajo
                $fieldId = preg_replace('/\s+/u', '_', $fieldId);

                if (!preg_match('/^[a-zA-Z]/', $fieldId)) {
                    $fieldId = 'field_' . $fieldId;
                }
                // Excluir Hora Final de la validación ya que se llena al enviar
                $esHoraFinal = str_contains(strtolower($columna), 'hora') && str_contains(strtolower($columna), 'final');
                $esObservaciones = str_contains(strtolower($columna), 'observacion') || str_contains(strtolower($columna), 'observaciones');
                $esEvidencia = str_contains(strtolower($columna), 'evidencia fotografica') || str_contains(strtolower($columna), 'evidencia fotografica');

                
                // Campos automáticos que no deben bloquear el envío si están vacíos
                $esCorreoTabulador = str_contains(strtolower($columna), 'correo electrónico del tabulador') || str_contains(strtolower($columna), 'correo electronico del tabulador') || str_contains(strtolower($columna), 'email del tabulador');
                $esNumeroPredial = str_contains(strtolower($columna), 'numero predial') || str_contains(strtolower($columna), 'número predial');
            @endphp

           @if(!$esHoraFinal && !$esObservaciones && !$esCorreoTabulador && !$esNumeroPredial && !$esEvidencia)
    const {{ $fieldId }} = document.getElementById('{{ $fieldId }}');

    if ({{ $fieldId }}) {
        const fieldGroup = {{ $fieldId }}.closest('.form-group');

        // No validar campos ocultos
        if (fieldGroup && fieldGroup.style.display === 'none') {
            // Campo oculto → no se valida
        } else {
            let val = {{ $fieldId }}.value.trim();
            const {{ $fieldId }}_otro = document.getElementById('{{ $fieldId }}_otro');
            
            // Validar campo "Otro" si está activo
            if ({{ $fieldId }}_otro && {{ $fieldId }}_otro.style.display !== 'none') {
                val = {{ $fieldId }}_otro.value.trim();
                if (!val) {
                    mostrarErrorCampo('{{ $fieldId }}', 'Por favor especifique la opción "Otro"');
                    valido = false;
                }
            } else if (!val) {
                const {{ $fieldId }}_file = document.getElementById('{{ $fieldId }}_file');
                if ({{ $fieldId }}_file && {{ $fieldId }}_file.files && {{ $fieldId }}_file.files[0]) {
                    const txt = document.getElementById('{{ $fieldId }}');
                    if (txt) txt.value = {{ $fieldId }}_file.files[0].name;
                    limpiarErrorCampo('{{ $fieldId }}');
                } else {
                    mostrarErrorCampo('{{ $fieldId }}', 'Este campo es obligatorio');
                    valido = false;
                }
            }
        }
    }
           @endif

        @endforeach

        return valido;
    }

    /* =========================
       LIMPIAR FORMULARIO
    ========================== */
    function limpiarFormulario() {
        document.querySelectorAll('#registro-form input:not([readonly]), #registro-form select:not([disabled])')
            .forEach(c => c.value = '');
        // Limpiar checkboxes visualmente
        document.querySelectorAll('.checkbox-dynamic').forEach(c => c.checked = false);
        
        limpiarTodosErrores();
        // Reinicializar campos automáticos
        inicializarCamposAutomaticos();
    }

    /* =========================
       AGREGAR REGISTRO
    ========================== */
    function agregarRegistro() {
        if (!validar()) return;

        // Validar documento si existe
        const datos = obtenerDatos();
        const documento = encontrarDocumento(datos);

        if (documento) {
            validarDocumentoExistente(documento, function(documentoInfo) {
                if (documentoInfo.found_in_caracterizacion) {
                    // Mostrar mensaje de advertencia
                    mostrarMensajeAdvertencia(documentoInfo);
                } else {
                    // No hay duplicados, agregar
                    agregarRegistroConfirmado();
                }
            });
        } else {
            // No hay documento para validar, agregar directamente
            agregarRegistroConfirmado();
        }
    }

    /* =========================
       ENCONTRAR DOCUMENTO EN LOS DATOS
    ========================== */
    function encontrarDocumento(datos) {
        @foreach($columnasReferencia as $columna)
            @if(str_contains(strtolower($columna), 'cedula') || str_contains(strtolower($columna), 'cédula') ||
               str_contains(strtolower($columna), 'documento') || str_contains(strtolower($columna), 'dni'))
                if (datos['{{ $columna }}']) {
                    return datos['{{ $columna }}'];
                }
            @endif
        @endforeach
        return null;
    }

    /* =========================
       MOSTRAR MENSAJE DE ADVERTENCIA
    ========================== */
    function mostrarMensajeAdvertencia(documentoInfo) {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        const detallesDiv = document.getElementById('mensaje-advertencia-detalles');

        detallesDiv.textContent = `Documento encontrado en la caracterización existente.`;
        mensajeAdvertencia.style.display = 'block';

        // Hacer scroll al mensaje
        mensajeAdvertencia.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* =========================
       OCULTAR MENSAJE DE ADVERTENCIA
    ========================== */
    function ocultarMensajeAdvertencia() {
        const mensajeAdvertencia = document.getElementById('mensaje-advertencia');
        mensajeAdvertencia.style.display = 'none';
    }

    /* =========================
       AGREGAR REGISTRO CONFIRMADO
    ========================== */
    function agregarRegistroConfirmado() {
        // Ocultar mensaje de advertencia si está visible
        ocultarMensajeAdvertencia();

        // Proceder a agregar el registro
        registros.push(obtenerDatos());
        inputAcumulados.value = JSON.stringify(registros);
        actualizarLista();
        limpiarFormulario();
        contadorRegistros++; // Incrementar contador para el siguiente registro
        contador++;
        actualizarEstado();
    }

    /* =========================
       VALIDAR DOCUMENTO EXISTENTE
    ========================== */
    function validarDocumentoExistente(documento, callback) {
        fetch('{{ route("caracterizaciones.formulario.validar-cedula") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cedula: documento
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error al validar documento: ' + data.error);
                return;
            }
            callback(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al validar documento. Continuando...');
            callback({found_in_caracterizacion: false});
        });
    }

    /* =========================
       LISTA
    ========================== */
    function actualizarLista() {
        lista.innerHTML = '';
        registros.forEach((r, i) => {
            const div = document.createElement('div');
            div.className = 'registro-item';

            // Encontrar el nombre o identificador principal
            let nombrePrincipal = 'Registro sin nombre';
            @foreach($columnasReferencia as $columna)
                @if(str_contains(strtolower($columna), 'nombre'))
                    if (r[{!! json_encode($columna) !!}]) {
                        nombrePrincipal = r[{!! json_encode($columna) !!}];
                    }
                @endif
            @endforeach

            div.innerHTML = `
                <div>
                    <div class="registro-nombre">${nombrePrincipal}</div>
                    <div class="registro-detalles">Registro #${i + 1}</div>
                </div>
                <button class="btn-remove-item" onclick="eliminar(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            lista.appendChild(div);
        });
        contenedorAgregados.style.display = registros.length ? 'block' : 'none';
    }

    window.eliminar = function (i) {
        registros.splice(i, 1);
        inputAcumulados.value = JSON.stringify(registros);
        contador = registros.length + 1;
        actualizarLista();
        actualizarEstado();
    };

    function actualizarEstado() {
        total.textContent = registros.length;
        actual.textContent = contador;
    }

    /* =========================
       CAMPOS CONDICIONALES
    ========================== */
    function inicializarCamposCondicionales() {
        // Manejar reglas de rango (skip to field)
        const ranges = window.conditionalRangeRules || [];
        console.log('DEBUG: Range rules loaded:', ranges);

            ranges.forEach(range => {
                const baseTriggerId = generateFieldId(range.trigger_field);
                const skipToFieldId = generateFieldId(range.skip_to_field);
                const triggerValue = range.trigger_value;

                // Buscar todos los elementos que coincidan con el patrón (nombre base + números opcionales)
                const allInputs = document.querySelectorAll('#registro-form select, #registro-form input');
                const matchingElements = Array.from(allInputs).filter(el => {
                    if (!el.id) return false;
                    // Coincidencia exacta
                    if (el.id === baseTriggerId) return true;
                    
                    // IMPORTANTE: No coincidir con sub-checkboxes (ej: campo_0, campo_1)
                    // porque ellos no contienen el valor agregado y confunden la lógica.
                    // Solo permitimos sufijos si NO existe el ID base (raro) o si el elemento 
                    // es el campo principal de datos.
                    return false;
                });

                console.log(`DEBUG: Processing rule for ${range.trigger_field} (Base ID: ${baseTriggerId}). Found ${matchingElements.length} matches.`);

                matchingElements.forEach(triggerElement => {
                    const triggerFieldId = triggerElement.id;

                    const checkRangeCondition = function () {
                        const currentValue = triggerElement.value;
                        const allFormGroups = Array.from(document.querySelectorAll('#registro-form .form-group'));
                        
                        // Para checkboxes: verificar si el valor gatillo está presente
                        let shouldHide = false;
                        const tVal = String(triggerValue).toLowerCase();
                        const cVal = String(currentValue).toLowerCase();

                        if (triggerElement.classList.contains('checkbox-result')) {
                            // Es un campo checkbox, verificar si el valor está en los seleccionados
                            const values = cVal.split(',').map(v => v.trim());
                            shouldHide = values.includes(tVal);
                        } else {
                            // Es un campo select u otro, usar comparación directa
                            shouldHide = (cVal === tVal);
                        }

                        console.log(`DEBUG: Checking condition for ${triggerFieldId} (value: "${currentValue}") with triggerValue: "${triggerValue}". shouldHide: ${shouldHide}`);

                        let triggerIndex = -1;
                        let targetIndex = -1;

                        // 1. Encontrar los índices del campo de inicio y fin
                        allFormGroups.forEach((group, index) => {
                            const groupFieldId = group.querySelector('input, select')?.id;
                            if (groupFieldId === triggerFieldId) triggerIndex = index;
                            if (groupFieldId === skipToFieldId) targetIndex = index;
                        });

                        console.log(`DEBUG: triggerIndex: ${triggerIndex}, targetIndex: ${targetIndex}`);

                        // Salir si los campos no se encuentran o están en el orden incorrecto
                        if (triggerIndex === -1 || targetIndex === -1 || triggerIndex >= targetIndex) {
                            console.log('DEBUG: Fields not found or wrong order, returning');
                            return;
                        }

                        // 2. Ocultar o mostrar los campos que están en el rango
                        allFormGroups.forEach((group, index) => {
                            if (index > triggerIndex && index < targetIndex) {
                                if (shouldHide) {
                                    // Ocultar y limpiar el campo
                                    group.style.display = 'none';
                                    const inputs = group.querySelectorAll('input, select, textarea');
                                    inputs.forEach(input => {
                                        input.value = '';
                                        const errorId = `error-${input.id}`;
                                        const errorElement = document.getElementById(errorId);
                                        if (errorElement) {
                                            errorElement.textContent = '';
                                            errorElement.classList.remove('show');
                                            input.classList.remove('error');
                                        }
                                    });
                                } else {
                                    // Mostrar el campo (otra regla podría ocultarlo después si es necesario)
                                    group.style.display = 'block';
                                }
                            }
                        });
                    };

                    // Verificar condición inicial
                    checkRangeCondition();

                    // Escuchar cambios
                    triggerElement.addEventListener('change', function () {
                        console.log('DEBUG: Trigger field changed', triggerFieldId);
                        checkRangeCondition();
                    });
                    triggerElement.addEventListener('input', function() {
                        checkRangeCondition();
                    });
                });
            });
        } 

        // Encontrar todos los campos condicionales individuales
        const conditionalFields = document.querySelectorAll('[data-conditional="true"]');

        conditionalFields.forEach(field => {
            const triggerFieldId = field.getAttribute('data-trigger-field');
            const triggerValue = field.getAttribute('data-trigger-value');
            const triggerElement = document.getElementById(triggerFieldId);

            if (triggerElement) {
                // Función para verificar y mostrar/ocultar
                const checkCondition = function() {
                    const currentValue = triggerElement.value;
                    const tVal = String(triggerValue).toLowerCase();
                    const cVal = String(currentValue).toLowerCase();
                    
                    let isMatch = false;
                    if (triggerElement.classList.contains('checkbox-result')) {
                        const values = cVal.split(',').map(v => v.trim());
                        isMatch = values.includes(tVal);
                    } else {
                        isMatch = (cVal === tVal);
                    }

                    if (isMatch) {
                        field.style.display = 'block';
                    } else {
                        field.style.display = 'none';
                        // Limpiar el campo cuando se oculta
                        const inputs = field.querySelectorAll('input, select, textarea');
                        inputs.forEach(input => {
                            input.value = '';
                            // Limpiar errores también
                            const errorId = `error-${input.id}`;
                            const errorElement = document.getElementById(errorId);
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.classList.remove('show');
                                input.classList.remove('error');
                            }
                        });
                    }
                };

                // Verificar condición inicial
                checkCondition();

                // Escuchar cambios en el campo trigger
                triggerElement.addEventListener('change', checkCondition);
                triggerElement.addEventListener('input', checkCondition);
            }
        });
    

    // Función auxiliar para reemplazar strings
    function strReplace(str, search, replace) {
        search.forEach((s, i) => {
            str = str.split(s).join(replace[i]);
        });
        return str;
    }

    // Función para generar IDs válidos para JavaScript (igual que en PHP)
    function generateFieldId(columna) {
        let id = columna.toLowerCase();
        
        // Eliminar caracteres especiales (punctuation)
        id = id.replace(/[()¿?¡!,.;:"']/g, '');
        
        // Reemplazar chars específicos
        id = id.replace(/[#/]/g, '_');
        
        // Reemplazar cualquier espacio en blanco con _
        id = id.replace(/\s+/g, '_');
        
        // Asegurar que empiece con letra (prefijo si es necesario)
        if (!/^[a-zA-Z]/.test(id)) {
            id = 'field_' + id;
        }
        return id;
    }

    /* =========================
       EVENTOS
    ========================== */

    // Inicializar campos automáticos al cargar
    inicializarCamposAutomaticos();

    // Inicializar campos condicionales
    inicializarCamposCondicionales();

    const documentFieldIds = [];
    @foreach($columnasReferencia as $columna)
        @php
            $fieldId = strtolower($columna);
            $fieldId = str_replace(['(', ')', '¿', '?', '¡', '!', ',', '.', ';', ':', '"', "'"], '', $fieldId);
            $fieldId = str_replace(['#', '/'], '_', $fieldId);
            $fieldId = preg_replace('/\s+/u', '_', $fieldId);
            if (!preg_match('/^[a-zA-Z]/', $fieldId)) {
                $fieldId = 'field_' . $fieldId;
            }
            $normalized = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $columna));
            $isDoc = trim($normalized) === 'numero de documento de identidad del encuestado';
        @endphp
        @if($isDoc)
            documentFieldIds.push('{{ $fieldId }}');
        @endif
    @endforeach

    function setupDocDuplicateCheck(id) {
        const el = document.getElementById(id);
        const msgEl = document.getElementById('duplicate-' + id);
        if (!el || !msgEl) return;
        let debounceTimer = null;
        const check = () => {
            const val = el.value.trim();
            if (!val) {
                msgEl.textContent = '';
                msgEl.classList.remove('show');
                return;
            }
            if (debounceTimer) clearTimeout(debounceTimer);
            msgEl.textContent = 'Verificando...';
            msgEl.style.color = '#6c757d';
            msgEl.classList.add('show');
            debounceTimer = setTimeout(() => {
                fetch('{{ route("caracterizaciones.formulario.validar-cedula") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ cedula: val })
                })
                .then(r => {
                    if (!r.ok) throw new Error('Estado ' + r.status);
                    return r.json();
                })
                .then(d => {
                    if (d && d.found_in_caracterizacion) {
                        msgEl.textContent = 'Esta persona ya se encuentra caracterizada';
                        msgEl.style.color = '#dc3545';
                        msgEl.classList.add('show');
                    } else {
                        msgEl.textContent = '';
                        msgEl.classList.remove('show');
                    }
                })
                .catch(() => {
                    msgEl.textContent = 'Error al verificar';
                    msgEl.style.color = '#dc3545';
                    msgEl.classList.add('show');
                });
            }, 300);
        };
        el.addEventListener('input', check);
        el.addEventListener('change', check);
    }

    documentFieldIds.forEach(setupDocDuplicateCheck);

    if (btnAgregar) btnAgregar.addEventListener('click', agregarRegistro);
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFormulario);

    // Event listeners para los botones del mensaje de advertencia
    const btnAceptar = document.getElementById('btn-aceptar');

    if (btnAceptar) {
        btnAceptar.addEventListener('click', function() {
            ocultarMensajeAdvertencia();
            limpiarFormulario();
        });
    }

    form.addEventListener('submit', function (e) {
        if (!validar()) {
            e.preventDefault();
            return;
        }
        const datos = obtenerDatos();
        horaFinFormulario = new Date().toISOString().slice(0, 16);
        @foreach($columnasReferencia as $columna)
            @if(str_contains(strtolower($columna), 'hora') && str_contains(strtolower($columna), 'final'))
                datos[{!! json_encode($columna) !!}] = horaFinFormulario;
            @endif
        @endforeach
        registros = [datos];
        inputAcumulados.value = JSON.stringify(registros);
    });

});
</script>

</x-app-layout>
