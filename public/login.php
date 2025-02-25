<?php
session_start();
require_once __DIR__ . '/../src/Database.php';

// Variável para armazenar mensagens de erro
$erro = '';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $email = trim($_POST['email']); // Remove espaços em branco extras
    $senha = $_POST['senha'];

    // Conexão com o banco de dados
    try {
        $db = new Database();
        $conn = $db->getConnection();

        // Prepara e executa a consulta para verificar o usuário
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($user && password_verify($senha, $user['senha'])) {
            // Login bem-sucedido: armazena os dados do usuário na sessão
            $_SESSION['usuario'] = $user;
            header('Location: dashboard.php'); // Redireciona para o painel
            exit();
        } else {
            $erro = 'E-mail ou senha inválidos.';
        }
    } catch (PDOException $e) {
        // Em caso de erro no banco de dados, exibe uma mensagem genérica
        $erro = 'Erro ao acessar o banco de dados. Tente novamente mais tarde.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academia</title>
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
        .text-decoration-none {
            color: #AD4721; /* Cor dos links */
        }
        .text-decoration-none:hover {
            color: #8B3D1C; /* Cor ao passar o mouse */
        }
        .position-relative {
            position: relative;
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
                <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Login</h2>

                <!-- Exibe mensagem de erro, se houver -->
                <?php if ($erro): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de login -->
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
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Entrar</button>
                </form>

                <!-- Links para cadastro e recuperação de senha -->
                <div class="mt-3 text-center">
                    <div>
                        <a href="cadastro.php" class="text-decoration-none"><i class="fas fa-user-plus me-2"></i>Criar conta</a>
                    </div>
                    <div>
                        <a href="esqueceu-senha.php" class="text-decoration-none"><i class="fa-solid fa-lock me-2"></i>Esqueceu a senha?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, caso precise de funcionalidades JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para mostrar/ocultar senha -->
    <script>
        const toggleSenha = document.getElementById('toggleSenha');
        const senhaInput = document.getElementById('senha');

        // Alterna entre mostrar e ocultar a senha
        toggleSenha.addEventListener('click', () => {
            const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            senhaInput.setAttribute('type', tipo);
            toggleSenha.classList.toggle('fa-eye');
            toggleSenha.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>