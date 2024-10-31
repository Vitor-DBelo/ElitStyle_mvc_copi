<?php
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../models/AuthController.php';
require_once __DIR__ . '/../models/SessionController.php';
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
    $uploadDir = '../../public/img/perfil/';
    
    // Criar diretório se não existir
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Processar o upload da imagem
    if ($_FILES['imagem-perfil']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem-perfil']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

        if (in_array($extensao, $extensoesPermitidas)) {
            // Gerar um nome único para a imagem
            $imagemNome = 'perfil_' . $id_user . '_' . date('d-m-Y_H-i-s') . '.' . $extensao;
            $imagemCaminho = $uploadDir . $imagemNome;

            // Mover a imagem para o diretório de upload
            if (move_uploaded_file($_FILES['imagem-perfil']['tmp_name'], $imagemCaminho)) {
                // Caminho relativo para salvar no banco de dados
                $imagemPerfil = 'public/img/perfil/' . $imagemNome;

                // (Opcional) Remover imagem anterior se necessário
                $stmt = $conn->prepare("SELECT imagem_perfil FROM user_db1 WHERE id_user = ?");
                $stmt->bind_param("i", $id_user);
                $stmt->execute();
                $stmt->bind_result($imagemAntiga);
                $stmt->fetch();
                $stmt->close();

                if ($imagemAntiga && file_exists('../../' . $imagemAntiga)) {
                    unlink('../../' . $imagemAntiga); // Remove a imagem antiga
                }

                // Atualizar o caminho da imagem no banco de dados
                $stmt = $conn->prepare("UPDATE user_db1 SET imagem_perfil = ? WHERE id_user = ?");
                $stmt->bind_param("si", $imagemPerfil, $id_user);
                
                if ($stmt->execute()) {
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
        // Fornecer feedback de erro baseado no tipo de erro
        switch ($_FILES['imagem-perfil']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                header('Location: ../../Views/usuario.php?error=size');
                break;
            case UPLOAD_ERR_PARTIAL:
                header('Location: ../../Views/usuario.php?error=partial');
                break;
            case UPLOAD_ERR_NO_FILE:
                header('Location: ../../Views/usuario.php?error=nofile');
                break;
            default:
                header('Location: ../../Views/usuario.php?error=unknown');
                break;
        }
        exit();
    }
} else {
    header('Location: ../../Views/usuario.php');
    exit();
}
?>
