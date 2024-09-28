<?php
// services/LoginService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/LoginController.php';

// Configuración del servicio SOAP
$namespace = "LoginUsuario";
$server = new soap_server();
$server->configureWSDL('ServicioLogin', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el usuario
$server->wsdl->addComplexType(
    'Credenciales',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'password' => array('name' => 'password', 'type' => 'xsd:string'),
    )
);

// Registrar método SOAP para credenciales de usuario
$server->register(
    'InicioSesion',
    array('data' => 'tns:Credenciales'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Servicio para insertar las credenciales de inicio de sesión de un usuario'
);

// Función que llama al controlador para crear las credenciales de inicio de sesion de un usuario
function InicioSesion($data)
{
    $pdo = getConnection();
    $controller = new LoginController($pdo);
    return $controller->loginUser($data);
}


//-----------------------

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
