<?php
// Caminho absoluto para o autoload.php
$autoloadPath = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    die("Erro: O arquivo autoload.php não foi encontrado em: " . $autoloadPath);
}

require_once $autoloadPath;

use MercadoPago\SDK;

if (class_exists('MercadoPago\SDK')) {
    echo "Classe SDK do Mercado Pago carregada com sucesso!";
} else {
    die("Erro: A classe MercadoPago\\SDK não foi encontrada.");
}

// Configura as credenciais
SDK::setAccessToken("TEST-e8c9e4e7-bd63-4dcb-bb42-4c9c9cbc33a");

// Aqui você pode adicionar outras configurações ou funções relacionadas ao Mercado Pago
?>
