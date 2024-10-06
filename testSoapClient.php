<?php
require_once __DIR__ . '/vendor/econea/nusoap/src/nusoap.php';

// URL del servicio SOAP
$wsdl = "http://localhost/soap1/categoriaSoap.php?wsdl";

// Crear un cliente para el servicio
$client = new nusoap_client($wsdl, 'wsdl');

// Verificar si hubo errores en la construcción del cliente
$error = $client->getError();
if ($error) {
    echo "Error en la construcción del cliente: $error";
    exit();
}

// Llamar a los métodos del servicio
// VerCategorias
$result = $client->call('VerCategorias');
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "Respuesta de VerCategorias: <br>";
        echo htmlspecialchars($result);
    }
}

// VerCategoria
$result = $client->call('VerCategoria', array('id' => 1));
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "<br><br>Respuesta de VerCategoria: <br>";
        echo htmlspecialchars($result);
    }
}

// CrearCategoria
$data = array('nombre' => 'Nueva Categoria');
$result = $client->call('CrearCategoria', array('data' => $data));
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "<br><br>Respuesta de CrearCategoria: <br>";
        echo htmlspecialchars($result);
    }
}

// ActualizarCategoria
$data = array('nombre' => 'Categoria Actualizada');
$result = $client->call('ActualizarCategoria', array('data' => $data, 'id' => 1));
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "<br><br>Respuesta de ActualizarCategoria: <br>";
        echo htmlspecialchars($result);
    }
}

// EliminarCategoria
$result = $client->call('EliminarCategoria', array('id' => 1));
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "<br><br>Respuesta de EliminarCategoria: <br>";
        echo htmlspecialchars($result);
    }
}

