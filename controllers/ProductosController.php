<?php
// controllers/ProductoController.php
// controllers/ProductoController.php
require_once __DIR__ . '/../models/producto.php';

class ProductoController
{
    private $productoModel;

    public function __construct($pdo)
    {
        $this->productoModel = new Producto($pdo);
    }
    public function calcularTotalConDescuentoPorNombre($nombre)
    {
        $resultado = $this->productoModel->calcularTotalConDescuentoPorNombre($nombre);

        // Devolver los resultados en formato XML
        $xml = new SimpleXMLElement('<producto/>');
        
        if (is_array($resultado)) {
            $xml->addChild('precio_original', $resultado['precio_original']);
            $xml->addChild('descuento', $resultado['descuento']);
            $xml->addChild('precio_final', $resultado['precio_final']);
        } else {
            $xml->addChild('error', $resultado); // En caso de error (producto no encontrado)
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
    }

    public function buscarEnProductos($valor)
    {
        $productos = $this->productoModel->buscarEnProductos($valor);

        // Crear la respuesta en XML
        $xml = new SimpleXMLElement('<productos/>');
        foreach ($productos as $producto) {
            $productoNode = $xml->addChild('producto');
            foreach ($producto as $key => $value) {
                $productoNode->addChild($key, htmlspecialchars($value));
            }
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
    }
    // Obtener todos los productos
    public function getAllProductos()
{
    // Llama al método del modelo para obtener todos los productos
    $productos = $this->productoModel->getProductos();

    if ($productos) {
        // Generar XML
        $xml = new SimpleXMLElement('<productos/>');
        
        foreach ($productos as $producto) {
            $productoNode = $xml->addChild('producto');
            foreach ($producto as $key => $value) {
                $productoNode->addChild($key, htmlspecialchars($value)); // Añadir cada campo del producto
            }
        }

        // Retorna el XML generado como una cadena
        header('Content-Type: text/xml'); // Asegúrate de establecer el tipo de contenido correcto
        echo $xml->asXML();
        exit; // Termina el script después de enviar el XML
    } else {
        // Manejo de errores
        $xml = new SimpleXMLElement('<error/>');
        $xml->addChild('mensaje', 'No se encontraron productos');
        
        header('Content-Type: text/xml');
        echo $xml->asXML();
        exit;
    }
}


    // Obtener detalle de un producto por ID
    public function getProductoDetail($id)
{
    // Llama al método del modelo para obtener el producto por ID
    $producto = $this->productoModel->getProductoById($id);

    if ($producto) {
        // Generar XML
        $xml = new SimpleXMLElement('<producto/>');
        foreach ($producto as $key => $value) {
            $xml->addChild($key, htmlspecialchars($value)); // Añadir cada campo del producto
        }

        // Retorna el XML generado como una cadena
        header('Content-Type: text/xml'); // Asegúrate de establecer el tipo de contenido correcto
        echo $xml->asXML();
        exit; // Termina el script después de enviar el XML
    } else {
        // Manejo de errores si no se encuentra el producto
        $xml = new SimpleXMLElement('<error/>');
        $xml->addChild('mensaje', 'Producto no encontrado');
        
        header('Content-Type: text/xml');
        echo $xml->asXML();
        exit;
    }
}

    // Crear nuevo producto
    public function createProducto($data)
    {
        // Aquí ajustamos los nombres de los campos para que coincidan con los definidos en el servicio SOAP
        if (!isset($data['nombre'], $data['descripcion'], $data['stock'], $data['idcategoria'], $data['idproveedor'], $data['idestado'], $data['iddescuento'], $data['precio'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->productoModel->create($data);
    }

    // Actualizar producto existente
    public function updateProducto($data, $id)
    {
        return $this->productoModel->update($data, $id);
    }

    // Eliminar producto
    public function deleteProducto($id)
{
    // Llama al método del modelo para eliminar el producto por ID
    $resultado = $this->productoModel->delete($id);

    // Generar XML para la respuesta
    $xml = new SimpleXMLElement('<respuesta/>');

    if ($resultado) {
        // Producto eliminado correctamente
        $xml->addChild('mensaje', 'Producto eliminado correctamente');
    } else {
        // Manejo de errores si no se pudo eliminar el producto
        $xml->addChild('mensaje', 'Error al eliminar el producto');
    }

    // Establecer el tipo de contenido como XML y devolver la respuesta
    header('Content-Type: text/xml');
    echo $xml->asXML();
    exit; // Termina el script después de enviar el XML
}

}

