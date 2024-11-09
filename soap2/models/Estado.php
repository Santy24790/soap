<?php
// models/Estado.php
class Estado
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los estados
    public function getEstados()
    {
        $stmt = $this->pdo->query("SELECT * FROM estados");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener estado por ID
    public function getEstadoById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estados WHERE idestado = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo estado
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO estados (estado) VALUES (:estado)");
        $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "Estado creado exitosamente.";
        } else {
            return "Error al crear el estado.";
        }
    }

    // Actualizar estado existente
    public function update($data, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE estados SET estado = :estado WHERE idestado = :id");
        $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "Estado actualizado exitosamente.";
        } else {
            return "Error al actualizar el estado.";
        }
    }

    // Eliminar estado
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM estados WHERE idestado = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "Estado eliminado correctamente.";
        } else {
            return "Error al eliminar el estado.";
        }
    }
}
?>
