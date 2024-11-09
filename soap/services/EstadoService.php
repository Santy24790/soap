<?php
// services/EstadoService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php';  // Configuración de la conexión a la base de datos
require_once __DIR__ . '/../controllers/EstadoController.php';  // Controlador de Estado

// Configuración del servicio SOAP
$namespace = "Estados";
$server = new nusoap_server();
$server->configureWSDL('ServicioEstados', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (modelo de datos) para el estado
$server->wsdl->addComplexType(
    'Estado',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'idestado' => array('name' => 'idestado', 'type' => 'xsd:int'),
        'estado' => array('name' => 'estado', 'type' => 'xsd:string')
    )
);

// Registrar métodos SOAP
$server->register(
    'VerEstados',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los estados'
);

$server->register(
    'VerEstado',
    array('id' => 'xsd:int'),
    array('return' => 'tns:Estado'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver detalles de un estado por su ID'
);

$server->register(
    'CrearEstado',
    array('data' => 'tns:Estado'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un nuevo estado'
);

$server->register(
    'ActualizarEstado',
    array('data' => 'tns:Estado', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un estado existente'
);

$server->register(
    'EliminarEstado',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un estado por su ID'
);

// Funciones SOAP
function VerEstados() {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->VerEstados();
}

function VerEstado($id) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->VerEstado($id);
}

function CrearEstado($data) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->createEstado($data);
}

function ActualizarEstado($data, $id) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->updateEstado($data, $id);
}

function EliminarEstado($id) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->deleteEstado($id);
}

// Procesar la solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
