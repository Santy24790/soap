<?php

namespace Services;

class SoapService {
    public static function consumirServicioSoap($location, $action, $request) {
        $headers = [
            'Method: POST',
            'Connection: Keep-Alive',
            'User-Agent: PHP-SOAP-CURL',
            'Content-Type: text/xml; charset=utf-8',
            "SOAPAction: $action",
        ];

        // Inicializar cURL
        $ch = curl_init($location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        // Ejecutar la solicitud y obtener la respuesta
        $response = curl_exec($ch);
        $err_status = curl_errno($ch);
        curl_close($ch);

        if ($err_status) {
            return "Error: " . curl_error($ch);
        } else {
            return $response;
        }
    }
}

?>
