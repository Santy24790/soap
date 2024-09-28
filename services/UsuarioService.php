<?php
// services/UsuarioService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

// Configuración del servicio SOAP
$namespace = "user";
$server = new soap_server();
$server->configureWSDL('ServicioUsuarios', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el usuario
$server->wsdl->addComplexType(
    'Usuario',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'user_name' => array('name' => 'user_name', 'type' => 'xsd:string'),
        'last_name' => array('name' => 'last_name', 'type' => 'xsd:string'),
        'number_doc' => array('name' => 'number_doc', 'type' => 'xsd:string'),
        'address' => array('name' => 'address', 'type' => 'xsd:string'),
        'telephone' => array('name' => 'telephone', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'password' => array('name' => 'password', 'type' => 'xsd:string'),
        'doc_type_id' => array('name' => 'doc_type_id', 'type' => 'xsd:int'),
        
    )
);


// Registrar método SOAP para ver todos los usuarios
$server->register(
    'VerUsuarios',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los usuarios'
);

// Registrar método SOAP para ver detalle de un usuario
$server->register(
    'VerUsuario',
    array('id' => 'tns:int'),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Detalle de un usuario'
);

// Registrar método SOAP para crear un usuario
$server->register(
    'CrearUsuario',
    array('data' => 'tns:Usuario'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un usuario'
);

// Registrar método SOAP para actualizar un usuario
$server->register(
    'ActualizarUsuario',
    array('data' => 'tns:Usuario', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un usuario'
);

// Registrar método SOAP para eliminar un usuario
$server->register(
    'EliminarUsuario',
    array('id' => 'tns:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un usuario'
);


// Función que llama al controlador para ver todos los usuarios
function VerUsuarios() {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->getAllUsers();
}

// Función que llama al controlador para obtener el detalle de uh usuario
function VerUsuario($id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->getUserDetail($id);
}

// Función que llama al controlador para crear usuario
function CrearUsuario($data) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->createUser($data);
}

// Función que llama al controlador para actualizar usuario
function ActualizarUsuario($data, $id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->updateUser($data, $id);
}


// Función que llama al controlador para eliminar usuario
function EliminarUsuario($id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->deleteUser($id);
}

//-----------------------

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
