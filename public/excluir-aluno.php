<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
// Conexão com o banco de dados
$host = "localhost";
$dbname = "academia";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebe o ID do aluno via GET
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("ID do aluno não informado.");
    }

    $id = $_GET['id'];

    // Exclui o aluno
    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Redireciona para a página de listagem com mensagem de sucesso
        header("Location: listaralunos.php?delete=success");
        exit();
    } else {
        die("Erro ao excluir aluno.");
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>