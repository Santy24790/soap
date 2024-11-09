<?php
// models/Proveedor.php
class Proveedor
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los proveedores
    public function getProveedores()
    {
        try {
            // Cambiar "proveedores" por "proveedor"
            $stmt = $this->pdo->query("SELECT * FROM proveedor");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener proveedor por ID
    public function getProveedorById($id)
    {
        // Cambiar 'idproveedores' por 'idproveedor' si ese es el nombre correcto de la columna
        $sql = "SELECT * FROM proveedor WHERE idproveedor = :id";
        
        // Preparar la consulta SQL
        $stmt = $this->pdo->prepare($sql);
        
        // Ejecutar la consulta con el valor del parámetro id
        $stmt->execute(['id' => $id]);
        
        // Retornar los resultados de la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear proveedor
    public function create($data)
    {
        try {
            // Cambiar "proveedores" por "proveedor" y ajusta los campos si es necesario
            $stmt = $this->pdo->prepare("INSERT INTO proveedor (marca)
            VALUES (:marca)");

            // Enlazamos los parámetros
            $stmt->bindParam(':marca', $data['marca']);
            
            // Ejecutamos la consulta
            $stmt->execute();
            return "Proveedor guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    
    // Actualizar proveedor
    public function update($data, $id)
    {
        // Cambiar "proveedores" por "proveedor" y ajustar el nombre del campo idproveedor
        $stmt = $this->pdo->prepare("UPDATE proveedor 
                                      SET marca = :marca
                                      WHERE idproveedor = :id");

        // Enlazar los parámetros
        $stmt->bindParam(':marca', $data['marca']);
        $stmt->bindParam(':id', $id); // Enlazar el ID del proveedor a actualizar

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return "Proveedor actualizado correctamente";
        } else {
            return "Error al actualizar el proveedor: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Eliminar proveedor
    public function delete($id)
    {
        // Cambiar "proveedores" por "proveedor" y ajustar el nombre del campo idproveedor
        $stmt = $this->pdo->prepare("DELETE FROM proveedor WHERE idproveedor = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute(); // Devuelve true si la eliminación fue exitosa, false en caso contrario
    }
}
