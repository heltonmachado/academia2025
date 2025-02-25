<?php
session_start();
require_once __DIR__ . '/../src/Database.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Remove espaços em branco extras

    // Conexão com o banco de dados
    try {
        $db = new Database();
        $conn = $db->getConnection();

        // Verifica se o e-mail existe no banco de dados
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Simulação de envio de e-mail (substitua por lógica real)
            $sucesso = "Um e-mail com instruções para redefinir sua senha foi enviado para o endereço fornecido.";
            
            // Aqui você pode gerar um token único e salvar no banco de dados para validação posterior
            // Em seguida, envie o link de redefinição de senha para o e-mail do usuário
        } else {
            $erro = "E-mail não encontrado.";
        }
    } catch (PDOException $e) {
        $erro = "Erro ao acessar o banco de dados. Tente novamente mais tarde.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha - Academia</title>
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
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 bg-white p-4 rounded shadow">
                <h2 class="text-center mb-4"><i class="fas fa-lock me-2"></i>Esqueceu a Senha?</h2>

                <!-- Exibe mensagem de erro, se houver -->
                <?php if ($erro): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <!-- Exibe mensagem de sucesso, se houver -->
                <?php if ($sucesso): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($sucesso); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de recuperação de senha -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small class="form-text text-muted">Insira o e-mail associado à sua conta.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Enviar</button>
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
</body>
</html>