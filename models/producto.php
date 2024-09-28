<?php
// models/Producto.php
class Producto
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los productos
    public function getProductos()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM productos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener producto por ID
    public function getProductoById($id)
    {
        // Cambia 'idproductos' por el nombre correcto de la columna en tu tabla, si es necesario
        $sql = "SELECT * FROM productos WHERE idproductos = :id";
        
        // Preparar la consulta SQL
        $stmt = $this->pdo->prepare($sql);
        
        // Ejecutar la consulta con el valor del parámetro id
        $stmt->execute(['id' => $id]);
        
        // Retornar los resultados de la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear producto
    public function create($data)
    {
        try {
            // Preparamos la consulta con los campos correctos
            $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, stock, idcategoria, idproveedor, idestado, iddescuento, precio)
            VALUES (:nombre, :descripcion, :stock, :idcategoria, :idproveedor, :idestado, :iddescuento, :precio)");

            // Enlazamos los parámetros
                $stmt->bindParam(':nombre', $data['nombre']);
                $stmt->bindParam(':descripcion', $data['descripcion']);
                $stmt->bindParam(':stock', $data['stock']);
                $stmt->bindParam(':idcategoria', $data['idcategoria']);
                $stmt->bindParam(':idproveedor', $data['idproveedor']);
                $stmt->bindParam(':idestado', $data['idestado']);
                $stmt->bindParam(':iddescuento', $data['iddescuento']);
                $stmt->bindParam(':precio', $data['precio']);

                
            // Ejecutamos la consulta
            $stmt->execute();
            return "Producto guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    
    // Actualizar producto
    public function update($data, $id)
{
    // Preparar la consulta SQL para actualizar el producto
    $stmt = $this->pdo->prepare("UPDATE productos 
                                  SET nombre = :nombre, 
                                      descripcion = :descripcion, 
                                      stock = :stock, 
                                      idcategoria = :idcategoria, 
                                      idproveedor = :idproveedor, 
                                      idestado = :idestado, 
                                      iddescuento = :iddescuento, 
                                      precio = :precio 
                                  WHERE idproductos = :id");

            // Enlazar los parámetros
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':stock', $data['stock']);
            $stmt->bindParam(':idcategoria', $data['idcategoria']);
            $stmt->bindParam(':idproveedor', $data['idproveedor']);
            $stmt->bindParam(':idestado', $data['idestado']);
            $stmt->bindParam(':iddescuento', $data['iddescuento']);
            $stmt->bindParam(':precio', $data['precio']);
            $stmt->bindParam(':id', $id); // Enlazar el ID del producto a actualizar

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return "Producto actualizado correctamente";
            } else {
                return "Error al actualizar el producto: " . implode(", ", $stmt->errorInfo());
            }
        }


    // Eliminar producto
    public function delete($id)
{
    $stmt = $this->pdo->prepare("DELETE FROM productos WHERE idproductos = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute(); // Devuelve true si la eliminación fue exitosa, false en caso contrario
}

}
