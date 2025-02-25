<?php
session_start();
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
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$aluno) {
        die("Aluno não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Aluno - Academia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .photo-container {
            width: 200px;
            height: 200px;
            border: 2px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .photo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Detalhes do Aluno</h3>
        <div class="table-container">
            <!-- Foto do Aluno -->
            <div class="photo-container text-center mb-4">
                <img src="<?php echo htmlspecialchars($aluno['foto']); ?>" alt="Foto do Aluno">
            </div>
            <!-- Tabela com os Detalhes do Aluno -->
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Nome:</th>
                        <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email:</th>
                        <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">RG:</th>
                        <td><?php echo htmlspecialchars($aluno['rg']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">CPF:</th>
                        <td><?php echo htmlspecialchars($aluno['cpf']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Telefone:</th>
                        <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Data de Nascimento:</th>
                        <td><?php echo htmlspecialchars($aluno['data_nascimento']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Endereço:</th>
                        <td><?php echo htmlspecialchars($aluno['endereco']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Sexo:</th>
                        <td><?php echo htmlspecialchars($aluno['sexo']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Turno:</th>
                        <td><?php echo htmlspecialchars($aluno['turno']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Curso:</th>
                        <td><?php echo htmlspecialchars($aluno['curso']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Matrícula:</th>
                        <td><?php echo htmlspecialchars($aluno['matricula']); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="listaralunos.php" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
</body>
</html>