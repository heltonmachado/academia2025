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

    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $rg = trim($_POST['rg']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data_nascimento'];
    $endereco = trim($_POST['endereco']);
    $sexo = $_POST['sexo'];
    $turno = $_POST['turno'];
    $curso = trim($_POST['curso']);
    $matricula = trim($_POST['matricula']);
    $foto = $_POST['foto']; // Foto capturada pela webcam (base64)
    $data_inscricao = $_POST['data_inscricao']; // Nova campo
    $valor_mensal = $_POST['valor_mensal']; // Novo campo

    // Validação básica dos campos
    if (empty($nome) || empty($email) || empty($rg) || empty($cpf) || empty($telefone) || empty($data_nascimento) || empty($endereco) || empty($sexo) || empty($turno) || empty($curso) || empty($matricula) || empty($data_inscricao) || empty($valor_mensal)) {
        die("Todos os campos são obrigatórios.");
    }

    // Insere os dados no banco de dados
    $stmt = $pdo->prepare("INSERT INTO alunos (nome, email, rg, cpf, telefone, data_nascimento, endereco, sexo, turno, curso, matricula, foto, data_inscricao, valor_mensal) VALUES (:nome, :email, :rg, :cpf, :telefone, :data_nascimento, :endereco, :sexo, :turno, :curso, :matricula, :foto, :data_inscricao, :valor_mensal)");

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':rg', $rg);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':sexo', $sexo);
    $stmt->bindParam(':turno', $turno);
    $stmt->bindParam(':curso', $curso);
    $stmt->bindParam(':matricula', $matricula);
    $stmt->bindParam(':foto', $foto);
    $stmt->bindParam(':data_inscricao', $data_inscricao); // Novo campo
    $stmt->bindParam(':valor_mensal', $valor_mensal); // Novo campo

    if ($stmt->execute()) {
        // Redireciona para a página de sucesso
        header("Location: listaralunos.php?cadastro=success");
        exit();
    } else {
        die("Erro ao cadastrar aluno.");
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>