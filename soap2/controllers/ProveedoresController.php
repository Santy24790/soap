<?php
// controllers/ProveedorController.php
require_once __DIR__ . '/../models/proveedores.php';

class ProveedorController
{
    private $proveedorModel;

    public function __construct($pdo)
    {
        $this->proveedorModel = new Proveedor($pdo);
    }

    // Obtener todos los proveedores
    public function getAllProveedores()
    {
        // Llama al método del modelo para obtener todos los proveedores
        $proveedores = $this->proveedorModel->getProveedores();

        if ($proveedores) {
            // Generar XML
            $xml = new SimpleXMLElement('<proveedores/>');
            
            foreach ($proveedores as $proveedor) {
                $proveedorNode = $xml->addChild('proveedor');
                foreach ($proveedor as $key => $value) {
                    $proveedorNode->addChild($key, htmlspecialchars($value)); // Añadir cada campo del proveedor
                }
            }

            // Retorna el XML generado como una cadena
            header('Content-Type: text/xml'); // Asegúrate de establecer el tipo de contenido correcto
            echo $xml->asXML();
            exit;
        } else {
            // Manejo de errores
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'No se encontraron proveedores');
            
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Obtener detalle de un proveedor por ID
    public function getProveedorDetail($id)
    {
        // Llama al método del modelo para obtener el proveedor por ID
        $proveedor = $this->proveedorModel->getProveedorById($id);

        if ($proveedor) {
            // Generar XML
            $xml = new SimpleXMLElement('<proveedor/>');
            foreach ($proveedor as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value)); // Añadir cada campo del proveedor
            }

            // Retorna el XML generado como una cadena
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        } else {
            // Manejo de errores si no se encuentra el proveedor
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'Proveedor no encontrado');
            
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Crear nuevo proveedor
    public function createProveedor($data)
    {
        // Aquí ajustamos los nombres de los campos para que coincidan con los definidos en el servicio SOAP
        if (!isset($data['marca'])) {
            return "Error: Falta el campo requerido 'marca'.";
        }
        return $this->proveedorModel->create($data);
    }

    // Actualizar proveedor existente
    public function updateProveedor($data, $id)
    {
        return $this->proveedorModel->update($data, $id);
    }

    // Eliminar proveedor
    public function deleteProveedor($id)
    {
        // Llama al método del modelo para eliminar el proveedor por ID
        $resultado = $this->proveedorModel->delete($id);

        // Generar XML para la respuesta
        $xml = new SimpleXMLElement('<respuesta/>');

        if ($resultado) {
            // Proveedor eliminado correctamente
            $xml->addChild('mensaje', 'Proveedor eliminado correctamente');
        } else {
            // Manejo de errores si no se pudo eliminar el proveedor
            $xml->addChild('mensaje', 'Error al eliminar el proveedor');
        }

        // Establecer el tipo de contenido como XML y devolver la respuesta
        header('Content-Type: text/xml');
        echo $xml->asXML();
        exit;
    }
}
