<?php
// Conexão com o banco de dados (substitua pelos seus dados)
$host = "localhost";
$dbname = "academia";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter a maior matrícula atual
    $stmt = $pdo->query("SELECT MAX(matricula) AS ultima_matricula FROM alunos");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extrair o número da última matrícula
    if ($row['ultima_matricula']) {
        $numero = intval(substr($row['ultima_matricula'], 1)); // Remove o "M" e converte para inteiro
    } else {
        $numero = 0; // Se não houver matrículas, começa do zero
    }

    // Incrementar o número e formatar como M00001, M00002, etc.
    $proxima_matricula = "M" . str_pad($numero + 1, 5, "0", STR_PAD_LEFT);

    echo $proxima_matricula; // Retorna a próxima matrícula
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>