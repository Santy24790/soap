<?php
// services/UsuarioService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/db.php'; // Archivo de conexión a la base de datos
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../controllers/CategoriaController.php';
require_once __DIR__ . '/../controllers/ProductosController.php';
require_once __DIR__ . '/../models/Producto.php'; 
require_once __DIR__ . '/SoapService.php';
use Services\SoapService;

// Configuración del servicio SOAP
$namespace = "user";
$server = new soap_server();
$server->configureWSDL('ServicioUsuarios', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (modelo de datos) para el usuario
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

// Registro de métodos del servicio SOAP

// Buscar una categoría
$server->register(
    'BuscarCategoria',
    array('nombre' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar una categoría por nombre'
);

// Ver todos los usuarios
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

// Ver detalle de un usuario
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

// Crear un usuario
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

// Actualizar un usuario
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

// Eliminar un usuario
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

// Filtrar productos
$server->register(
    'BuscarEnProductos',
    array('valor' => 'xsd:string'),
    array('return' => 'xsd:array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar en productos por un valor en cualquier campo'
);

// Calcular el total con descuento por nombre de producto
$server->register(
    'CalcularTotalConDescuentoPorNombre',
    array('nombre' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Calcular el total con descuento para un producto, buscando por nombre'
);

// Función que llama al servicio de productos para calcular el total con descuento por nombre
function CalcularTotalConDescuentoPorNombre($nombre)
{
    // Definir el endpoint y la acción SOAP del servicio de productos
    $location = "http://localhost/soap1/services/ProductosService.php";
    $action = "http://localhost/soap1/services/ProductosService.php#CalcularTotalConDescuentoPorNom";

    // Sanitizar el nombre del producto
    $nombre = htmlspecialchars($nombre);

    // Crear la solicitud SOAP
    $request = "
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/'>
        <soapenv:Body>
            <CalcularTotalConDescuentoPorNom>
                <nombre>{$nombre}</nombre>
            </CalcularTotalConDescuentoPorNom>
        </soapenv:Body>
    </soapenv:Envelope>";

    try {
        // Llamar al servicio SOAP del producto usando SoapService
        $response = \Services\SoapService::consumirServicioSoap($location, $action, $request);

        // Verificar y devolver la respuesta
        if (!$response) {
            throw new Exception("No se recibió respuesta del servicio.");
        }

        return $response; // Retorna la respuesta tal como fue recibida
    } catch (Exception $e) {
        // Manejo de errores
        return "<error><message>" . htmlspecialchars($e->getMessage()) . "</message></error>";
    }
}

// Función que llama al controlador para buscar una categoría por nombre
function BuscarCategoria($nombre)
{
    $pdo = getConnection('productos_bd');
    $categoriaModel = new Categoria($pdo);
    $categorias = $categoriaModel->buscarPorNombre($nombre);

    $xml = new SimpleXMLElement('<categorias/>');
    foreach ($categorias as $categoria) {
        $categoriaNode = $xml->addChild('categoria');
        foreach ($categoria as $key => $value) {
            $categoriaNode->addChild($key, htmlspecialchars($value));
        }
    }
    return $xml->asXML();
}

// Funciones adicionales (VerUsuarios, VerUsuario, CrearUsuario, etc.)
// ...

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
