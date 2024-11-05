<?php
session_start();
require_once '../backend/controllers/BuscarEnderecoController.php';

// Debug
var_dump($_SESSION);

if (!isset($_SESSION['id_user'])) {
    header("Location: login.html?message=nao_logado");
    exit();
}

$endereco = buscarEndereco($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Endereço</title>
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/css/crudcadastro.css">
</head>
<body>
    <!-- Debug -->
    <div style="display:none;">
        <?php echo "ID do usuário na sessão: " . $_SESSION['id_user']; ?>
    </div>

    <form id="enderecoForm" action="../backend/models/CadastroEndereco.php" method="POST">
        <h1>Formulário de Endereço</h1>
        
        <label for="numero">Número:</label>
        <input type="text" id="numero" name="numero" required>

        <label for="rua">Rua:</label>
        <input type="text" id="rua" name="rua" required>

        <label for="bairro">Bairro:</label>
        <input type="text" id="bairro" name="bairro" required>

        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" required pattern="\d{5}-\d{3}" 
               placeholder="12345-678" >

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>

        <input type="submit" value="Cadastrar Endereço">
    </form>
</body>
</html> 