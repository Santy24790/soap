<?php
namespace Services;

class SoapService {
    public static function consumirServicioSoap($location, $action, $request) {
        // Definir los encabezados para la solicitud SOAP
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
        
        // Verificar si hubo un error en cURL
        if ($response === false) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return "Error en la solicitud SOAP: " . $error_message;
        }

        // Obtener el código de respuesta HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Si la respuesta HTTP no es 200, devolver un mensaje de error
        if ($http_code !== 200) {
            return "Error en la respuesta HTTP. Código: " . $http_code;
        }

        // Retornar la respuesta en formato XML (legible)
        return self::formatXmlResponse($response);
    }

    // Función para formatear la respuesta XML para que sea legible
    private static function formatXmlResponse($response) {
        // Manejar la excepción si la respuesta no es un XML válido
        try {
            $xml = new \SimpleXMLElement($response);
            return $xml->asXML(); // Devolver el XML bien formateado
        } catch (\Exception $e) {
            return "Error al procesar la respuesta XML: " . $e->getMessage();
        }
    }
}
