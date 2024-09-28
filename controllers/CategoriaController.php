<?php
// controllers/CategoriaController.php
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController
{
    private $categoriaModel;

    public function __construct($pdo)
    {
        $this->categoriaModel = new Categoria($pdo);
    }

    // Obtener todas las categorías
    public function getAllCategorias()
    {
        $categorias = $this->categoriaModel->getCategorias();

        if ($categorias) {
            $xml = new SimpleXMLElement('<categorias/>');
            foreach ($categorias as $categoria) {
                $categoriaNode = $xml->addChild('categoria');
                foreach ($categoria as $key => $value) {
                    $categoriaNode->addChild($key, htmlspecialchars($value));
                }
            }
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        } else {
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'No se encontraron categorías');
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Obtener detalle de una categoría por ID
    public function getCategoriaDetail($id)
    {
        $categoria = $this->categoriaModel->getCategoriaById($id);

        if ($categoria) {
            $xml = new SimpleXMLElement('<categoria/>');
            foreach ($categoria as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        } else {
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'Categoría no encontrada');
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Crear nueva categoría
    public function createCategoria($data)
    {
        if (!isset($data['nombre'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->categoriaModel->create($data);
    }

    // Actualizar categoría existente
    public function updateCategoria($data, $id)
    {
        return $this->categoriaModel->update($data, $id);
    }

    // Eliminar categoría
    public function deleteCategoria($id)
    {
        $resultado = $this->categoriaModel->delete($id);
        $xml = new SimpleXMLElement('<respuesta/>');

        if ($resultado) {
            $xml->addChild('mensaje', 'Categoría eliminada correctamente');
        } else {
            $xml->addChild('mensaje', 'Error al eliminar la categoría');
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
        exit;
    }
}
