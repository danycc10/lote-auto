<?php

$autoForm = [
    'purpose' => 'Registra la información comercial, técnica y visual de una unidad para integrarla al inventario.',
    'steps' => [
        'Captura la marca, modelo, año y versión de la unidad.',
        'Completa identificadores, condición, kilometraje y precio.',
        'Agrega las fotografías y define si el auto se publicará en el catálogo.',
        'Revisa los datos antes de guardar para evitar duplicados.',
    ],
    'information_title' => 'Información a capturar',
    'information' => [
        'VIN, número de serie o placa tal como aparecen en los documentos.',
        'Precio, enganche sugerido, kilometraje y características principales.',
        'Estatus operativo y visibilidad pública del vehículo.',
        'Fotografías claras y representativas de la unidad.',
    ],
    'tip' => 'Verifica el VIN y el precio antes de guardar; ambos datos afectan contratos, apartados y publicaciones.',
];

$clienteForm = [
    'purpose' => 'Concentra los datos de identificación, contacto y perfil económico necesarios para atender y financiar al cliente.',
    'steps' => [
        'Captura el nombre completo y los medios de contacto.',
        'Registra CURP, RFC y domicilio cuando estén disponibles.',
        'Completa el perfil económico y adjunta los documentos privados.',
        'Confirma que la información corresponda a la persona antes de guardar.',
    ],
    'information_title' => 'Información a capturar',
    'information' => [
        'Nombre, apellidos, teléfono y correo electrónico.',
        'CURP, RFC, dirección, ciudad, estado y código postal.',
        'Ocupación e ingreso mensual para la evaluación.',
        'INE y comprobante de domicilio legibles, en los formatos permitidos.',
    ],
    'tip' => 'No captures datos de otra persona ni documentos vencidos; esta información se utiliza en expedientes y contratos.',
];

$contratoForm = [
    'purpose' => 'Define las condiciones de un financiamiento y genera el calendario de pagos del contrato.',
    'steps' => [
        'Selecciona el cliente y el auto que formarán parte del contrato.',
        'Captura enganche, plazo, tasa, fechas y demás condiciones financieras.',
        'Revisa el resumen y la tabla calculada antes de confirmar.',
        'Guarda únicamente cuando los importes coincidan con el acuerdo firmado.',
    ],
    'information_title' => 'Información a capturar',
    'information' => [
        'Cliente y auto correctos; una unidad no puede tener dos contratos vigentes.',
        'Precio, enganche, capital financiado, tasa y número de pagos.',
        'Periodicidad, fecha de inicio y día acordado para el cobro.',
        'Documento del contrato y observaciones relevantes, cuando correspondan.',
    ],
    'tip' => 'Cambiar plazo, tasa o enganche modifica las cuotas. Compara siempre el total a pagar antes de guardar.',
];

$reciboForm = [
    'purpose' => 'Registra o consulta el comprobante de una operación de cobro vinculada al financiamiento.',
    'steps' => [
        'Localiza el contrato o cliente relacionado con el movimiento.',
        'Revisa la cuota, saldo e importe que se aplicarán.',
        'Captura la forma de pago y la referencia del comprobante.',
        'Confirma los datos antes de guardar o imprimir.',
    ],
    'information_title' => 'Información a capturar',
    'information' => [
        'Contrato, cuota y cliente a los que corresponde el pago.',
        'Monto recibido, fecha y forma de pago.',
        'Referencia bancaria, folio o nota que permita rastrear el movimiento.',
        'Observaciones necesarias para aclaraciones posteriores.',
    ],
    'tip' => 'No registres el mismo comprobante dos veces. Verifica importe, referencia y contrato antes de confirmar.',
];

return [
    'default' => [
        'title' => 'Ayuda de esta pantalla',
        'purpose' => 'Consulta la información disponible y utiliza únicamente las acciones autorizadas para tu cuenta.',
        'steps' => [
            'Revisa el título y los indicadores de la pantalla.',
            'Usa los filtros o campos disponibles para localizar la información.',
            'Confirma los datos antes de ejecutar una acción.',
        ],
        'information_title' => 'Información importante',
        'information' => [
            'Los campos marcados como obligatorios deben completarse.',
            'Las acciones disponibles dependen de tus permisos.',
        ],
        'tip' => 'Si una opción no aparece, solicita al administrador que revise tus permisos.',
    ],

    'screens' => [
        'dashboard' => [
            'title' => 'Panel de cobranza',
            'purpose' => 'Resume la cartera, los vencimientos y las tareas prioritarias de cobranza.',
            'steps' => [
                'Revisa los indicadores generales y el monto pendiente.',
                'Usa los filtros para acotar contratos, fechas o estatus.',
                'Abre un contrato para consultar su estado de cuenta o registrar un pago.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Cuotas próximas, vencidas y montos recuperados.',
                'Contratos al corriente, atrasados o con atención pendiente.',
                'Accesos directos a pagos, clientes y detalle de financiamientos.',
            ],
            'tip' => 'Comienza por los vencimientos más antiguos y valida el último pago antes de contactar al cliente.',
        ],
        'admin.prospectos.index' => [
            'title' => 'Prospectos',
            'purpose' => 'Da seguimiento a las personas interesadas que llegan desde el sitio o son registradas por el equipo.',
            'steps' => [
                'Busca por nombre, teléfono o correo y filtra por estatus.',
                'Abre el prospecto para revisar el auto o mensaje de interés.',
                'Actualiza el estatus y registra el siguiente paso de seguimiento.',
            ],
            'information_title' => 'Información de seguimiento',
            'information' => [
                'Nombre, teléfono, correo y fecha de contacto.',
                'Unidad de interés, mensaje y origen del prospecto.',
                'Estatus comercial y notas de seguimiento.',
            ],
            'tip' => 'Evita cambiar un prospecto a atendido sin dejar una nota que indique el resultado del contacto.',
        ],
        'admin.cotizador' => [
            'title' => 'Cotizador',
            'purpose' => 'Simula un plan de financiamiento antes de crear un contrato formal.',
            'steps' => [
                'Selecciona o captura el precio de la unidad.',
                'Define enganche, plazo, tasa y periodicidad.',
                'Revisa cuota, intereses, total a pagar y tabla de amortización.',
                'Genera el PDF cuando la propuesta esté lista para compartir.',
            ],
            'information_title' => 'Datos de la cotización',
            'information' => [
                'Precio de venta y enganche disponible.',
                'Plazo, tasa de interés y frecuencia de pago.',
                'Datos del prospecto o cliente para identificar la propuesta.',
            ],
            'tip' => 'Una cotización es informativa; confirma condiciones y disponibilidad antes de convertirla en contrato.',
        ],
        'admin.administracion.index' => [
            'title' => 'Administración',
            'purpose' => 'Agrupa las herramientas administrativas disponibles para la operación del lote.',
            'steps' => [
                'Identifica la tarjeta del proceso que necesitas administrar.',
                'Abre la sección y revisa sus permisos y alcance.',
                'Regresa a este concentrador para cambiar de herramienta.',
            ],
            'information_title' => 'Qué encontrarás',
            'information' => [
                'Accesos a catálogos y configuraciones operativas.',
                'Herramientas visibles según los permisos de tu usuario.',
            ],
            'tip' => 'Si una herramienta no aparece, puede estar desactivada o requerir un permiso adicional.',
        ],
        'admin.administracion.tarjetas-cobro' => [
            'title' => 'Tarjetas de cobro',
            'purpose' => 'Configura los medios o referencias que se muestran al personal para recibir pagos.',
            'steps' => [
                'Revisa las tarjetas registradas y su estado.',
                'Captura los datos de identificación del medio de cobro.',
                'Activa únicamente las opciones vigentes y guarda los cambios.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre del banco, titular o etiqueta de identificación.',
                'Número enmascarado, cuenta o referencia autorizada.',
                'Orden de visualización y estado activo.',
            ],
            'tip' => 'No publiques claves, NIP, CVV ni contraseñas; solo datos seguros para identificar el medio de pago.',
        ],
        'admin.sistema.index' => [
            'title' => 'Sistema',
            'purpose' => 'Centraliza seguridad, apariencia, configuración, auditoría y herramientas avanzadas.',
            'steps' => [
                'Selecciona la categoría que deseas administrar.',
                'Lee la descripción de cada tarjeta antes de ingresar.',
                'Realiza cambios solo si conoces su impacto operativo.',
            ],
            'information_title' => 'Secciones disponibles',
            'information' => [
                'Configuración general, apariencia y plantillas públicas.',
                'Usuarios, roles, auditoría y registros financieros.',
            ],
            'tip' => 'Los cambios de sistema pueden afectar a todos los usuarios; valida su alcance antes de guardar.',
        ],
        'admin.sistema.configuracion' => [
            'title' => 'Configuración del sistema',
            'purpose' => 'Controla módulos, datos públicos de contacto y plantillas de notificación.',
            'steps' => [
                'Revisa qué módulos están activos antes de modificarlos.',
                'Actualiza WhatsApp, redes sociales y mapa con datos públicos válidos.',
                'Edita las plantillas respetando las variables disponibles.',
                'Guarda y verifica el resultado en el sitio o flujo correspondiente.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Número de WhatsApp con código de país y enlaces completos de redes.',
                'URL de inserción de Google Maps, no el enlace corto de navegación.',
                'Asunto y cuerpo de correo y mensaje de WhatsApp para mora.',
                'Variables entre llaves sin modificar su nombre.',
            ],
            'tip' => 'Desactivar un módulo puede ocultar menús y procesos relacionados; hazlo fuera del horario operativo.',
        ],
        'admin.sistema.apariencia' => [
            'title' => 'Apariencia y contenido público',
            'purpose' => 'Personaliza la identidad, textos, contacto, SEO y secciones del sitio público.',
            'steps' => [
                'Carga logotipo e imágenes con buena resolución y peso adecuado.',
                'Edita textos y colores mientras revisas la vista previa.',
                'Completa contacto, mensajes de WhatsApp y metadatos SEO.',
                'Guarda y valida el sitio público en móvil y escritorio.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre comercial, logotipo, colores e imágenes institucionales.',
                'Títulos, descripciones y llamadas a la acción del sitio.',
                'Horario, dirección y mensajes predeterminados de contacto.',
                'Título SEO, descripción y códigos de analítica válidos.',
            ],
            'tip' => 'Usa imágenes optimizadas y textos breves; los cambios se reflejan en la experiencia pública de los clientes.',
        ],
        'admin.sistema.landing' => [
            'title' => 'Plantillas del sitio',
            'purpose' => 'Permite elegir el diseño general que utilizará la página pública del lote.',
            'steps' => [
                'Revisa la vista previa y descripción de cada plantilla.',
                'Selecciona la que mejor represente al negocio.',
                'Confirma el cambio y visita el sitio público para validarlo.',
            ],
            'information_title' => 'Qué debes considerar',
            'information' => [
                'La plantilla cambia la presentación, no los datos del inventario.',
                'Los colores, imágenes y textos provienen de Apariencia.',
                'La legibilidad debe comprobarse en móvil y escritorio.',
            ],
            'tip' => 'Configura primero la apariencia y el contenido; después elige la plantilla con información real.',
        ],
        'admin.sistema.auditoria' => [
            'title' => 'Auditoría',
            'purpose' => 'Consulta quién realizó cambios relevantes, cuándo ocurrieron y sobre qué registro.',
            'steps' => [
                'Filtra por usuario, acción, módulo o rango de fechas.',
                'Abre un evento para revisar valores anteriores y nuevos.',
                'Usa la información para seguimiento, no para modificar registros.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Usuario, fecha, dirección IP y tipo de acción.',
                'Entidad afectada y diferencias registradas.',
                'Filtros para investigar un evento específico.',
            ],
            'tip' => 'La auditoría registra evidencia histórica; evita compartirla con usuarios no autorizados.',
        ],
        'admin.seguridad.roles-permisos' => [
            'title' => 'Roles y permisos',
            'purpose' => 'Define qué módulos y acciones puede utilizar cada rol del sistema.',
            'steps' => [
                'Selecciona un rol y revisa todos sus permisos actuales.',
                'Activa solo las capacidades necesarias para su función.',
                'Guarda y prueba el acceso con un usuario representativo.',
            ],
            'information_title' => 'Información de seguridad',
            'information' => [
                'Permisos de consulta, creación, edición, cancelación y configuración.',
                'Roles asignables sin elevar privilegios innecesariamente.',
                'Restricciones especiales para administración y seguridad.',
            ],
            'tip' => 'Aplica el principio de mínimo privilegio y evita otorgar permisos administrativos por comodidad.',
        ],
        'admin.seguridad.usuarios' => [
            'title' => 'Usuarios',
            'purpose' => 'Administra las cuentas internas y los roles con los que acceden al sistema.',
            'steps' => [
                'Busca una cuenta existente o abre el formulario de alta.',
                'Captura nombre y correo corporativo de la persona.',
                'Asigna únicamente los roles necesarios y define su estado.',
                'Comunica las credenciales por un canal seguro.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre identificable y correo electrónico único.',
                'Contraseña inicial segura cuando el flujo la solicite.',
                'Roles acordes con las responsabilidades del usuario.',
                'Estado activo o inactivo de la cuenta.',
            ],
            'tip' => 'Nunca compartas cuentas. Cada persona debe usar su propio usuario para conservar la trazabilidad.',
        ],
        'admin.autos.index' => [
            'title' => 'Inventario de autos',
            'purpose' => 'Consulta y administra las unidades registradas, su disponibilidad y publicación.',
            'steps' => [
                'Usa la búsqueda y los filtros para localizar una unidad.',
                'Revisa estatus, precio y datos principales en el listado.',
                'Abre la edición o cambia la publicación si tienes permiso.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Marca, modelo, año, VIN, placa y kilometraje.',
                'Precio, estatus operativo y visibilidad pública.',
                'Acciones disponibles según tu rol.',
            ],
            'tip' => 'Antes de crear una unidad, busca por VIN o placa para evitar registros duplicados.',
        ],
        'admin.autos.create' => ['title' => 'Registrar auto', ...$autoForm],
        'admin.autos.edit' => ['title' => 'Editar auto', ...$autoForm],
        'admin.catalogos.marcas-modelos' => [
            'title' => 'Marcas y modelos',
            'purpose' => 'Mantiene los catálogos utilizados al registrar unidades y filtrar el inventario.',
            'steps' => [
                'Selecciona o crea primero la marca.',
                'Agrega los modelos correspondientes a la marca seleccionada.',
                'Edita o desactiva elementos solo después de revisar su uso.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre oficial y sin abreviaturas innecesarias de la marca.',
                'Nombre comercial del modelo asociado a la marca correcta.',
                'Estado activo de cada opción del catálogo.',
            ],
            'tip' => 'Busca variantes ortográficas antes de crear una marca o modelo para evitar duplicados.',
        ],
        'admin.clientes.index' => [
            'title' => 'Clientes',
            'purpose' => 'Consulta expedientes y localiza a las personas vinculadas con apartados o financiamientos.',
            'steps' => [
                'Busca por nombre, teléfono, correo, CURP o RFC.',
                'Revisa el estado y los datos principales del cliente.',
                'Abre el expediente o la edición según la acción necesaria.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Identificación y medios de contacto.',
                'Estado del expediente y documentos disponibles.',
                'Relación con apartados y contratos.',
            ],
            'tip' => 'Busca por CURP, RFC o teléfono antes de registrar un cliente nuevo.',
        ],
        'admin.clientes.create' => ['title' => 'Registrar cliente', ...$clienteForm],
        'admin.clientes.edit' => ['title' => 'Editar cliente', ...$clienteForm],
        'admin.apartados-autos.index' => [
            'title' => 'Apartados de autos',
            'purpose' => 'Controla las unidades reservadas temporalmente, sus anticipos y vencimientos.',
            'steps' => [
                'Busca por folio, cliente, VIN o placa y filtra por estatus.',
                'Revisa fecha de vencimiento, anticipo y unidad apartada.',
                'Convierte, cancela o consulta el apartado según corresponda.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Folio, cliente, auto y periodo de reserva.',
                'Monto de anticipo, forma de pago y referencia.',
                'Estatus y motivo de cancelación cuando exista.',
            ],
            'tip' => 'Al cancelar, captura un motivo claro; esta acción puede liberar nuevamente la unidad.',
        ],
        'admin.apartados-autos.create' => [
            'title' => 'Nuevo apartado',
            'purpose' => 'Reserva una unidad disponible para un cliente durante un periodo definido.',
            'steps' => [
                'Busca y selecciona un auto disponible.',
                'Selecciona un cliente o captura sus datos temporales.',
                'Define fechas, anticipo, forma de pago y referencia.',
                'Revisa el resumen y confirma el apartado.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Auto y cliente relacionados con la reserva.',
                'Fecha de apartado y fecha límite de vencimiento.',
                'Monto de anticipo, forma de pago y referencia.',
                'Observaciones o condiciones acordadas.',
            ],
            'tip' => 'Confirma que la unidad esté disponible y que la fecha de vencimiento haya sido acordada con el cliente.',
        ],
        'admin.contratos-financiamiento.index' => [
            'title' => 'Contratos de financiamiento',
            'purpose' => 'Consulta la cartera de contratos, saldos, estatus y próximas acciones.',
            'steps' => [
                'Busca por folio, cliente o unidad y aplica los filtros.',
                'Revisa estatus, saldo y fechas principales.',
                'Abre el contrato para consultar, editar o registrar pagos.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Folio, cliente, auto y condiciones financieras.',
                'Capital, saldo, cuotas y estatus de cobranza.',
                'Accesos al estado de cuenta y registro de pagos.',
            ],
            'tip' => 'Antes de editar, verifica si el contrato ya tiene pagos; algunas condiciones dejan de ser modificables.',
        ],
        'admin.contratos-financiamiento.create' => ['title' => 'Crear contrato de financiamiento', ...$contratoForm],
        'admin.contratos-financiamiento.edit' => ['title' => 'Editar contrato de financiamiento', ...$contratoForm],
        'admin.contratos-financiamiento.show' => [
            'title' => 'Detalle del contrato',
            'purpose' => 'Muestra las condiciones, documentos, pagos y situación actual de un financiamiento.',
            'steps' => [
                'Confirma folio, cliente y unidad del contrato.',
                'Revisa saldo, calendario, pagos y estatus.',
                'Usa las acciones disponibles para imprimir, editar o registrar un pago.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Condiciones financieras y calendario de cuotas.',
                'Historial de pagos, recargos y saldo pendiente.',
                'Documentos y datos relacionados con cliente y auto.',
            ],
            'tip' => 'Usa el historial real del contrato para aclaraciones; no calcules el saldo de forma manual.',
        ],
        'admin.contratos-financiamiento.registrar-pago' => [
            'title' => 'Registrar pago',
            'purpose' => 'Aplica un ingreso a una cuota del contrato y genera su comprobante.',
            'steps' => [
                'Confirma contrato, cliente, cuota y saldo pendiente.',
                'Captura monto, fecha, forma de pago y referencia.',
                'Revisa cómo se distribuirá el pago y cualquier recargo.',
                'Confirma una sola vez y conserva el recibo generado.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Cuota a pagar y monto efectivamente recibido.',
                'Fecha y forma de pago utilizadas.',
                'Referencia bancaria o folio del comprobante.',
                'Observaciones necesarias para identificar la operación.',
            ],
            'tip' => 'No continúes si el contrato, la cuota o el importe no coinciden con el comprobante entregado.',
        ],
        'admin.recibos.index' => [
            'title' => 'Recibos',
            'purpose' => 'Localiza comprobantes de pagos y revisa su estado dentro del historial financiero.',
            'steps' => [
                'Busca por folio, contrato, cliente o referencia.',
                'Filtra por fecha o estatus cuando sea necesario.',
                'Abre el recibo para consultar, imprimir o cancelar.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Folio, fecha, cliente, contrato y monto.',
                'Forma de pago, referencia y usuario que registró.',
                'Estado vigente o cancelado del comprobante.',
            ],
            'tip' => 'Una cancelación afecta saldos y cuotas; revisa el contrato antes de iniciar ese proceso.',
        ],
        'admin.recibos.create' => ['title' => 'Registrar recibo', ...$reciboForm],
        'admin.recibos.edit' => ['title' => 'Administrar recibo', ...$reciboForm],
        'admin.recibos.show' => [
            'title' => 'Detalle del recibo',
            'purpose' => 'Presenta el comprobante completo y la operación financiera que representa.',
            'steps' => [
                'Confirma folio, contrato, cliente y monto.',
                'Revisa forma de pago, referencia y fecha.',
                'Imprime o descarga únicamente si los datos son correctos.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Desglose del pago aplicado y cuota relacionada.',
                'Datos de emisión, referencia y usuario responsable.',
                'Estado actual y motivo de cancelación, si aplica.',
            ],
            'tip' => 'El recibo debe coincidir con el historial del contrato y el comprobante recibido.',
        ],
        'admin.finanzas.logs-financieros' => [
            'title' => 'Registro financiero',
            'purpose' => 'Consulta la trazabilidad de operaciones que modificaron saldos, cuotas o contratos.',
            'steps' => [
                'Filtra por contrato, usuario, tipo de evento o fecha.',
                'Abre el movimiento para revisar su contexto y valores.',
                'Compara la secuencia de eventos cuando investigues una diferencia.',
            ],
            'information_title' => 'Qué puedes consultar',
            'information' => [
                'Operación, fecha, usuario y contrato relacionado.',
                'Importes y valores anteriores y posteriores.',
                'Referencia que vincula pagos, cancelaciones o ajustes.',
            ],
            'tip' => 'Este registro es de consulta; usa folios y fechas para documentar cualquier aclaración.',
        ],
        'admin.reportes.index' => [
            'title' => 'Reportes',
            'purpose' => 'Analiza resultados operativos y financieros dentro de un periodo seleccionado.',
            'steps' => [
                'Selecciona el tipo de reporte y el rango de fechas.',
                'Aplica los filtros necesarios y revisa los indicadores.',
                'Valida el resultado antes de exportarlo.',
            ],
            'information_title' => 'Datos para el reporte',
            'information' => [
                'Periodo, estatus y criterios de agrupación.',
                'Totales, conteos y tendencias mostradas.',
                'Archivo exportado con los filtros aplicados.',
            ],
            'tip' => 'Compara reportes usando el mismo rango y filtros para evitar conclusiones incorrectas.',
        ],
        'profile.show' => [
            'title' => 'Mi perfil y seguridad',
            'purpose' => 'Actualiza tus datos personales, contraseña y opciones de protección de la cuenta.',
            'steps' => [
                'Revisa nombre, correo y fotografía de perfil.',
                'Actualiza la contraseña usando tu contraseña actual.',
                'Configura la verificación en dos pasos y guarda los códigos de recuperación.',
                'Cierra otras sesiones si detectas un acceso desconocido.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre y correo electrónico que identifican tu cuenta.',
                'Contraseña actual y una contraseña nueva segura.',
                'Código de autenticación al habilitar la protección en dos pasos.',
            ],
            'tip' => 'No compartas tu contraseña ni tus códigos de recuperación con ninguna persona.',
        ],
        'api-tokens.index' => [
            'title' => 'Tokens de API',
            'purpose' => 'Crea credenciales técnicas para que aplicaciones autorizadas accedan a funciones específicas.',
            'steps' => [
                'Asigna un nombre que identifique claramente la integración.',
                'Selecciona solo los permisos estrictamente necesarios.',
                'Copia el token cuando se muestre y guárdalo en un gestor seguro.',
                'Revoca los tokens que ya no se utilicen.',
            ],
            'information_title' => 'Información a capturar',
            'information' => [
                'Nombre de la aplicación o integración responsable.',
                'Permisos mínimos requeridos para su operación.',
                'Fecha o propósito que facilite su posterior revisión.',
            ],
            'tip' => 'El token se muestra completo una sola vez. Nunca lo envíes por correo o mensajería sin protección.',
        ],
    ],
];
