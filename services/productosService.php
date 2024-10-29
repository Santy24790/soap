<?php
// services/ProductoService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/productos.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

// Configuración del servicio SOAP
$namespace = "Productos";
$server = new soap_server();
$server->configureWSDL('ServicioProductos', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el producto
$server->wsdl->addComplexType(
    'Producto',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
        'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
        'stock' => array('name' => 'stock', 'type' => 'xsd:int'),
        'idcategoria' => array('name' => 'idcategoria', 'type' => 'xsd:int'),
        'idproveedor' => array('name' => 'idproveedor', 'type' => 'xsd:int'),
        'idestado' => array('name' => 'idestado', 'type' => 'xsd:int'),
        'iddescuento' => array('name' => 'iddescuento', 'type' => 'xsd:int'),
        'precio' => array('name' => 'precio', 'type' => 'xsd:float'),
    )
);

// Registrar métodos SOAP

$server->register(
    'CrearProducto',
    array('data' => 'tns:Producto'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un producto'
);

$server->register(
    'ActualizarProducto',
    array('data' => 'tns:Producto', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un producto'
);

$server->register(
    'EliminarProducto',
    array('id' => 'xsd:int'), 
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un producto'
);

$server->register(
    'CalcularTotalConDescuentoPorNombre',
    array('nombre' => 'xsd:string'), 
    array('return' => 'xsd:string'), 
    $namespace,
    false,
    'rpc',
    'encoded',
    'Calcular el total con descuento de un producto'
);

$server->register(
    'FiltrarProductos',
    array('valor' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar productos por valor en cualquier campo'
);

$server->register(
    'BuscarCategoria',
    array('nombre' => 'xsd:string'), // Parámetro para buscar por nombre de categoría
    array('return' => 'xsd:string'), // Devolvemos XML como string
    $namespace,
    false,
    'rpc',
    'encoded',
    'Buscar categorías por nombre'
);

// Implementación de funciones

function CrearProducto($data) {
    $pdo = getConnection();
    $controller = new ProductoController($pdo);
    return $controller->createProducto($data);
}

function ActualizarProducto($data, $id) {
    $pdo = getConnection();
    $controller = new ProductoController($pdo);
    return $controller->updateProducto($data, $id);
}

function EliminarProducto($id) {
    $pdo = getConnection();
    $controller = new ProductoController($pdo);
    return $controller->deleteProducto($id);
}

function CalcularTotalConDescuentoPorNombre($nombre) {
    $pdo = getConnection('productos_bd');
    $controller = new ProductoController($pdo);
    
    ob_start();
    try {
        $resultado = $controller->calcularTotalConDescuentoPorNombre($nombre);
        if ($resultado) {
            echo "<response><totalConDescuento>" . htmlspecialchars($resultado) . "</totalConDescuento></response>";
        } else {
            echo "<error><message>No se encontró el producto.</message></error>";
        }
        $xmlResponse = ob_get_clean();
    } catch (Exception $e) {
        $xmlResponse = "<error><message>" . htmlspecialchars($e->getMessage()) . "</message></error>";
    }
    return $xmlResponse;
}

function FiltrarProductos($valor) {
    $pdo = getConnection('productos_bd');
    $controller = new ProductoController($pdo);

    ob_start();
    try {
        $resultado = $controller->buscarEnProductos($valor);
        echo "<response><productos>" . htmlspecialchars($resultado) . "</productos></response>"; // Devuelve productos
        $xmlResponse = ob_get_clean();
    } catch (Exception $e) {
        $xmlResponse = "<error><message>" . htmlspecialchars($e->getMessage()) . "</message></error>";
    }
    return $xmlResponse;
}

function BuscarCategoria($nombre) {
    $pdo = getConnection('productos_bd');
    $controller = new ProductoController($pdo);

    ob_start();
    try {
        $resultado = $controller->buscarCategoria($nombre); // Llama al método del controlador
        if ($resultado) {
            echo "<response><categorias>" . htmlspecialchars($resultado) . "</categorias></response>";
        } else {
            echo "<error><message>No se encontró la categoría.</message></error>";
        }
        $xmlResponse = ob_get_clean();
    } catch (Exception $e) {
        $xmlResponse = "<error><message>" . htmlspecialchars($e->getMessage()) . "</message></error>";
    }
    return $xmlResponse;
}

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
