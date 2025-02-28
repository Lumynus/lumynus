<?php

use PHPUnit\Framework\TestCase;
use Luminus\Simple;

class SimpleTest extends TestCase
{
    public function testUrlInvalida()
    {
        $resultado = Simple::request("invalid-url", "GET", [], []);
        $this->assertEquals(400, $resultado["status"]);
        $this->assertStringContainsString("A Url enviada não corresponde ao padrão", $resultado["response"]);
    }

    public function testMetodoInvalido()
    {
        $resultado = Simple::request("https://example.com", "INVALIDO", [], []);
        $this->assertEquals(400, $resultado["status"]);
        $this->assertStringContainsString("Método não permitido", $resultado["response"]);
    }
}
