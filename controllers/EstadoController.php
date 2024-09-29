<?php
// controllers/EstadoController.php
require_once __DIR__ . '/../models/estado.php';

class EstadoController
{
    private $estadoModel;

    public function __construct($pdo)
    {
        $this->estadoModel = new Estado($pdo);
    }

    // Obtener todos los estados
    public function getAllEstados()
    {
        // Llama al método del modelo para obtener todos los estados
        $estados = $this->estadoModel->getEstados();

        if ($estados) {
            // Generar XML
            $xml = new SimpleXMLElement('<estados/>');

            foreach ($estados as $estado) {
                $estadoNode = $xml->addChild('estado');
                foreach ($estado as $key => $value) {
                    $estadoNode->addChild($key, htmlspecialchars($value)); // Añadir cada campo del estado
                }
            }

            // Retorna el XML generado como una cadena
            header('Content-Type: text/xml'); // Asegúrate de establecer el tipo de contenido correcto
            echo $xml->asXML();
            exit; // Termina el script después de enviar el XML
        } else {
            // Manejo de errores
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'No se encontraron estados');

            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Obtener detalle de un estado por ID
    public function getEstadoDetail($id)
    {
        // Llama al método del modelo para obtener el estado por ID
        $estado = $this->estadoModel->getEstadoById($id);

        if ($estado) {
            // Generar XML
            $xml = new SimpleXMLElement('<estado/>');
            foreach ($estado as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value)); // Añadir cada campo del estado
            }

            // Retorna el XML generado como una cadena
            header('Content-Type: text/xml'); // Asegúrate de establecer el tipo de contenido correcto
            echo $xml->asXML();
            exit; // Termina el script después de enviar el XML
        } else {
            // Manejo de errores si no se encuentra el estado
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'Estado no encontrado');

            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Crear nuevo estado
    public function createEstado($data)
    {
        if (!isset($data['estado'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->estadoModel->create($data);
    }

    // Actualizar estado existente
    public function updateEstado($data, $id)
    {
        return $this->estadoModel->update($data, $id);
    }

    // Eliminar estado
    public function deleteEstado($id)
    {
        $resultado = $this->estadoModel->delete($id);

        $xml = new SimpleXMLElement('<respuesta/>');
        if ($resultado) {
            $xml->addChild('mensaje', 'Estado eliminado correctamente');
        } else {
            $xml->addChild('mensaje', 'Error al eliminar el estado');
        }

        header('Content-Type: text/xml');
        echo $xml->asXML();
        exit;
    }
}
