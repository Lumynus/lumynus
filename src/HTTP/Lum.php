<?php

namespace Lumynus\HTTP;

/**
 * Classe responsável por gerenciar requisições HTTP com suporte a autenticação e rate limiting.
 */
class Lum
{

    /**
     * @var array Lista de cabeçalhos HTTP para a requisição.
     */
    private array $headers = [];

    /**
     * @var bool Indica se o cabeçalho Content-Length deve ser incluído.
     */
    private bool $enableLength = false;

    /**
     * @var string Tipo de conteúdo da requisição (Content-Type).
     */
    private string $contentType = '';

    /**
     * @var string Tipo de aceitação da resposta (Accept).
     */
    private string $acceptType = '';

    /**
     * @var int|null Limite máximo de requisições permitidas dentro do período especificado.
     */
    private null|int $limitRequests = null;

    /**
     * @var int|null Tempo, em segundos, para o período de limitação de requisições.
     */
    private null|int $limitSeconds = null;

    /**
     * @var int Contador de requisições feitas no período atual.
     */
    private int $countRequests = 0;

    /**
     * @var int Timestamp de início do período de contagem de requisições.
     */
    private int $startTime;

    /**
     * @var int Código de status HTTP da última requisição.
     */
    private int $statusCode;

    /**
     * @var mixed Resposta da última requisição.
     */
    private mixed $response;

    /**
     * @var mixed Erro ocorrido na última requisição, se houver.
     */
    private mixed $errorRequest;

    /**
     * @var string URL da última requisição.
     */
    private string $url;

    /**
     * @var bool Informa se a  verificação de SSL foi ativada.
     */
    private bool $verifySSL = false;

    /**
     * @var string Cabeçalho Content-Length da última requisição.
     */
    private string $contentLength = 'N/A';


    public function __construct()
    {
        if (!function_exists('curl_version')) {
            throw new \Exception("A lib cURL não está habilitada. Verifique seu php.ini", 500);
        }
        $this->startTime = time();
    }


    /**
     * Define o cabeçalho de autorização Bearer.
     * @param bool $enableLength Habilita o cabeçalho Content-Length.
     * @param string $contentType Tipo de conteúdo da requisição. Padrão: 'json'.
     * @param string $acceptType Tipo de conteúdo aceito na resposta. Padrão: 'json'.
     * @param string $token Token de autorização.
     * @return void
     */
    public function bearer(bool $enableLength = false, string $contentType = 'json', string $acceptType = 'json', string $token = ''): void
    {
        $this->enableLength = $enableLength;
        $this->validContentType($contentType);
        $this->validAcceptType($acceptType);

        $this->headers = [
            "Authorization: Bearer $token",
            $this->contentType,
            $this->acceptType
        ];
    }

    /**
     * Define o cabeçalho de autorização API Key.
     * @param bool $enableLength Habilita o cabeçalho Content-Length.
     * @param string $contentType Tipo de conteúdo da requisição. Padrão: 'json'.
     * @param string $acceptType Tipo de conteúdo aceito na resposta. Padrão: 'json'.
     * @param string $token Token de autorização.
     * @return void
     */
    public function key(bool $enableLength = false, string $contentType = 'json', string $acceptType = 'json', string $token = ''): void
    {
        $this->enableLength = $enableLength;
        $this->validContentType($contentType);
        $this->validAcceptType($acceptType);

        $this->headers = [
            "X-API-Key: $token",
            $this->contentType,
            $this->acceptType
        ];
    }

    /**
     * Define o cabeçalho de autorização Basic.
     * @param bool $EnableLength Habilita o cabeçalho Content-Length.
     * @param string $contentType Tipo de conteúdo da requisição. Padrão: 'json'.
     * @param string $acceptType Tipo de conteúdo aceito na resposta. Padrão: 'json'.
     * @param string $token Token de autorização.
     * @return void
     */
    public function basic(bool $enableLength = false, string $contentType = 'json', string $acceptType = 'json', string $token = ''): void
    {
        $this->enableLength = $enableLength;
        $this->validContentType($contentType);
        $this->validAcceptType($acceptType);

        $this->headers = [
            "Authorization: Basic " . base64_encode($token),
            $this->contentType,
            $this->acceptType
        ];
    }

    /**
     * Define um cabeçalho customizado.
     * @param array $customHeaders Cabeçalhos customizados.
     * @return void
     */
    public function customHeaders(array $customHeaders): void
    {
        foreach ($customHeaders as $key => $value) {
            $this->headers[] = "$key: $value";
        }
    }

    /**
     * Define o tipo de conteúdo da requisição.
     * @param string $type Tipo de conteúdo da requisição.
     * @return void
     */
    private function validContentType(string $type): void
    {
        $contentTypes = [
            'json' => 'Content-Type: application/json',
            'url' => 'Content-Type: application/x-www-form-urlencoded',
            'form-data' => 'Content-Type: multipart/form-data',
            'text' => 'Content-Type: text/plain',
            'xml' => 'Content-Type: application/xml'
        ];

        $this->contentType = $contentTypes[$type] ?? '';
    }

    /**
     * Define o tipo de conteúdo aceito na resposta.
     * @param string $type Tipo de conteúdo aceito na resposta.
     * @return void
     */
    private function validAcceptType(string $type): void
    {
        $acceptTypes = [
            'json' => 'Accept: application/json',
            'url' => 'Accept: application/x-www-form-urlencoded',
            'form-data' => 'Accept: multipart/form-data',
            'text' => 'Accept: text/plain',
            'xml' => 'Accept: application/xml'
        ];

        $this->acceptType = $acceptTypes[$type] ?? '';
    }

    /**
     * Define o limite de requisições por segundo.
     * @param int $limit Limite de requisições.
     * @param int $seconds Segundos para o limite.
     * @return void
     */
    public function limitRequests(int $limit, int $seconds): void
    {
        $this->limitRequests = $limit;
        $this->limitSeconds = $seconds;
    }

    /**
     * Limita a quantidade de requisições por segundo.
     * @return void
     */
    private function rateLimit(): void
    {
        if ($this->countRequests >= $this->limitRequests) {
            $currentTime = time();
            $elapsedTime = $currentTime - $this->startTime;

            if ($elapsedTime < $this->limitSeconds) {
                $waitTime = $this->limitSeconds - $elapsedTime;
                sleep($waitTime);
            }
            $this->countRequests = 0;
            $this->startTime = time();
        }
    }


    /**
     * Realiza uma requisição HTTP.
     * @param string $url URL da requisição.
     * @param string $method Método da requisição.
     * @param array|string $body Corpo da requisição, pode ser um array ou uma string.
     * @return void
     */
    public function request(string $url, string $method = "GET", array|string $body = ""): void
    {
        try {
            // Salva a URL da última requisição
            $this->url = $url;

            // Limita a quantidade de requisições
            if ($this->limitRequests !== null && $this->limitSeconds !== null) {
                $this->rateLimit();
            }

            // Validações
            $methods = ["GET", "POST", "PUT", "DELETE"];
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception("A URL enviada não corresponde ao padrão RFC 2396.", 500);
            }
            if (!in_array($method, $methods)) {
                throw new \Exception("Método não suportado. Utilize: GET, POST, PUT ou DELETE.", 500);
            }

            // Detecta o tipo do corpo da requisição
            $urlEncode = false;
            $jsonEncode  = false;
            $xmlEncode   = false;
            foreach ($this->headers as $header) {
                if (stripos($header, "application/x-www-form-urlencoded") !== false) {
                    $urlEncode = true;
                }
                if (stripos($header, "application/json") !== false) {
                    $jsonEncode = true;
                }
                if (stripos($header, "application/xml") !== false) {
                    $xmlEncode = true;
                }
            }

            // Verifica se o body contém algum arquivo (instância de CURLFile)
            $hasCURLFile = false;
            if (is_array($body)) {
                foreach ($body as $value) {
                    if ($value instanceof \CURLFile) {
                        $hasCURLFile = true;
                        break;
                    }
                }
            }

            // Caso não haja arquivo, aplicar conversões conforme o header
            if (!$hasCURLFile) {
                if ($urlEncode && is_array($body)) {
                    $body = http_build_query($body);
                }
                if ($jsonEncode && is_array($body)) {
                    $body = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
                }
            } else {
                // Se estiver enviando arquivos, remova o header Content-Type para que o cURL defina o multipart corretamente.
                $this->headers = array_filter($this->headers, function ($header) {
                    return stripos($header, 'Content-Type:') === false;
                });
            }

            // Validações para body XML
            if ($xmlEncode && (!is_string($body) || stripos($body, "<?xml") === false)) {
                throw new \Exception("Body inválido para application/xml", 500);
            }

            // Verifica se a URL é segura e ativa a verificação de SSL, se necessário
            $testUrl = parse_url($url);
            $ssl = false;
            if ($testUrl['scheme'] === 'https') {
                $ssl = true;
                $this->verifySSL = true;
            }

            // Adiciona o cabeçalho Content-Length se habilitado e se não estiver enviando arquivos
            if ($this->enableLength && !$hasCURLFile) {
                $len = strlen(is_string($body) ? $body : json_encode($body));
                $this->headers[] = 'Content-Length: ' . $len;
                $this->contentLength = $len;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_FAILONERROR, false);

            if ($method !== "GET") {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }

            $this->response = curl_exec($ch);
            $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->errorRequest = curl_error($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            $this->response = null;
            $this->statusCode = 500;
            $this->errorRequest = $e->getMessage();
        }

        // Incrementa o contador de requisições
        $this->countRequests++;
    }


    /**
     * Retorna o código de status da última requisição.
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Retorna a resposta da última requisição.
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * Retorna o erro da última requisição.
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->errorRequest;
    }

    /**
     * Retorna a resposta como array.
     *
     * @return array
     */
    public function getArray(): array
    {
        // Tenta decodificar a resposta assumindo que é um JSON
        $decoded = json_decode($this->response, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Retorna a resposta como objeto.
     *
     * @return object|null
     */
    public function getObject(): ?object
    {
        // Tenta decodificar a resposta assumindo que é um JSON
        $decoded = json_decode($this->response);
        return is_object($decoded) ? $decoded : null;
    }

    /**
     * Retorna a resposta no formato JSON.
     * Caso a resposta já seja uma string JSON válida, retorna-a diretamente.
     * Se a resposta for um array ou objeto, converte-a para JSON.
     *
     * @return string
     */
    public function getJson(): string
    {
        // Tenta decodificar para verificar se é um JSON válido
        json_decode($this->response);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->response;
        }

        // Se a resposta for um array ou objeto, converte para JSON
        if (is_array($this->response) || is_object($this->response)) {
            return json_encode($this->response);
        }

        // Se não for possível decodificar, encapsula a resposta em um objeto JSON
        return json_encode(["data" => $this->response]);
    }

    /**
     * Salva a resposta em um arquivo no caminho especificado.
     * Antes de salvar, tenta converter a resposta para um JSON formatado (pretty).
     *
     * @param string $path Caminho completo para salvar o arquivo.
     * 
     * @return true|string Retorna true se o arquivo for salvo com sucesso, ou uma mensagem de erro em caso de falha.
     */
    public function saveFile(string $path): mixed
    {
        if (empty($this->response)) {
            return "Nenhuma resposta para salvar.";
        }
        $data = json_decode($this->response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = ['data' => $this->response];
        }

        $formattedResponse = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        $result = @file_put_contents($path, $formattedResponse);
        if ($result === false) {
            $error = error_get_last();
            return $error['message'] ?? "Erro desconhecido ao salvar o arquivo.";
        }

        return true;
    }




    /**
     * Método mágico __call.
     * Caso seja chamada uma função inexistente, redireciona para o método request().
     * 
     * Exemplo de uso:
     * <code>
     * $lum->get("https://api.exemplo.com/dados");
     * $lum->post("https://api.exemplo.com/dados", $body);
     * </code>
     *
     * @param string $name      Nome do método chamado.
     * @param array  $arguments Argumentos passados.
     * 
     * @return mixed
     *
     * @throws \BadMethodCallException Se o método HTTP não for suportado.
     * @throws \InvalidArgumentException Se a URL não for informada.
     */
    public function __call(string $name, array $arguments): mixed
    {
        $httpMethod = strtoupper($name);
        $supportedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

        if (!in_array($httpMethod, $supportedMethods)) {
            throw new \BadMethodCallException("O método '$name' não é suportado.");
        }

        if (empty($arguments[0])) {
            throw new \InvalidArgumentException("A URL deve ser informada para o método '$name'.");
        }

        $url  = $arguments[0];
        $body = $arguments[1] ?? "";

        $this->request($url, $httpMethod, $body);
        return $this->getResponse();
    }

    /**
     * Retorna os cabeçalhos da última requisição.
     * @return array
     */
    public function __debugInfo(): array
    {
        // Faz uma cópia dos cabeçalhos para manipulação segura
        $sanitizedHeaders = [];
        foreach ($this->headers as $header) {
            if (stripos($header, "Authorization:") !== false || stripos($header, "X-API-Key:") !== false) {
                $sanitizedHeaders[] = preg_replace('/(:\s*)(.+)/', '$1[REDACTED]', $header);
            } else {
                $sanitizedHeaders[] = $header;
            }
        }

        return [
            "Mode"         => "Lumynus HTTP - Lum",
            "Debug"        => "Enabled",
            "Url"          =>  $this->url ?? "N/A",
            "VerifySSL"    => $this->verifySSL,
            "statusCode"   => $this->statusCode ?? "N/A",
            "error"        => $this->errorRequest ?? "N/A",
            "headers"      => $sanitizedHeaders,
            "lastRequest"  => $this->lastRequest ?? "N/A",
            "executionTime" => isset($this->startTime) ? (time() - $this->startTime) . "s" : "N/A",
            "contentLength" => $this->contentLength
        ];
    }
}
