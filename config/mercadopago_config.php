<?php
// Verifica se o arquivo autoload.php existe
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Erro: O arquivo autoload.php não foi encontrado em: " . $autoloadPath . ". Por favor, execute 'composer install' na raiz do projeto.");
}

// Carrega o autoloader do Composer
require_once $autoloadPath;

// Verifica se a classe MercadoPago\SDK existe
if (!class_exists('MercadoPago\SDK')) {
    die("Erro: A classe MercadoPago\SDK não foi encontrada. Verifique se o SDK do Mercado Pago está instalado corretamente.");
}

// Configurações do Mercado Pago
define('MP_ACCESS_TOKEN', 'SEU_ACCESS_TOKEN_AQUI');
define('MP_PUBLIC_KEY', 'SUA_PUBLIC_KEY_AQUI');

// Inicializa o SDK do Mercado Pago
MercadoPago\SDK::setAccessToken(MP_ACCESS_TOKEN);
