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
        try {
            // Realiza una consulta para obtener todos los estados
            $stmt = $this->pdo->query("SELECT * FROM estado");
            // Devuelve los resultados en forma de array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En caso de error, devuelve un array con el mensaje de error
            return array('error' => $e->getMessage());
        }
    }

    // Obtener estado por ID
    public function getEstadoById($id)
    {
        try {
            // Preparamos la consulta SQL con un placeholder para el ID
            $sql = "SELECT * FROM estado WHERE idestado = :id";
            $stmt = $this->pdo->prepare($sql);
            // Ejecutamos la consulta con el valor del parámetro id
            $stmt->execute(['id' => $id]);
            // Retornamos el resultado como un array asociativo
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    // Crear un nuevo estado
    public function create($data)
    {
        try {
            // Preparamos la consulta SQL de inserción
            $stmt = $this->pdo->prepare("INSERT INTO estado (estado) VALUES (:estado)");

            // Enlazamos los parámetros de la consulta con los datos recibidos
            $stmt->bindParam(':estado', $data['estado']);

            // Ejecutamos la consulta y verificamos si se guardó correctamente
            if ($stmt->execute()) {
                return "Estado guardado correctamente";
            } else {
                return "Error al guardar el estado: " . implode(", ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    
    // Actualizar un estado existente
    public function update($data, $id)
    {
        try {
            // Preparamos la consulta SQL para actualizar el estado
            $stmt = $this->pdo->prepare("UPDATE estado SET estado = :estado WHERE idestado = :id");

            // Enlazamos los parámetros de la consulta con los datos recibidos
            $stmt->bindParam(':estado', $data['estado']);
            $stmt->bindParam(':id', $id); // Enlazamos el ID del estado a actualizar

            // Ejecutamos la consulta y verificamos si se actualizó correctamente
            if ($stmt->execute()) {
                return "Estado actualizado correctamente";
            } else {
                return "Error al actualizar el estado: " . implode(", ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Eliminar un estado por ID
    public function delete($id)
    {
        try {
            // Preparamos la consulta SQL para eliminar el estado
            $stmt = $this->pdo->prepare("DELETE FROM estado WHERE idestado = :id");

            // Enlazamos el ID del estado a eliminar
            $stmt->bindParam(':id', $id);

            // Ejecutamos la consulta y verificamos si se eliminó correctamente
            if ($stmt->execute()) {
                return true;
            } else {
                return "Error al eliminar el estado: " . implode(", ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
