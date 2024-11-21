<?php
session_start();
session_unset(); // Limpa todos os dados da sessão
session_destroy(); // Destrói a sessão

// Após o logout, redireciona para a página de login
header("Location: ../../Views/index.html");
exit();
?>
