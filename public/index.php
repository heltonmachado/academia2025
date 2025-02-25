<?php
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['email'])) {
    // Se o usuário estiver logado, redireciona para o dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>