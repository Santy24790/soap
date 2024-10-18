<?php
// services/EstadoService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php';  // Configuración de la conexión a la base de datos
require_once __DIR__ . '/../controllers/EstadoController.php';  // Controlador de Estado
require_once __DIR__ . '/../models/estado.php';  // Modelo de datos de estado
require_once __DIR__ . '/../controllers/EstadoController.php'; 

// Configuración del servicio SOAP
$namespace = "Estados";
$server = new Soap_Server();
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

// Registrar método SOAP para ver todos los estados
$server->register(
    'VerEstados',
    array(),  // Sin parámetros de entrada
    array('return' => 'xsd:Array'),  // Retorna un array de estados
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los estados'
);

// Registrar método SOAP para ver el detalle de un estado por ID
$server->register(
    'VerEstado',
    array('id' => 'xsd:int'),  // Parámetro de entrada
    array('return' => 'tns:Estado'),  // Retorna un estado
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver detalles de un estado por su ID'
);

// Registrar método SOAP para crear un estado
$server->register(
    'CrearEstado',
    array('data' => 'tns:Estado'),  // Recibe un objeto de tipo Estado
    array('return' => 'xsd:string'),  // Retorna un mensaje
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un nuevo estado'
);

// Registrar método SOAP para actualizar un estado
$server->register(
    'ActualizarEstado',
    array('data' => 'tns:Estado', 'id' => 'xsd:int'),  // Recibe un objeto de tipo Estado y el ID del estado
    array('return' => 'xsd:string'),  // Retorna un mensaje
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un estado existente'
);

// Registrar método SOAP para eliminar un estado
$server->register(
    'EliminarEstado',
    array('id' => 'xsd:int'),  // El parámetro de entrada es el ID del estado
    array('return' => 'xsd:string'),  // Retorna un mensaje
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un estado por su ID'
);

// Función para ver todos los estados
function VerEstados() {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->getEstados();
}

// Función para ver el detalle de un estado
function VerEstado($id) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->getEstadoById($id);
}

// Función para crear un estado
function CrearEstado($data) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->createEstado($data);
}

// Función para actualizar un estado
function ActualizarEstado($data, $id) {
    $pdo = getConnection();
    $controller = new EstadoController($pdo);
    return $controller->updateEstado($data, $id);
}

// Función para eliminar un estado
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
