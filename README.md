# 🌟 Lumynus

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue)](https://www.php.net/)  
[![Packagist Version](https://img.shields.io/packagist/v/lumynus/Lumynus)](https://packagist.org/packages/lumynus/Lumynus)  
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

**Lumynus** é uma biblioteca PHP simples e leve para realizar requisições HTTP de forma descomplicada. Com ela, você pode facilmente enviar e receber dados de APIs, fazer requisições GET, POST, PUT, DELETE e muito mais, com suporte a cabeçalhos personalizados e diferentes tipos de conteúdo (JSON, XML, etc).

---

## 🚀 Instalação

Para instalar a biblioteca **Lumynus**, utilize o **Composer**:

```bash
composer require lumynus/lumynus
```

---


## ⚡ Exemplos de Uso

### 1. Requisição GET

```php 
<?php
require 'vendor/autoload.php';

use Lumynus\HTTP\Simple;

// Exemplo de requisição GET para um endpoint público
$response = Simple::request("https://jsonplaceholder.typicode.com/posts", "GET");
print_r($response);

```

### 2. Requisição POST com JSON

```php
<?php
require 'vendor/autoload.php';

use Lumynus\HTTP\Simple;

// Dados a serem enviados como JSON
$data = [
    "title"  => "Novo Post",
    "body"   => "Conteúdo do post",
    "userId" => 1
];

// Cabeçalho informando que o conteúdo é JSON
$headers = ["Content-Type: application/json"];

// Enviando a requisição POST
$response = Simple::request("https://jsonplaceholder.typicode.com/posts", "POST", $headers, $data);
print_r($response);

```

### 3. Requisição POST com XML

```php
<?php
require 'vendor/autoload.php';

use Lumynus\HTTP\Simple;

// Corpo da requisição em XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<data>
    <name>RioDev</name>
    <email>contato@riodev.com.br</email>
</data>';

// Cabeçalho informando que o conteúdo é XML
$headers = ["Content-Type: application/xml"];

// Enviando a requisição POST
$response = Simple::request("https://example.com/api", "POST", $headers, $xml);
print_r($response);

```

## 📜 Licença

Este projeto está licenciado sob a MIT License.


## 💙 Agradecimentos

Agradecemos a todos os desenvolvedores open-source e à comunidade PHP por tornarem possíveis projetos como este.
Se você gosta do Lumynus, não esqueça de deixar uma estrela ⭐ no repositório!


## ✉️ Contato
Desenvolvedor: Weleny Santos - RioDev

Email: fale@riodev.com.br

Site: riodev.com.br

GitHub: [https://github.com/Lumynus/lumynus]
