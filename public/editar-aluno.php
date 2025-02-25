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

    // Recebe o ID do aluno via GET
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("ID do aluno não informado.");
    }

    $id = $_GET['id'];

    // Consulta para obter os dados do aluno
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aluno) {
        die("Aluno não encontrado.");
    }

    // Processa o formulário de edição
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Validação básica dos campos
        if (empty($nome) || empty($email) || empty($rg) || empty($cpf) || empty($telefone) || empty($data_nascimento) || empty($endereco) || empty($sexo) || empty($turno) || empty($curso) || empty($matricula)) {
            die("Todos os campos são obrigatórios.");
        }

        // Atualiza os dados no banco de dados
        $updateStmt = $pdo->prepare("UPDATE alunos SET nome = :nome, email = :email, rg = :rg, cpf = :cpf, telefone = :telefone, data_nascimento = :data_nascimento, endereco = :endereco, sexo = :sexo, turno = :turno, curso = :curso, matricula = :matricula, foto = :foto WHERE id = :id");

        $updateStmt->bindParam(':id', $id);
        $updateStmt->bindParam(':nome', $nome);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':rg', $rg);
        $updateStmt->bindParam(':cpf', $cpf);
        $updateStmt->bindParam(':telefone', $telefone);
        $updateStmt->bindParam(':data_nascimento', $data_nascimento);
        $updateStmt->bindParam(':endereco', $endereco);
        $updateStmt->bindParam(':sexo', $sexo);
        $updateStmt->bindParam(':turno', $turno);
        $updateStmt->bindParam(':curso', $curso);
        $updateStmt->bindParam(':matricula', $matricula);
        $updateStmt->bindParam(':foto', $foto);

        if ($updateStmt->execute()) {
            // Redireciona para a página de listagem com mensagem de sucesso
            header("Location: listaralunos.php?edit=success");
            exit();
        } else {
            die("Erro ao atualizar aluno.");
        }
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
    <title>Editar Aluno - Academia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Inputmask CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
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
        /* Webcam Capture */
        #webcam-container, #captured-photo-container {
            width: 200px;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        #webcam-container video, #captured-photo-container img {
            width: 100%;
            height: 100%;
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
                        <a class="nav-link active" href="cadastro-aluno.php"><i class="fas fa-user-plus me-2"></i>Cadastro de Aluno</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaralunos.php"><i class="fa-solid fa-magnifying-glass"></i> Listar Alunos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro-professor.php"><i class="fas fa-chalkboard-teacher me-2"></i>Cadastro de Professor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listarprofessores.php"><i class="fa-solid fa-magnifying-glass"></i>Listar Professores</a>
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

    <!-- Dashboard Horizontal -->
    <div class="container-fluid mt-3 bg-light p-3 rounded">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start">
                <h2 class="welcome-message m-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            </div>
            <div class="col-md-4 text-center">
                <p class="user-info m-0">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['email']); ?>!</p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <p class="user-info m-0">Tipo de usuário: <?php echo htmlspecialchars($_SESSION['usuario']['tipo']); ?></p>
            </div>
        </div>
    </div>

    <!-- Formulário de Edição de Aluno -->
    <div class="container mt-5">
        <h3 class="text-center mb-4">Editar Aluno</h3>
        <form action="editar-aluno.php?id=<?php echo $aluno['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="rg" class="form-label">RG:</label>
                    <input type="text" class="form-control" id="rg" name="rg" value="<?php echo htmlspecialchars($aluno['rg']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($aluno['cpf']); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($aluno['telefone']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($aluno['data_nascimento']); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="endereco" class="form-label">Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($aluno['endereco']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="sexo" class="form-label">Sexo:</label>
                    <select class="form-select" id="sexo" name="sexo" required>
                        <option value="masculino" <?php echo ($aluno['sexo'] === 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="feminino" <?php echo ($aluno['sexo'] === 'feminino') ? 'selected' : ''; ?>>Feminino</option>
                        <option value="outro" <?php echo ($aluno['sexo'] === 'outro') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="turno" class="form-label">Turno:</label>
                    <select class="form-select" id="turno" name="turno" required>
                        <option value="manha" <?php echo ($aluno['turno'] === 'manha') ? 'selected' : ''; ?>>Manhã</option>
                        <option value="tarde" <?php echo ($aluno['turno'] === 'tarde') ? 'selected' : ''; ?>>Tarde</option>
                        <option value="noite" <?php echo ($aluno['turno'] === 'noite') ? 'selected' : ''; ?>>Noite</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="curso" class="form-label">Curso:</label>
                    <input type="text" class="form-control" id="curso" name="curso" value="<?php echo htmlspecialchars($aluno['curso']); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="matricula" class="form-label">Matrícula:</label>
                    <input type="text" class="form-control" id="matricula" name="matricula" value="<?php echo htmlspecialchars($aluno['matricula']); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="foto" class="form-label">Foto:</label>
                    <div class="d-flex gap-3">
                        <div id="webcam-container">
                            <video id="webcam" autoplay playsinline></video>
                        </div>
                        <div id="captured-photo-container">
                            <img id="captured-photo-preview" src="<?php echo htmlspecialchars($aluno['foto']); ?>" alt="Captured Photo" style="display: block;">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="capturePhoto()">Capturar Foto</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <input type="hidden" id="captured-photo" name="foto" value="<?php echo htmlspecialchars($aluno['foto']); ?>">
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
let webcamStream;

// Inicializa a webcam automaticamente
async function startWebcam() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        document.getElementById('webcam').srcObject = stream;
        webcamStream = stream;
    } catch (error) {
        console.error("Erro ao acessar a webcam:", error);
        alert("Não foi possível acessar a câmera. Verifique as permissões.");
    }
}

// Captura a foto da webcam
function capturePhoto() {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Converte a imagem capturada para base64 e armazena no campo oculto
    const photoData = canvas.toDataURL('image/png');
    document.getElementById('captured-photo').value = photoData;

    // Exibe a imagem capturada no contêiner ao lado
    const previewImage = document.getElementById('captured-photo-preview');
    previewImage.src = photoData;
    previewImage.style.display = 'block';
}

// Aplica máscaras aos campos usando Inputmask
$(document).ready(function () {
    $('#rg').inputmask('999999-9'); // Máscara para RG
    $('#cpf').inputmask('999.999.999-99'); // Máscara para CPF
    $('#telefone').inputmask('(99) 99999-9999'); // Máscara para telefone
    $('#data_nascimento').inputmask('99/99/9999'); // Máscara para data de nascimento
    $('#matricula').inputmask('9999999999'); // Máscara para matrícula (exemplo: 10 dígitos)
});

// Inicia a webcam quando a página carregar
window.onload = startWebcam;
    </script>
</body>
</html>