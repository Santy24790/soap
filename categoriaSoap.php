<?php
require_once __DIR__ . '/vendor/econea/nusoap/src/nusoap.php';

// Crear un nuevo servidor SOAP
$namespace = "http://localhost/soap1/categoriaSoap.php";
$server = new soap_server();
$server->configureWSDL('ServicioCategorias', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Función para obtener todas las categorías
function VerCategorias()
{
    $categorias = [
        ['id' => 1, 'nombre' => 'Categoria 1'],
        ['id' => 2, 'nombre' => 'Categoria 2'],
    ];

    $xml = new SimpleXMLElement('<categorias/>');
    foreach ($categorias as $categoria) {
        $catNode = $xml->addChild('categoria');
        $catNode->addChild('id', $categoria['id']);
        $catNode->addChild('nombre', $categoria['nombre']);
    }

    return $xml->asXML();
}

// Función para obtener una categoría por ID
function VerCategoria($id)
{
    $categorias = [
        1 => ['id' => 1, 'nombre' => 'Categoria 1'],
        2 => ['id' => 2, 'nombre' => 'Categoria 2'],
    ];

    if (isset($categorias[$id])) {
        $xml = new SimpleXMLElement('<categoria/>');
        $xml->addChild('id', $categorias[$id]['id']);
        $xml->addChild('nombre', $categorias[$id]['nombre']);
        return $xml->asXML();
    } else {
        return "Categoría no encontrada";
    }
}

// Función para crear una nueva categoría
function CrearCategoria($data)
{
    $nombre = $data['nombre'];
    // Aquí se agregaría la lógica para insertar en la base de datos
    return "Categoría '$nombre' creada exitosamente.";
}

// Función para actualizar una categoría
function ActualizarCategoria($data, $id)
{
    $pdo = getConnection(); // Obtener la conexión a la base de datos
    $controller = new CategoriaController($pdo);
    return $controller->updateCategoria($data, $id); // Llamar al controlador para actualizar la categoría
}

// Registrar el método para actualizar una categoría
$server->register(
    'ActualizarCategoria',
    array('data' => 'tns:Categoria', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar una categoría existente'
);

// Función para eliminar una categoría
function EliminarCategoria($id)
{
    // Aquí se agregaría la lógica para eliminar de la base de datos
    return "Categoría con ID $id eliminada exitosamente.";
}

// Registrar los métodos en el servidor SOAP
$server->register(
    'VerCategorias',
    array(),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todas las categorías disponibles'
);

$server->register(
    'VerCategoria',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Obtiene una categoría por su ID'
);

// Registrar el método para crear una categoría
$server->register(
    'CrearCategoria',
    array('data' => 'tns:Categoria'), // Solo un argumento ahora
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear una nueva categoría'
);

$server->register(
    'ActualizarCategoria',
    array('data' => 'tns:Categoria', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar una categoría existente'
);

$server->register(
    'EliminarCategoria',
    array('id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar una categoría por su ID'
);

// Procesar la solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
