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
    public function VerEstados()
    {
        return $this->estadoModel->getEstados();
    }

    // Obtener detalle de un estado por ID
    public function VerEstado($id)
    {
        return $this->estadoModel->getEstadoById($id);
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
        return $this->estadoModel->delete($id);
    }
}
?>
