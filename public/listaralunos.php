<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
// Conexão com o banco de dados (substitua pelos seus dados)
$host = "localhost";
$dbname = "academia";
$username = "root";
$password = "";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Consulta para listar todos os alunos
    $stmt = $pdo->query("SELECT * FROM alunos ORDER BY id DESC");
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Alunos - Academia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFFFFF;
        }
        .navbar {
            background-color: #000000 !important; /* Cor da barra de menu */
            border: none !important; /* Remove a borda da navbar */
            box-shadow: none !important; /* Remove a sombra */
        }
        .navbar-brand {
            font-weight: bold;
            color: #AD4721 !important; /* Cor do texto do logo */
        }
        .logo {
            max-height: 130px; /* Altura padrão para logos em sites */
            margin-right: 10px; /* Espaçamento entre a logo e o texto */
        }
        .brand-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            
        }
        .brand-text h5:first-child {
            text-align: left; /* Alinha o primeiro texto à esquerda */
            color: white !important; /* Cor do texto "THE BLACK PANTHER TEAM" */
        }
        .brand-text h5:last-child {
            text-align: center; /* Centraliza o segundo texto */
            margin-left: auto; /* Move o texto para o centro */
            margin-right: auto; /* Centraliza horizontalmente */
            color: white !important; /* Cor do texto "ACADEMIA" */
            font-size: 15px;
            font-weight: 600;
        }
        .welcome-message {
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
        }
        .user-info {
            font-size: 1.2rem;
            color: rgb(108, 117, 125);
        }
        .logout-btn {
            margin-top: 2rem;
        }
        /* Estilo para os links do menu */
        .navbar-nav .nav-link {
            color: white !important; /* Cor da letra do menu */
            transition: color 0.3s ease; /* Efeito suave na mudança de cor */
        }
        .navbar-nav .nav-link:hover {
            color: #AD4721 !important; /* Cor da letra ao passar o mouse */
        }
        /* Estilo para o botão "Sair" */
        .navbar-nav .btn-danger {
            background-color: red !important; /* Cor vermelha para o botão "Sair" */
            border-color: red !important;
            color: white !important; /* Garante que o texto do botão seja branco */
            transition: background-color 0.3s ease, border-color 0.3s ease; /* Efeito suave na mudança de cor */
        }
        .navbar-nav .btn-danger:hover {
            background-color: #AB461A !important; /* Cor ao passar o mouse */
            border-color: #AB461A !important;
            color: white !important; /* Mantém o texto branco ao passar o mouse */
        }
        /* Estilo para o ícone do menu hambúrguer */
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E") !important;
        }
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
            }
        }
    </style>
</head>
<body>
       <!-- Barra Superior de Menu -->
       <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../public/img/logo.png" alt="Logo" class="logo">
                <div class="brand-text">
                    <span><h5 class="mb-0">THE BLACK PANTHER TEAM</h5></span>
                    <span><h5 class="mb-0">ACADEMIA</h5></span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-house"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro-aluno.php"><i class="fas fa-user-plus me-2"></i>Cadastro de Aluno</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaralunos.php"><i class="fa-solid fa-magnifying-glass"></i> Listar Alunos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro-professor.php"><i class="fas fa-chalkboard-teacher me-2"></i>Cadastro de Professor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro-professor.php"><i class="fa-solid fa-magnifying-glass"></i>Listar Professor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal de Confirmação de Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirmação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente sair do sistema?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="logout.php" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirmação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente excluir este aluno?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="confirmDeleteButton" href="#" class="btn btn-danger">Confirmar</a>
                </div>
            </div>
        </div>
    </div>
<!-- Dashboard Horizontal -->
<div class="container-fluid mt-3 bg-light p-3 rounded">
    <div class="d-flex justify-content-center align-items-center gap-4">
        <!-- Bem-vindo -->
        <div>
            <p class="user-info m-0" style="color: black;">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['email']); ?>!</p>
        </div>
        <!-- Tipo de Usuário -->
        <div>
            <p class="user-info m-0" style="color: black;">Usuário: <?php echo htmlspecialchars($_SESSION['usuario']['tipo']); ?></p>
        </div>
    </div>
</div>

    <!-- Tabela de Listagem de Alunos -->
    <div class="container table-container">
        <h3 class="text-center mb-4">Lista de Alunos</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Matrícula</th>
                    <th>Data de Inscrição</th>
                    <th>Valor Mensal (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alunos) > 0): ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['id']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['data_inscricao']); ?></td>
                            <td>R$ <?php echo number_format($aluno['valor_mensal'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="visualizar-aluno.php?id=<?php echo $aluno['id']; ?>" class="btn btn-info btn-action" title="Visualizar"><i class="fas fa-eye"></i></a>
                                <a href="editar-aluno.php?id=<?php echo $aluno['id']; ?>" class="btn btn-warning btn-action" title="Editar"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-danger btn-action" title="Excluir" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?php echo $aluno['id']; ?>"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Nenhum aluno cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Manipula o clique no botão "Excluir"
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-danger[data-bs-target="#confirmDeleteModal"]');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            let alunoIdToDelete;
            // Quando o botão "Excluir" é clicado, armazena o ID do aluno
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    alunoIdToDelete = this.getAttribute('data-id');
                });
            });
            // Quando o botão "Confirmar" na modal é clicado, redireciona para a página de exclusão
            confirmDeleteButton.addEventListener('click', function () {
                if (alunoIdToDelete) {
                    window.location.href = `excluir-aluno.php?id=${alunoIdToDelete}`;
                }
            });
        });
    </script>

    
</body>
</html>