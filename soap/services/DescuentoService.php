<?php
// services/DescuentoService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php';
require_once __DIR__ . '/../controllers/DescuentoController.php';

// Configuración del servicio SOAP
$namespace = "Descuentos";
$server = new Soap_Server();
$server->configureWSDL('ServicioDescuentos', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo para descuento
$server->wsdl->addComplexType(
    'Descuento',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'descuento' => array('name' => 'descuento', 'type' => 'xsd:string'),
    )
);

// Registrar método SOAP para ver todos los descuentos
$server->register(
    'VerDescuentos',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los descuentos'
);

// Registrar método SOAP para ver detalle de un descuento
$server->register(
    "VerDescuento",
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    'urn:descuentos',
    'urn:descuentos#VerDescuento',
    'rpc',
    'encoded',
    'Obtiene los detalles de un descuento por su ID'
);

// Registrar método SOAP para crear un descuento
$server->register(
    'CrearDescuento',
    array('data' => 'tns:Descuento'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un descuento'
);

// Registrar método SOAP para actualizar un descuento
$server->register(
    'ActualizarDescuento',
    array('data' => 'tns:Descuento', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un descuento'
);

// Registrar método SOAP para eliminar un descuento
$server->register(
    'EliminarDescuento',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un descuento'
);

// Función que llama al controlador para ver todos los descuentos
function VerDescuentos()
{
    $pdo = getConnection();
    $controller = new DescuentoController($pdo);
    return $controller->getAllDescuentos();
}

// Función que llama al controlador para ver el detalle de un descuento
function VerDescuento($id)
{
    $pdo = getConnection();
    $controller = new DescuentoController($pdo);
    return $controller->getDescuentoDetail($id);
}

// Función que llama al controlador para crear un descuento
function CrearDescuento($data)
{
    $pdo = getConnection();
    $controller = new DescuentoController($pdo);
    return $controller->createDescuento($data);
}

// Función que llama al controlador para actualizar un descuento
function ActualizarDescuento($data, $id)
{
    $pdo = getConnection();
    $controller = new DescuentoController($pdo);
    return $controller->updateDescuento($data, $id);
}

// Función que llama al controlador para eliminar un descuento
function EliminarDescuento($id)
{
    $pdo = getConnection();
    $controller = new DescuentoController($pdo);
    return $controller->deleteDescuento($id);
}

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
