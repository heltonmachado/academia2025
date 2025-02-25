<?php
session_start();
require_once __DIR__ . '/../src/Database.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Corrigido para usar $_POST['email']
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // Tipo selecionado pelo usuário

    // Validação da senha
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $senha)) {
        $erro = 'A senha deve conter pelo menos 8 caracteres, incluindo uma letra minúscula, uma maiúscula, um número e um caractere especial (@$!%*?&).';
    } else {
        // Conexão com o banco de dados
        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Verifica se o usuário já existe
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $erro = 'Usuário já cadastrado.';
            } else {
                // Insere o novo usuário
                $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO usuarios (email, senha, tipo) VALUES (:email, :senha, :tipo)");
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':senha', $senhaHash, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $sucesso = 'Cadastro realizado com sucesso! Faça login.';
                } else {
                    $erro = 'Erro ao cadastrar usuário.';
                }
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao acessar o banco de dados. Tente novamente mais tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Academia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Fundo claro */
        }
        .container {
            margin-top: 10vh; /* Centraliza verticalmente */
        }
        .form-control:focus {
            border-color: #AD4721; /* Cor da borda ao focar */
            box-shadow: 0 0 0 0.25rem rgba(173, 71, 33, 0.25); /* Sombra suave */
        }
        .btn-primary {
            background-color: #AD4721; /* Cor principal do botão */
            border-color: #AD4721;
        }
        .btn-primary:hover {
            background-color: #8B3D1C; /* Cor ao passar o mouse */
            border-color: #8B3D1C;
        }
        .alert-danger {
            background-color: #f8d7da; /* Fundo vermelho claro */
            border-color: #f5c6cb;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda; /* Fundo verde claro */
            border-color: #c3e6cb;
            color: #155724;
        }
        .text-decoration-none {
            color: #AD4721; /* Cor dos links */
        }
        .text-decoration-none:hover {
            color: #8B3D1C; /* Cor ao passar o mouse */
        }
        .form-text {
            font-size: 0.875rem; /* Tamanho menor para instruções */
            color: #6c757d; /* Cinza padrão */
        }
        .form-select:focus {
            border-color: #AD4721; /* Cor da borda ao focar */
            box-shadow: 0 0 0 0.25rem rgba(173, 71, 33, 0.25); /* Sombra suave */
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .translate-middle-y {
            transform: translateY(-50%);
        }
        .fa-eye,
        .fa-eye-slash {
            font-size: 1.2rem;
            color: #6c757d; /* Cor padrão do ícone */
        }
        .fa-eye:hover,
        .fa-eye-slash:hover {
            color: #AD4721; /* Cor ao passar o mouse */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 bg-white p-4 rounded shadow">
                <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Cadastro</h2>

                <!-- Exibe mensagem de erro, se houver -->
                <?php if ($erro): ?>
                    <div id="mensagemErro" class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <!-- Exibe mensagem de sucesso, se houver -->
                <?php if ($sucesso): ?>
                    <div id="mensagemSucesso" class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($sucesso); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de cadastro -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="senha" class="form-label"><i class="fas fa-lock me-2"></i>Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                        <i class="fas fa-eye position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer" 
                           id="toggleSenha" style="cursor: pointer;"></i>
                        <small class="form-text text-muted">A senha deve conter pelo menos 8 caracteres, incluindo uma letra minúscula, uma maiúscula, um número e um caractere especial (@$!%*?&).</small>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label"><i class="fas fa-user-tag me-2"></i>Tipo de Usuário:</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="aluno">Aluno</option>
                            <option value="professor">Professor</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-plus me-2"></i>Cadastrar</button>
                </form>

                <!-- Link para voltar ao login -->
                <div class="mt-3 text-center">
                    <a href="login.php" class="text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Voltar ao Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, caso precise de funcionalidades JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para esconder mensagens após 3 segundos -->
    <script>
        // Função para esconder a mensagem de erro ou sucesso
        function esconderMensagem(id) {
            const mensagem = document.getElementById(id);
            if (mensagem) {
                setTimeout(() => {
                    mensagem.style.display = 'none'; // Esconde a mensagem
                }, 3000); // 3 segundos
            }
        }

        // Esconde as mensagens, se existirem
        esconderMensagem('mensagemErro');
        esconderMensagem('mensagemSucesso');

        // Script para mostrar/ocultar senha
        const toggleSenha = document.getElementById('toggleSenha');
        const senhaInput = document.getElementById('senha');
        toggleSenha.addEventListener('click', () => {
            const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            senhaInput.setAttribute('type', tipo);
            toggleSenha.classList.toggle('fa-eye');
            toggleSenha.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>