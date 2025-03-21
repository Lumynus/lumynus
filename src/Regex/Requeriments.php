<?php

namespace Lumynus\Regex;

/**
 * Classe com expressões regulares para validar diferentes tipos de dados comuns.
 */
class Requeriments
{
    // ==========================
    // 📌 Validações de Texto e Caracteres
    // ==========================

    /**
     * Expressão para validar texto puro, apenas letras e espaços.
     * Não permite números, caracteres especiais ou símbolos.
     * Exemplo válido: "Apenas Texto", "Nome Completo"
     */
    public const TEXT_ONLY = "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/";

    /**
     * Expressão para validar nomes completos.
     * Aceita letras, espaços e acentos, com no mínimo 2 palavras.
     * Exemplo válido: "João da Silva", "Maria Oliveira Souza"
     */
    public const NAME = "/^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ]+)+$/";

    /**
     * Expressão para validar palavras únicas (sem espaços).
     * Exemplo válido: "Palavra", "TextoSimples"
     */
    public const SINGLE_WORD = "/^[A-Za-zÀ-ÖØ-öø-ÿ]+$/";

    /**
     * Expressão para validar parágrafos (permitindo letras, números, espaços e pontuação).
     * Exemplo válido: "Este é um parágrafo. Ele tem frases!"
     */
    public const PARAGRAPH = "/^[\wÀ-ÖØ-öø-ÿ\s.,;!?\"'()]+$/";

    /**
     * Expressão para validar apenas letras (sem espaços, números ou caracteres especiais).
     * Exemplo válido: "Palavra", "TextoSimples"
     */
    public const LETTERS_ONLY = "/^[A-Za-zÀ-ÖØ-öø-ÿ]+$/";

    /**
     * Expressão para validar palavras separadas por espaços (sem números ou caracteres especiais).
     * Exemplo válido: "Apenas Texto", "Nome Completo"
     */
    public const WORDS_WITH_SPACES = "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/";

    /**
     * Expressão para validar texto alfanumérico (letras e números, sem caracteres especiais).
     * Exemplo válido: "Texto123", "Nome2024"
     */
    public const ALPHANUMERIC = "/^[A-Za-z0-9À-ÖØ-öø-ÿ]+$/";

    /**
     * Expressão para validar strings que contenham SOMENTE espaços em branco.
     * Exemplo válido: "   " (espaços)
     */
    public const WHITESPACE_ONLY = "/^\s+$/";

    // ==========================
    // 📌 Validações Numéricas
    // ==========================

    /**
     * Expressão para validar inteiros positivos.
     * Exemplo válido: "123", "4567"
     */
    public const WHOLE = "/^\d+$/";

    /**
     * Expressão para validar números inteiros positivos ou negativos.
     * Exemplo válido: "-123", "0", "4567"
     */
    public const INT = "/^-?\d+$/";

    /**
     * Expressão para validar números decimais positivos ou negativos.
     * Exemplo válido: "-12.34", "0.5", "100.0"
     */
    public const FLOAT = "/^-?\d+(\.\d+)?$/";

    /**
     * Expressão para números em notação científica.
     * Exemplo válido: "1.23e4", "-5E-10"
     */
    public const SCI = "/^-?\d+(\.\d+)?([eE][-+]?\d+)?$/";

    /**
     * Expressão para validar números binários (0 e 1).
     * Exemplo válido: "101010", "111000"
     */
    public const BINARY = "/^[01]+$/";

    /**
     * Expressão para validar números hexadecimais.
     * Exemplo válido: "1A3F", "FF00CC"
     */
    public const HEXADECIMAL = "/^[A-Fa-f0-9]+$/";

    /**
     * Expressão para validar números de cartão de crédito (16 dígitos).
     * Exemplo válido: "4111111111111111"
     */
    public const CREDIT_CARD = "/^\d{16}$/";

    /**
     * Expressão para validar coordenadas geográficas (latitude e longitude).
     * Exemplo válido: "-23.550520, -46.633308"
     */
    public const COORDINATES = "/^-?([1-8]?[0-9]|90)\.\d{1,6},\s*-?(180|1[0-7][0-9]|[1-9]?[0-9])\.\d{1,6}$/";


    // ==========================
    // 📌 Validações de Documentos e Códigos
    // ==========================

    /**
     * Expressão para validar CPF.
     * Exemplo válido: "123.456.789-09", "12345678909"
     */
    public const CPF = "/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/";

    /**
     * Expressão para validar CNPJ.
     * Exemplo válido: "12.345.678/0001-95", "12345678000195"
     */
    public const CNPJ = "/^\d{2}\.?\d{3}\.?\d{3}\/?\d{4}-?\d{2}$/";

    /**
     * Expressão para validar CEP (código postal brasileiro).
     * Exemplo válido: "12345-678", "01001000"
     */
    public const CEP = "/^\d{5}-?\d{3}$/";

    /**
     * Expressão para validar placas de veículos brasileiros (padrão Mercosul e antigo).
     * Exemplo válido: "AAA-1234", "BRA1D23"
     */
    public const VEHICLE_PLATE = "/^[A-Z]{3}-?\d{4}$|^[A-Z]{3}\d[A-Z]\d{2}$/";

    /**
     * Expressão para validar telefones brasileiros (com ou sem DDD).
     * Aceita formatos como: (11) 91234-5678, 11912345678
     * Exemplo válido: "(11) 91234-5678", "11912345678"
     */
    public const PHONE = "/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/";

    // ==========================
    // 📌 Validações de Identificadores e Códigos
    // ==========================

    /**
     * Expressão para validar códigos de barras (EAN-13, EAN-8, UPC).
     * Exemplo válido: "1234567890123"
     */
    public const BARCODE = "/^\d{8,13}$/";

    /**
     * Expressão para validar códigos de produto SKU (alfanumérico, entre 3 e 20 caracteres).
     * Exemplo válido: "SKU-12345", "XYZ2024"
     */
    public const SKU = "/^[A-Za-z0-9\-_]{3,20}$/";

    // ==========================
    // 📌 Validações de Arquivos e Extensões
    // ==========================

    /**
     * Expressão para validar extensões de arquivos (imagens, documentos, áudio, vídeo).
     * Exemplo válido: "foto.jpg", "documento.pdf"
     */
    public const FILE_EXTENSION = "/^.*\.(jpg|jpeg|png|gif|bmp|pdf|doc|docx|xls|xlsx|ppt|pptx|txt|zip|rar|mp3|mp4|avi|mkv)$/i";

    /**
     * Expressão para validar nomes de arquivos seguros (sem caracteres inválidos para sistemas de arquivos).
     * Exemplo válido: "meuarquivo.txt", "foto_perfil.png"
     */
    public const SAFE_FILENAME = "/^[A-Za-z0-9\-_]+\.[A-Za-z0-9]+$/";

    // ==========================
    // 📌 Validações de Internet e Rede
    // ==========================

    /**
     * Expressão para validar e-mails.
     * Exemplo válido: "exemplo@email.com"
     */
    public const EMAIL = "/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/";

    /**
     * Expressão para validar URLs (HTTP, HTTPS).
     * Exemplo válido: "https://www.google.com"
     */
    public const URL = "/^(https?:\/\/)?([\w\d-]+\.)+[\w]{2,}(\/[\w\d#?&=]*)?$/";

    /**
     * Expressão para validar endereços IPv4.
     * Exemplo válido: "192.168.1.1"
     */
    public const IPV4 = "/^(\d{1,3}\.){3}\d{1,3}$/";

    /**
     * Expressão para validar endereços IPv6.
     * Exemplo válido: "2001:0db8:85a3:0000:0000:8a2e:0370:7334"
     */
    public const IPV6 = "/^([a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}$/";

    // ==========================
    // 📌 Validações de Senhas e Segurança
    // ==========================

    /**
     * Expressão para validar senhas seguras (mínimo 8 caracteres, pelo menos 1 letra, 1 número e 1 caractere especial).
     * Exemplo válido: "Senha@123"
     */
    public const SECURE_PASSWORD = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/";

    /**
     * Expressão para validar senhas apenas numéricas (4 a 6 dígitos, como PINs).
     * Exemplo válido: "1234", "987654"
     */
    public const NUMERIC_PASSWORD = "/^\d{4,6}$/";

    /**
     * Expressão para validar senhas muito fortes (mínimo 12 caracteres, pelo menos 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial).
     * Exemplo válido: "Forte@Senha123"
     */
    public const VERY_STRONG_PASSWORD = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/";

    /**
     * Expressão para validar tokens seguros (32 a 128 caracteres alfanuméricos e símbolos seguros).
     * Exemplo válido: "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
     */
    public const SECURE_TOKEN = "/^[A-Za-z0-9\-_]{32,128}$/";

    // ==========================
    // 📌 Validações de Datas e Horas
    // ==========================

    /**
     * Expressão para validar datas no formato brasileiro (DD/MM/AAAA).
     * Exemplo válido: "25/12/2023"
     */
    public const DATE_BR = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/";

    /**
     * Expressão para validar datas no formato ISO (AAAA-MM-DD).
     * Exemplo válido: "2023-12-25"
     */
    public const DATE_ISO = "/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";

    /**
     * Expressão para validar horários no formato HH:MM ou HH:MM:SS.
     * Exemplo válido: "14:30", "23:59:59"
     */
    public const TIME = "/^(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/";

    /**
     * Expressão para validar timestamps UNIX (somente números, representando segundos desde 1970).
     * Exemplo válido: "1618579200"
     */
    public const UNIX_TIMESTAMP = "/^\d{10}$/";

    /**
     * Expressão para validar horários em formato 12h (com AM/PM).
     * Exemplo válido: "02:30 PM"
     */
    public const TIME_12H = "/^(0[1-9]|1[0-2]):[0-5]\d\s?(AM|PM)$/i";

    // ==========================
    // 📌 Validações de Redes Sociais e Usernames
    // ==========================

    /**
     * Expressão para validar usernames do Twitter (X) e Instagram.
     * Exemplo válido: "@usuario_123"
     */
    public const SOCIAL_USERNAME = "/^@[A-Za-z0-9_]{3,15}$/";

    /**
     * Expressão para validar links de redes sociais (Facebook, Twitter, Instagram, LinkedIn).
     * Exemplo válido: "https://www.instagram.com/usuario"
     */
    public const SOCIAL_LINK = "/^(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin)\.com\/[A-Za-z0-9_.]+$/";

    // ==========================
    // 📌 Validações de Cores e Estilos
    // ==========================

    /**
     * Expressão para validar cores em hexadecimal.
     * Exemplo válido: "#FF5733", "#ABC"
     */
    public const HEX_COLOR = "/^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/";

    /**
     * Expressão para validar unidades de medida CSS (px, em, rem, %).
     * Exemplo válido: "12px", "2em", "100%"
     */
    public const CSS_UNIT = "/^\d+(px|em|rem|%)$/";

    // ==========================
    // 📌 Validações de Moeda e Valores Monetários
    // ==========================

    /**
     * Expressão para validar valores monetários (com ou sem centavos).
     * Exemplo válido: "R$ 1.234,56", "$100.00", "€50,00"
     */
    public const CURRENCY = "/^(\$|€|R\$)?\s?\d{1,3}(\.\d{3})*,?\d{0,2}$/";

    /**
     * Expressão para validar Bitcoin e criptomoedas (endereços em formato alfanumérico).
     * Exemplo válido: "1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa"
     */
    public const CRYPTO_ADDRESS = "/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/";
}





?>