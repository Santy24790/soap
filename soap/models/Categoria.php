<?php
// models/Categoria.php
class Categoria
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo; // Utilizar la conexión pasada como parámetro
    }

    // Método para buscar categorías por nombre
    public function buscarPorNombre($nombre)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categoria WHERE nombre LIKE :nombre");
        $nombre = "%" . $nombre . "%";
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function buscarEnCategorias($valor)
    {
        // Definir las columnas en las que quieres buscar
        $columnas = ['nombre', 'descripcion']; // Puedes agregar más columnas si es necesario

        // Construir la consulta SQL
        $sql = "SELECT * FROM categoria WHERE ";
        $condiciones = [];
        $params = [];

        // Generar las condiciones para cada columna
        foreach ($columnas as $columna) {
            $condiciones[] = "$columna LIKE :valor";
        }

        // Unir las condiciones con "OR" para buscar en todas las columnas
        $sql .= implode(" OR ", $condiciones);

        // Preparar y ejecutar la consulta
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':valor', "%$valor%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
?>
