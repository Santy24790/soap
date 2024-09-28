<?php
// models/Categoria.php
class Categoria
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las categorías
    public function getCategorias()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM categoria");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener categoría por ID
    public function getCategoriaById($id)
    {
        $sql = "SELECT * FROM categoria WHERE idcategoria = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear categoría
    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categoria (nombre) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->execute();
            return "Categoría guardada correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar categoría
    public function update($data, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE categoria SET nombre = :nombre WHERE idcategoria = :id");
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return "Categoría actualizada correctamente";
        } else {
            return "Error al actualizar la categoría: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Eliminar categoría
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categoria WHERE idcategoria = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute(); // Devuelve true si la eliminación fue exitosa, false en caso contrario
    }
}
