<?php

namespace Lumynus\Regex;


class Regex
{
    /**
     * Testa se um valor corresponde a uma expressão regular.
     *
     * @param string $regex A expressão regular a ser testada.
     * @param string $value O valor a ser testado.
     * @return bool Retorna true se houver correspondência, false caso contrário.
     */
    public static function tester(string $regex, string $value = ""): bool
    {
        return @preg_match($regex, $value) === 1;
    }

    /**
     * Refina uma string removendo tudo que não corresponde ao padrão especificado.
     * Ou seja, remove caracteres que não são permitidos segundo a regex de validação.
     *
     * @param string $regex A expressão regular de validação (por exemplo, "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/").
     * @param string $value A string a ser refinada.
     * @return string A string filtrada, contendo apenas os caracteres permitidos.
     */
    public static function refine(string $regex, string $value = ""): string
    {
        // Converte a regex de validação para uma regex de limpeza que remova caracteres não permitidos.
        $pattern = self::invertRegex($regex);
        return preg_replace($pattern, "", $value) ?? "";
    }


    /**
     * Inverte uma expressão regular de validação para gerar uma expressão que capture
     * os caracteres NÃO permitidos.
     *
     * Para regex simples no formato ancorado (ex: "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/"),
     * extrai o conjunto de caracteres permitido e retorna uma regex que remove os demais.
     *
     * Se não encontrar um conjunto explícito (com colchetes), tenta detectar tokens comuns como \d.
     *
     * @param string $regex A expressão regular de validação.
     * @return string A expressão regular invertida para remover caracteres não permitidos.
     */
    private static function invertRegex(string $regex): string
    {
        // Assume que o delimitador é o primeiro caractere (geralmente "/")
        $delimiter = $regex[0];
        $lastDelimiterPos = strrpos($regex, $delimiter);
        if ($lastDelimiterPos === false) {
            return $regex;
        }
        // Separa o corpo da regex (entre os delimitadores) e os modificadores
        $patternBody = substr($regex, 1, $lastDelimiterPos - 1);
        $modifiers = substr($regex, $lastDelimiterPos + 1);

        // Remove âncoras de início (^) e fim ($), se existirem
        if (strpos($patternBody, '^') === 0) {
            $patternBody = substr($patternBody, 1);
        }
        if (substr($patternBody, -1) === '$') {
            $patternBody = substr($patternBody, 0, -1);
        }
        // Remover quantificadores finais como +, *, ? (não afeta a extração do conjunto)
        $patternBody = rtrim($patternBody, '+*?');

        // Procura o primeiro grupo de colchetes
        $start = strpos($patternBody, '[');
        $end = strpos($patternBody, ']', $start);
        if ($start !== false && $end !== false) {
            $allowed = substr($patternBody, $start + 1, $end - $start - 1);
        } else {
            // Se não encontrou colchetes, tenta detectar tokens comuns
            $allowed = "";
            // Se encontrar \d, considera dígitos de 0 a 9
            if (preg_match('/\\\\d/', $patternBody)) {
                $allowed .= "0-9";
            }
            // Se encontrar \w, permite letras, dígitos e underline
            if (preg_match('/\\\\w/', $patternBody)) {
                $allowed .= "A-Za-z0-9_";
            }
            // Se encontrar \s, permite espaços (note que \s já é representado na classe de caracteres)
            if (preg_match('/\\\\s/', $patternBody)) {
                $allowed .= "\s";
            }
            // Se o padrão começar com "-?" significa que o hífen é permitido (opcionalmente no início)
            if (strpos($patternBody, '-?') === 0 || strpos($patternBody, '^-?') === 0) {
                $allowed = "-" . $allowed;
            }
        }

        // Se nada foi detectado, cria um padrão que não remove nada
        if ($allowed === "") {
            // Padrão que nunca casa (removeria nada)
            return $delimiter . "a^" . $delimiter . $modifiers;
        }

        // Retorna a regex para remover tudo que NÃO esteja no conjunto permitido.
        // Forçamos o modificador 'u' para Unicode.
        return $delimiter . "[^" . $allowed . "]" . $delimiter . "u";
    }
}

