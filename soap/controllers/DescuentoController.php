<?php
// controllers/DescuentoController.php
require_once __DIR__ . '/../models/Descuento.php';

class DescuentoController
{
    private $descuentoModel;

    public function __construct($pdo)
    {
        $this->descuentoModel = new Descuento($pdo);
    }

    // Obtener todos los descuentos
    public function getAllDescuentos()
    {
        return $this->descuentoModel->getDescuentos();
    }

    // Obtener detalle de un descuento
    public function getDescuentoDetail($id)
    {
        return $this->descuentoModel->getDescuentoById($id);
    }

    // Crear un nuevo descuento
    public function createDescuento($data)
    {
        return $this->descuentoModel->create($data);
    }

    // Actualizar un descuento existente
    public function updateDescuento($data, $id)
    {
        return $this->descuentoModel->update($data, $id);
    }

    // Eliminar un descuento
    public function deleteDescuento($id)
    {
        return $this->descuentoModel->delete($id);
    }
}
