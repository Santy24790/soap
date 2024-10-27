<?php
// models/Producto.php
class Producto
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerDescuento($id_descuento)
    {
        $descuento = 0;

        if ($id_descuento == 1) {
            $descuento = 0.50; // 50%
        } elseif ($id_descuento == 2) {
            $descuento = 0.20; // 20%
        }

        return $descuento;
    }

    // Función para calcular el total con descuento
    public function calcularTotalConDescuentoPorNombre($nombre)
    {
        $sql = "SELECT precio, iddescuento FROM productos WHERE nombre = :nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $precio = $producto['precio'];
            $id_descuento = $producto['iddescuento'];
            $descuento = $this->obtenerDescuento($id_descuento);
            $precio_final = $precio - ($precio * $descuento);

            return [
                'precio_original' => $precio,
                'descuento' => $descuento * 100 . '%',
                'precio_final' => $precio_final
            ];
        } else {
            return "Producto no encontrado";
        }
    }

    // Obtener todos los productos
    public function getProductos()
    {
        $stmt = $this->pdo->query("SELECT * FROM productos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos por valor de búsqueda
    public function obtenerProductos($valor)
    {
        $sql = "SELECT * FROM productos WHERE nombre LIKE :valor OR descripcion LIKE :valor";
        $stmt = $this->pdo->prepare($sql);
        $valor = "%$valor%";
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve todos los productos como un array asociativo
    }

    // Obtener producto por ID
    public function getProductoById($id)
    {
        $sql = "SELECT * FROM productos WHERE idproductos = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear producto
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, stock, idcategoria, idproveedor, idestado, iddescuento, precio) 
                                     VALUES (:nombre, :descripcion, :stock, :idcategoria, :idproveedor, :idestado, :iddescuento, :precio)");
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':idcategoria', $data['idcategoria']);
        $stmt->bindParam(':idproveedor', $data['idproveedor']);
        $stmt->bindParam(':idestado', $data['idestado']);
        $stmt->bindParam(':iddescuento', $data['iddescuento']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->execute();

        return "Producto guardado correctamente";
    }

    // Actualizar producto
    public function update($data, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, stock = :stock, idcategoria = :idcategoria, 
                                     idproveedor = :idproveedor, idestado = :idestado, iddescuento = :iddescuento, precio = :precio 
                                     WHERE idproductos = :id");
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':idcategoria', $data['idcategoria']);
        $stmt->bindParam(':idproveedor', $data['idproveedor']);
        $stmt->bindParam(':idestado', $data['idestado']);
        $stmt->bindParam(':iddescuento', $data['iddescuento']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return "Producto actualizado correctamente";
    }

    // Eliminar producto
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE idproductos = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
