<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Categoria.php';

class UsuarioCategoriaController
{
    private $userModel;
    private $categoriaModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->categoriaModel = new Categoria($pdo);
    }

    // Método para obtener todos los usuarios
    public function getAllUsers()
    {
        return $this->userModel->getUsers();
    }

    // Método para obtener un usuario por ID
    public function getUserDetail($id)
    {
        return $this->userModel->getUserById($id);
    }

   
       
    // Método para buscar categorías por nombre
    public function buscarCategoria($nombre)
    {
        $categorias = $this->categoriaModel->buscarPorNombre($nombre);

        if ($categorias) {
            $xml = new SimpleXMLElement('<categorias/>');
            foreach ($categorias as $categoria) {
                $categoriaNode = $xml->addChild('categoria');
                foreach ($categoria as $key => $value) {
                    $categoriaNode->addChild($key, htmlspecialchars($value));
                }
            }
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        } else {
            $xml = new SimpleXMLElement('<error/>');
            $xml->addChild('mensaje', 'No se encontraron categorías con ese nombre');
            header('Content-Type: text/xml');
            echo $xml->asXML();
            exit;
        }
    }

    // Otros métodos para manejar usuarios (crear, actualizar, eliminar) pueden ser añadidos aquí
}
?>
    