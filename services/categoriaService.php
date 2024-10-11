<?php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php'; 
require_once __DIR__ . '/../controllers/usuarioCategoriaController.php';

// Configuración del servicio SOAP
$namespace = "Categorias";
$server = new soap_server();
$server->configureWSDL('ServicioCategorias', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para la categoría
$server->wsdl->addComplexType(
    'Categoria',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string')
    )
);

// Función que llama al controlador para ver todas las categorías
function VerCategorias() {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->getAllCategorias(); // Asegúrate de que este método devuelva un XML
}

// Función que llama al controlador para obtener el detalle de una categoría
function VerCategoria($id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->getCategoriaDetail($id); // Asegúrate de que este método devuelva un XML
}

// Función que llama al controlador para crear una categoría
function CrearCategoria($data) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->createCategoria($data); // Asegúrate de que este método devuelva un mensaje
}

// Función que llama al controlador para actualizar una categoría
function ActualizarCategoria($data, $id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->updateCategoria($data, $id); // Asegúrate de que este método devuelva un mensaje
}

// Función que llama al controlador para eliminar una categoría
function EliminarCategoria($id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->deleteCategoria($id); // Asegúrate de que este método devuelva un mensaje
}

// Registrar métodos SOAP para ver todas las categorías
$server->register(
    'VerCategorias',
    array(),
    array('return' => 'xsd:string'), // Cambiado a xsd:string para devolver XML
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todas las categorías'
);

// Registrar método SOAP para ver detalle de una categoría
$server->register(
    "VerCategoria",
    array('id' => 'xsd:int'),    // Parámetro de entrada
    array('return' => 'xsd:string'),  // Tipo de retorno
    $namespace,
    false,
    'rpc',
    'encoded',
    'Obtiene los detalles de una categoría por su ID'
);

// Registrar método SOAP para crear una categoría
$server->register(
    'CrearCategoria',
    array('data' => 'tns:Categoria'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear una categoría'
);

// Registrar método SOAP para actualizar una categoría
$server->register(
    'ActualizarCategoria',
    array('data' => 'tns:Categoria', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar una categoría'
);

// Registrar método SOAP para eliminar una categoría
$server->register(
    'EliminarCategoria',
    array('id' => 'xsd:int'), // El argumento de entrada es el ID de la categoría
    array('return' => 'xsd:string'), // Tipo
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar una categoría'
);

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
