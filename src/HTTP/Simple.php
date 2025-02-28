<?php

namespace Lumynus\HTTP;

class Simple
{

    /**
     * Executa uma requisição HTTP de forma simples.
     *
     * @param string $url URL para a requisição.
     * @param string $metodo Método HTTP (GET, POST, PUT, DELETE). Padrão: "GET".
     * @param array $headers Cabeçalhos HTTP para a requisição.
     * @param array|string $body Corpo da requisição, pode ser um array ou uma string.
     *
     * @return array Retorna um array associativo contendo:
     *               - 'status_code' (int): Código de resposta HTTP.
     *               - 'response' (string): Corpo da resposta ou mensagem de erro.
     */
    public static function request(string $url, string $metodo = "GET", array $headers = [], array|string $body = ""): array
    {
        $metodos = ["GET", "POST", "PUT", "DELETE"];

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ["status_code" => 400, "response" => "URL inválida -> " . $url];
        }

        if (!in_array($metodo, $metodos)) {
            return ["status_code" => 400, "response" => "Método não permitido -> " . $metodo];
        }

        if (!function_exists('curl_version')) {
            return ["status_code" => 500, "response" => "A lib cURL não está habilitada. Verifique seu php.ini"];
        }

        // Detecta o tipo do corpo da requisição
        $urlEncode = false;
        $jsonEncode = false;
        $xmlEncode = false;

        foreach ($headers as $header) {
            if (stripos($header, "application/x-www-form-urlencoded") !== false) $urlEncode = true;
            if (stripos($header, "application/json") !== false) $jsonEncode = true;
            if (stripos($header, "application/xml") !== false) $xmlEncode = true;
        }

        // Converte o corpo de acordo com o tipo
        if ($urlEncode && is_array($body)) $body = http_build_query($body);
        if ($jsonEncode && is_array($body)) $body = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($xmlEncode && (!is_string($body) || stripos($body, "<?xml") === false)) {
            return ["status_code" => 400, "response" => "Body inválido para application/xml"];
        }

        // Configuração do cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($metodo !== "GET") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        // Executa a requisição
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ["status_code" => 500, "response" => "Erro na requisição -> " . $curlError];
        }

        return ["status_code" => $httpCode, "response" => $response];
    }
}
