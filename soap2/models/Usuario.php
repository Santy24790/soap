<?php
// models/Usuario.php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM user");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener usuario por ID
    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
    // Crear usuario
public function create($data)
{
    try {
        // Hasheando la contraseña antes de insertarla en la base de datos
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Preparar la sentencia SQL
        $stmt = $this->pdo->prepare("INSERT INTO user (user_name, last_name, number_doc, address, telephone, email, password, date_create, doc_type_id)
                                     VALUES (:user_name, :last_name, :number_doc, :address, :telephone, :email, :password, NOW(), :doc_type_id)");

        // Asignar valores a los parámetros
        $stmt->bindParam(':user_name', $data['user_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':number_doc', $data['number_doc']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':doc_type_id', $data['doc_type_id']);

        // Ejecutar la sentencia
        $stmt->execute();

        return "Usuario guardado correctamente";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


    // Actualizar usuario
    public function update($data, $id)
    {
        $sql = "UPDATE user SET user_name = :user_name, last_name = :last_name, number_doc = :number_doc, address = :address, telephone = :telephone, email = :email, password = :password, doc_type_id = :doc_type_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'user_name' => $data['user_name'],
            'last_name' => $data['last_name'],
            'number_doc' => $data['number_doc'],
            'address' => $data['address'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'password' => $data['password'],
            'doc_type_id' => $data['doc_type_id']
        ]);
        return "Usuario actualizado correctamente";
    }

    // Eliminar usuario
    public function delete($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "Usuario eliminado correctamente";
    }
}
