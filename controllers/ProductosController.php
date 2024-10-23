<?php
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

        // Generar XML
        $xml = new SimpleXMLElement('<producto/>');
        
        if (is_array($resultado)) {
            $xml->addChild('precio_original', $resultado['precio_original']);
            $xml->addChild('descuento', $resultado['descuento']);
            $xml->addChild('precio_final', $resultado['precio_final']);
        } else {
            $xml->addChild('error', htmlspecialchars($resultado)); // Escapar en caso de error (producto no encontrado)
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
    }

    public function obtenerProductos($valor) {
        // Llamar al método del modelo para obtener productos
        $productos = $this->productoModel->obtenerProductos($valor); // Asegúrate de que este método exista en tu modelo
        
        return $productos; // Retorna el array de productos
    }

    public function buscarEnProductos($valor)
    {
        $productos = $this->obtenerProductos($valor); // Llama al método obtenerProductos

        // Generar XML
        $xml = new \SimpleXMLElement('<productos/>');

        foreach ($productos as $producto) {
            $productoXml = $xml->addChild('producto');
            $productoXml->addChild('idproductos', $producto['idproductos']);
            $productoXml->addChild('nombre', htmlspecialchars($producto['nombre']));
            $productoXml->addChild('descripcion', htmlspecialchars($producto['descripcion']));
            $productoXml->addChild('stock', $producto['stock']);
            $productoXml->addChild('idcategoria', $producto['idcategoria']);
            $productoXml->addChild('idproveedor', $producto['idproveedor']);
            $productoXml->addChild('idestado', $producto['idestado']);
            $productoXml->addChild('iddescuento', $producto['iddescuento']);
            $productoXml->addChild('precio', $producto['precio']);
            }

        header('Content-Type: text/xml');
        echo $xml->asXML();
    }

    public function getAllProductos()
    {
        $productos = $this->productoModel->getProductos();

        if ($productos) {
            // Generar XML
            $xml = new SimpleXMLElement('<productos/>');
            foreach ($productos as $producto) {
                $productoNode = $xml->addChild('producto');
                foreach ($producto as $key => $value) {
                    $productoNode->addChild($key, htmlspecialchars($value));
                }
            }

            // Asegúrate de que no haya enviado ningún contenido antes de este encabezado
            header('Content-Type: text/xml');
            echo $xml->asXML();
        } else {
            // Manejo de errores
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'No se encontraron productos');
            header('Content-Type: text/xml');
            echo $xml->asXML();
        }
    }

    public function getProductoDetail($id)
    {
        $producto = $this->productoModel->getProductoById($id);

        if ($producto) {
            $xml = new SimpleXMLElement('<producto/>');
            foreach ($producto as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }

            header('Content-Type: text/xml');
            echo $xml->asXML();
        } else {
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'Producto no encontrado');
            header('Content-Type: text/xml');
            echo $xml->asXML();
        }
    }

    public function createProducto($data)
    {
        if (!isset($data['nombre'], $data['descripcion'], $data['stock'], $data['idcategoria'], $data['idproveedor'], $data['idestado'], $data['iddescuento'], $data['precio'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->productoModel->create($data);
    }

    public function updateProducto($data, $id)
    {
        return $this->productoModel->update($data, $id);
    }

    public function deleteProducto($id)
    {
        $resultado = $this->productoModel->delete($id);

        $xml = new SimpleXMLElement('<respuesta/>');
        if ($resultado) {
            $xml->addChild('mensaje', 'Producto eliminado correctamente');
        } else {
            $xml->addChild('mensaje', 'Error al eliminar el producto');
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
    }
}
