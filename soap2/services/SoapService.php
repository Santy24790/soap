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
        
        // Verificar si hubo un error con cURL
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return self::generarRespuestaError("Error en la solicitud SOAP: " . $error_message);
        }

        // Obtener el código de respuesta HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Si la respuesta HTTP no es 200, devolver un mensaje de error
        if ($http_code != 200) {
            return self::generarRespuestaError("Error en la respuesta HTTP. Código: " . $http_code);
        }

        // Devolver la respuesta SOAP tal cual, sin formatear
        return $response;
    }

    // Función para generar una respuesta de error en formato XML
    private static function generarRespuestaError($mensaje) {
        $xmlError = new \SimpleXMLElement('<error/>');
        $xmlError->addChild('mensaje', htmlspecialchars($mensaje));
        return $xmlError->asXML();
    }
}

?>
