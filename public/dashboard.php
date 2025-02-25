<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Academia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFFFFF;
          /* overflow-y: hidden; /* Remove a barra vertical */
            overflow-x: auto; /* Mantém a barra horizontal, se necessário */
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
            font-size: 15px;
            font-weight: 600;
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
            color: rgb(253, 253, 253);
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

        /* Estilo para a faixa abaixo do dashboard */
        .message-banner {
            background-color: #AD3F15; /* Cor da faixa */
            color: white; /* Cor do texto */
            text-align: center; /* Centraliza o texto */
            padding: 1rem; /* Espaçamento interno */
            margin-top: 1rem; /* Espaçamento acima da faixa */
            font-size: 1.2rem; /* Tamanho da fonte */
            font-weight: bold; /* Negrito */
            border-radius: 5px; /* Bordas arredondadas */
            
        }

        /* Estilo personalizado para o Carousel */
        .carousel-item img {
            height: 400px; /* Altura fixa para as imagens */
            object-fit: cover; /* Ajusta a imagem para cobrir o espaço */
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

    <!-- Faixa de Mensagem -->
    <div class="container mt-3">
        <div class="message-banner">
            Aqui você encontra tudo para alcançar seus objetivos.
        </div>
    </div>

    <!-- Slide de Imagens -->
    <div class="container mt-4">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < 21; $i++): ?>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?> aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
                <?php endfor; ?>
            </div>
            <div class="carousel-inner">
                <?php for ($i = 1; $i <= 21; $i++): ?>
                    <div class="carousel-item <?php echo $i === 1 ? 'active' : ''; ?>">
                        <img src="img-academia/foto<?php echo $i; ?>.jpg" class="d-block w-100" alt="Imagem <?php echo $i; ?>">
                    </div>
                <?php endfor; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
    </div>

<!-- Faixa de Mensagem -->
<div class="container mt-3">
    <div class="message-banner" style="background-color: #AD3F15; color: white; text-align: center; padding: 1rem; border-radius: 10px;">
        <h4>Produtos</h4>
    </div>
</div>

<!--  Caixas de Produtos Horizontais produtos -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <!-- Produto 1 -->
        <div class="col-md-4">
            <div class="product-box text-center" style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; padding: 1rem;">
                <img src="../public/img-suplemento/creatina.png" alt="Creatina com Carboidrato" style="max-width: 100%; height: auto; border-radius: 5px;">
                <h5 class="mt-2">Creatina com Carboidrato 300g</h5>
                <p class="user-info m-0" style="color: #000000;">R$ 50,33 à vista no Pix (economize R$ 5,59)</p>
                <table class="table table-bordered mt-3" style="font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th colspan="2" style="background-color: #AD3F15; color: white;">INFORMAÇÃO NUTRICIONAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Porções por Embalagem</td>
                            <td>Laranja e Limão: Cerca de 42 / Neutro: Cerca de 48</td>
                        </tr>
                        <tr>
                            <td>Porção</td>
                            <td>Laranja e Limão: 7,1 g (1 dosador) / Neutro: 6,2 g (1 dosador)</td>
                        </tr>
                        <tr>
                            <td>Quantidade por porção</td>
                            <td>6,2g</td>
                        </tr>
                        <tr>
                            <td>Valor energético (kcal)</td>
                            <td>12 kcal (1%)</td>
                        </tr>
                        <tr>
                            <td>Carboidratos (g)</td>
                            <td>3 g (1%)</td>
                        </tr>
                        <tr>
                            <td>Açúcares totais (g)</td>
                            <td>0,2 g (**)</td>
                        </tr>
                        <tr>
                            <td>Açúcares adicionados (g)</td>
                            <td>0,2 g (0%)</td>
                        </tr>
                        <tr>
                            <td>Creatina (mg)</td>
                            <td>3000 mg (**)</td>
                        </tr>
                    </tbody>
                </table>
                <p class="small text-muted">*Percentual de valores diários fornecidos pela porção.</p>
            </div>
        </div>
        <!-- Produto 2 -->
        <div class="col-md-4">
            <div class="product-box text-center" style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; padding: 1rem;">
            <img src="../public/img-suplemento/ZMA.png" alt="Creatina com Carboidrato" style="max-width: 100%; height: auto; border-radius: 5px;">
                <h5 class="mt-2">Vitamina A-Z</h5>
                <p class="user-info m-0" style="color: #000000;">R$ 35,90 à vista no Pix (economize R$ 4,00)</p>
                <table class="table table-bordered mt-3" style="font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th colspan="2" style="background-color: #AD3F15; color: white;">INFORMAÇÃO NUTRICIONAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Porções por Embalagem</td>
                            <td>Laranja e Limão: Cerca de 42 / Neutro: Cerca de 48</td>
                        </tr>
                        <tr>
                            <td>Porção</td>
                            <td>Laranja e Limão: 7,1 g (1 dosador) / Neutro: 6,2 g (1 dosador)</td>
                        </tr>
                        <tr>
                            <td>Quantidade por porção</td>
                            <td>6,2g</td>
                        </tr>
                        <tr>
                            <td>Valor energético (kcal)</td>
                            <td>12 kcal (1%)</td>
                        </tr>
                        <tr>
                            <td>Carboidratos (g)</td>
                            <td>3 g (1%)</td>
                        </tr>
                        <tr>
                            <td>Açúcares totais (g)</td>
                            <td>0,2 g (**)</td>
                        </tr>
                        <tr>
                            <td>Açúcares adicionados (g)</td>
                            <td>0,2 g (0%)</td>
                        </tr>
                        <tr>
                            <td>Creatina (mg)</td>
                            <td>3000 mg (**)</td>
                        </tr>
                    </tbody>
                </table>
                <p class="small text-muted">*Percentual de valores diários fornecidos pela porção.</p>
            </div>
        </div>
        <!-- Produto 3 -->
        <div class="col-md-4">
            <div class="product-box text-center" style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; padding: 1rem;">
            <img src="../public/img-suplemento/zma testo booster.png" alt="Creatina com Carboidrato" style="max-width: 100%; height: auto; border-radius: 5px;">
                <h5 class="mt-2">Multivitamínico DUX</h5>
                <p class="user-info m-0" style="color: #000000;">R$ 79,90 à vista no Pix (economize R$ 8,90)</p>
                <table class="table table-bordered mt-3" style="font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th colspan="2" style="background-color: #AD3F15; color: white;">INFORMAÇÃO NUTRICIONAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Porções por Embalagem</td>
                            <td>Laranja e Limão: Cerca de 42 / Neutro: Cerca de 48</td>
                        </tr>
                        <tr>
                            <td>Porção</td>
                            <td>Laranja e Limão: 7,1 g (1 dosador) / Neutro: 6,2 g (1 dosador)</td>
                        </tr>
                        <tr>
                            <td>Quantidade por porção</td>
                            <td>6,2g</td>
                        </tr>
                        <tr>
                            <td>Valor energético (kcal)</td>
                            <td>12 kcal (1%)</td>
                        </tr>
                        <tr>
                            <td>Carboidratos (g)</td>
                            <td>3 g (1%)</td>
                        </tr>
                        <tr>
                            <td>Açúcares totais (g)</td>
                            <td>0,2 g (**)</td>
                        </tr>
                        <tr>
                            <td>Açúcares adicionados (g)</td>
                            <td>0,2 g (0%)</td>
                        </tr>
                        <tr>
                            <td>Creatina (mg)</td>
                            <td>3000 mg (**)</td>
                        </tr>
                    </tbody>
                </table>
                <p class="small text-muted">*Percentual de valores diários fornecidos pela porção.</p>
            </div>
        </div>
    </div>
</div>
  <!-- Três Caixas Horizontais -->
  <div class="container mt-4">
    <div class="row justify-content-center">
        <!-- Caixa 1: Endereço -->
        <div class="col-md-4">
            <div class="info-box text-center d-flex flex-column justify-content-center" style="background-color:#3A3C49; border: 1px solid #ddd; border-radius: 10px; padding: 1rem; color: white; min-height: 250px;">
                <h5><i class="fas fa-map-marker-alt me-2"></i>Endereço</h5>
                <p class="user-info m-0">Rua Exemplo, 123<br>Bairro Centro<br>Cidade, Estado</p>
            </div>
        </div>
        <!-- Caixa 2: Horários de Funcionamento -->
        <div class="col-md-4">
            <div class="info-box text-center d-flex flex-column justify-content-center" style="background-color:#3A3C49; border-radius: 10px; padding: 1rem; color: white; min-height: 250px;">
                <h5><i class="fas fa-clock me-2"></i>Horários de Funcionamento</h5>
                <p class="user-info m-0">Segunda a Sexta: 06:00 às 22:00</p>
                <p class="user-info m-0">Sábado: 08:00 às 18:00</p>
                <p class="user-info m-0">Domingo: Fechado</p>
            </div>
        </div>
        <!-- Caixa 3: Redes Sociais -->
        <div class="col-md-4">
            <div class="info-box text-center d-flex flex-column justify-content-center" style="background-color:#3A3C49; border: 1px solid #ddd; border-radius: 10px; padding: 1rem; color: white; min-height: 250px;">
                <h5><i class="fas fa-share-alt me-2"></i>Redes Sociais</h5>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/SEU_NUMERO" target="_blank" class="text-decoration-none" style="color: white;">
                        <i class="fab fa-whatsapp" style="font-size: 2rem;"></i>
                    </a>
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/SEU_INSTAGRAM" target="_blank" class="text-decoration-none" style="color: white;">
                        <i class="fab fa-instagram" style="font-size: 2rem;"></i>
                    </a>
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/SEU_FACEBOOK" target="_blank" class="text-decoration-none" style="color: white;">
                        <i class="fab fa-facebook" style="font-size: 2rem;"></i>
                    </a>
                    <!-- Telegram -->
                    <a href="https://t.me/SEU_TELEGRAM" target="_blank" class="text-decoration-none" style="color: white;">
                        <i class="fab fa-telegram" style="font-size: 2rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
    </div>
    </div>
<!-- Footer -->
<footer style="background-color:#000000; color: white; text-align: center; padding: 1rem; width: 100%; margin-top: 2rem; display: flex; align-items: center; justify-content: space-between;">
    <!-- Imagem à Esquerda -->
    <img src="../public/img/logo.png" alt="Logo" style="max-width: 200px; margin-left: 1rem;">
    <!-- Texto Centralizado -->
    <div style="flex-grow: 1; text-align: center;">
        <p style="margin: 0;">&copy; 2025 THE BLACK PANTHER TEAM - Todos os direitos reservados.</p>
    </div>
</footer>
    <!-- Bootstrap JS (opcional, caso precise de funcionalidades JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>