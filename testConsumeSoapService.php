<?php
require_once __DIR__ . '/vendor/econea/nusoap/src/nusoap.php';
$location = "http://localhost:90/bd.php/categoriaSoap.php?wsdl";

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

$data = array('nombre' => 'Nueva Categoria'); // Cambia el nombre aquí
$requestData = array('data' => $data); // Estructura de datos que se enviará

// Llamar al método CrearCategoria
$result = $client->call('CrearCategoria', $requestData);

// Verificar si hubo errores durante la llamada
if ($client->fault) {
    echo "Falla: ";
    print_r($result);
} else {
    $error = $client->getError();
    if ($error) {
        echo "Error: $error";
    } else {
        echo "Respuesta de CrearCategoria: <br>";
        echo htmlspecialchars($result); // Mostrar la respuesta
    }
}

// Preparar los headers para la solicitud
$headers = [
    'Content-Type: text/xml; charset=utf-8',
    'SOAPAction: "http://localhost/soap1/categoriaSoap.php/CrearCategoria"',
];

// Inicializar cURL
$ch = curl_init($wsdl); // Asegúrate de que esté apuntando al archivo WSDL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($ch);
$err_status = curl_errno($ch);
curl_close($ch);

// Manejar la respuesta
if ($err_status) {
    echo "Error: " . curl_error($ch);
} else {
    echo "Respuesta del servicio:<br>";
    echo htmlspecialchars($response); // Mostrar la respuesta
}

