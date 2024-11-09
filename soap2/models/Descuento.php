<?php
// models/Descuento.php
class Descuento
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los descuentos
    public function getDescuentos()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM descuento");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener descuento por ID
    public function getDescuentoById($id)
    {
        $sql = "SELECT * FROM descuento WHERE iddescuento = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear descuento
    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO descuento (descuento) VALUES (:descuento)");
            $stmt->bindParam(':descuento', $data['descuento']);
            $stmt->execute();
            return "Descuento creado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar descuento
    public function update($data, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE descuento SET descuento = :descuento WHERE iddescuento = :id");
        $stmt->bindParam(':descuento', $data['descuento']);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return "Descuento actualizado correctamente";
        } else {
            return "Error al actualizar el descuento: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Eliminar descuento
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM descuento WHERE iddescuento = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
