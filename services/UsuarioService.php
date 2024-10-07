<?php
// services/UsuarioService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/db.php'; // Usar un solo archivo de conexión
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../controllers/CategoriaController.php'; // Importar el controlador de categorías
require_once __DIR__ . '/../controllers/ProductosController.php'; // Importar el controlador de productos

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

// Registrar método SOAP para buscar una categoría
$server->register(
    'BuscarCategoria',
    array('nombre' => 'xsd:string'), // Parámetro de entrada
    array('return' => 'xsd:string'), // Tipo de retorno
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar una categoría por nombre'
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
$server->register(
    'FiltrarProductos',
    array('filtros' => 'tns:Array'), // Parámetro de entrada
    array('return' => 'xsd:string'), // Tipo de retorno
    $namespace,
    false,
    'rpc',
    'encoded',
    'Filtrar productos por nombre, stock, categoría, etc.'
);
$server->wsdl->addComplexType(
    'FiltroProducto',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
        'stock' => array('name' => 'stock', 'type' => 'xsd:int'),
        'categoria' => array('name' => 'categoria', 'type' => 'xsd:int')
    )
);

// Registrar método SOAP para filtrar productos
$server->register(
    'BuscarEnProductos',
    array('valor' => 'xsd:string'), // Parámetro de entrada
    array('return' => 'xsd:string'), // Tipo de retorno
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar en productos por un valor en cualquier campo'
);

// Función que llama al controlador para buscar en productos
function BuscarEnProductos($valor)
{
    $pdo = getConnection('productos_bd'); // Conectar a la base de datos de productos
    $controller = new ProductoController($pdo); // Instanciar el controlador

    // Capturar la salida del controlador en formato XML
    ob_start();
    $controller->buscarEnProductos($valor); // Pasar el valor de búsqueda al controlador
    $xmlResponse = ob_get_clean();

    return $xmlResponse; // Devolver la respuesta en XML
}
// Función que llama al controlador para buscar una categoría por nombre
function BuscarCategoria($nombre)
{
    $pdo = getConnection('productos_bd'); // Conectar a la base de datos de productos
    $categoriaModel = new Categoria($pdo); // Pasar la conexión al modelo
    $categorias = $categoriaModel->buscarPorNombre($nombre);

    // Convertir los resultados a XML y devolverlos como respuesta SOAP
    $xml = new SimpleXMLElement('<categorias/>');
    foreach ($categorias as $categoria) {
        $categoriaNode = $xml->addChild('categoria');
        foreach ($categoria as $key => $value) {
            $categoriaNode->addChild($key, htmlspecialchars($value));
        }
    }
    return $xml->asXML();
}

// Función que llama al controlador para obtener todos los usuarios
function VerUsuarios()
{
    $pdo = getConnection('gestion_usuarios'); // Conectar a la base de datos de usuarios
    $controller = new UserController($pdo);
    return $controller->getAllUsers();
}

// Función que llama al controlador para obtener el detalle de un usuario
function VerUsuario($id)
{
    $pdo = getConnection('gestion_usuarios');
    $controller = new UserController($pdo);
    return $controller->getUserDetail($id);
}

// Función que llama al controlador para crear un usuario
function CrearUsuario($data)
{
    $pdo = getConnection('gestion_usuarios');
    $controller = new UserController($pdo);
    return $controller->createUser($data);
}

// Función que llama al controlador para actualizar un usuario
function ActualizarUsuario($data, $id)
{
    $pdo = getConnection('gestion_usuarios');
    $controller = new UserController($pdo);
    return $controller->updateUser($data, $id);
}

// Función que llama al controlador para eliminar un usuario
function EliminarUsuario($id)
{
    $pdo = getConnection('gestion_usuarios');
    $controller = new UserController($pdo);
    return $controller->deleteUser($id);
}

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
