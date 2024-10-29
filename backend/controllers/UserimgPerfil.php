<?php
require_once __DIR__ . '/../db/database.php';
session_start();

date_default_timezone_set('America/Sao_Paulo');

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../Views/login.html');
    exit();
}

// Verificar se um arquivo foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagem-perfil'])) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $id_user = $_SESSION['id_user'];
    
    // Configurações para o upload da imagem
    $uploadDir = '../../public/img/perfil/'; // Alterado para uma pasta mais apropriada
    $defaultImage = '../public/img/usuario.png'; // Imagem padrão
    
    // Criar diretório se não existir
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Processar o upload da imagem
    if ($_FILES['imagem-perfil']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['imagem-perfil']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

        if (in_array($extensao, $extensoesPermitidas)) {
            // Gerar nome único para a imagem
            $imagemNome = 'perfil_' . $id_user . '_' . date('d-m-Y_H-i-s') . '.' . $extensao;
            $imagemCaminho = $uploadDir . $imagemNome;

            // Mover a imagem para o diretório de upload
            if (move_uploaded_file($_FILES['imagem-perfil']['tmp_name'], $imagemCaminho)) {
                // Caminho relativo para salvar no banco de dados
                $imagemPerfil = 'public/img/perfil/' . $imagemNome;

                // Atualizar o caminho da imagem no banco de dados
                $stmt = $conn->prepare("UPDATE user_db1 SET imagem_perfil = ? WHERE id_user = ?");
                $stmt->bind_param("si", $imagemPerfil, $id_user);
                
                if ($stmt->execute()) {
                    // Redirecionar de volta para a página do usuário com mensagem de sucesso
                    header('Location: ../../Views/usuario.php?success=1');
                    exit();
                } else {
                    header('Location: ../../Views/usuario.php?error=db');
                    exit();
                }
            } else {
                header('Location: ../../Views/usuario.php?error=upload');
                exit();
            }
        } else {
            header('Location: ../../Views/usuario.php?error=format');
            exit();
        }
    } else {
        header('Location: ../../Views/usuario.php?error=file');
        exit();
    }
} else {
    header('Location: ../../Views/usuario.php');
    exit();
}
?>