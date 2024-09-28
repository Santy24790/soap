<?php
// services/ProveedorService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php';
require_once __DIR__ . '/../controllers/ProveedoresController.php';

// Configuración del servicio SOAP
$namespace = "Proveedores";
$server = new Soap_Server();
$server->configureWSDL('ServicioProveedores', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el proveedor
$server->wsdl->addComplexType(
    'Proveedor',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'idproveedor' => array('name' => 'idproveedor', 'type' => 'xsd:int'),
        'marca' => array('name' => 'marca', 'type' => 'xsd:string')
    )
);

// Funciones CRUD para la tabla Proveedor

// Función para ver todos los proveedores
function VerProveedores() {
    $pdo = getConnection();
    $controller = new ProveedorController($pdo);
    return $controller->getAllProveedores();
}

// Función para ver un proveedor específico por ID
function VerProveedor($id) {
    $pdo = getConnection();
    $controller = new ProveedorController($pdo);
    return $controller->getProveedorDetail($id);
}

// Función para crear un nuevo proveedor
function CrearProveedor($data) {
    $pdo = getConnection();
    $controller = new ProveedorController($pdo);
    return $controller->createProveedor($data);
}

// Función para actualizar un proveedor existente
function ActualizarProveedor($data, $id) {
    $pdo = getConnection();
    $controller = new ProveedorController($pdo);
    return $controller->updateProveedor($data, $id);
}

// Función para eliminar un proveedor por ID
function EliminarProveedor($id) {
    $pdo = getConnection();
    $controller = new ProveedorController($pdo);
    return $controller->deleteProveedor($id);
}

// Registrar los métodos SOAP
$server->register(
    'VerProveedores',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los proveedores'
);

$server->register(
    'VerProveedor',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver un proveedor por ID'
);

$server->register(
    'CrearProveedor',
    array('data' => 'tns:Proveedor'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un nuevo proveedor'
);

$server->register(
    'ActualizarProveedor',
    array('data' => 'tns:Proveedor', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un proveedor'
);

$server->register(
    'EliminarProveedor',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un proveedor'
);

// Procesar la solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
