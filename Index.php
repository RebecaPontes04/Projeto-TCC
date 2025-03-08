<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="Index.css">
</head>

<body>
    <header>
        <nav class="navbar d-flex justify-content-between align-items-center p-3">
            <div class="auth-buttons d-flex align-items-center">
                <?php if (isset($_SESSION['cod_cliente'])): ?>
                    <i class="fa-solid fa-hand"></i> Olá, <?php echo htmlspecialchars($_SESSION['nome_cliente']); ?>!
                <?php else: ?>
                    <a href="Login.php" class="mr-3">
                        <i class="fa-solid fa-user"></i> Login
                    </a>
                    <a href="cadastro.php" class="mr-3">
                        <i class="fa-solid fa-paperclip"></i> Cadastro
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['cod_cliente'])): ?>
                    <div class="dropdown ml-3">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="menuDropdown">
                            <a class="dropdown-item" href="Cadastro_estacionamento.php"><i class="fa-solid fa-street-view"></i> Cadastrar Estacionamento</a>
                            <a class="dropdown-item" href="Cadastro_veiculo.php"><i class="fa-solid fa-car"></i> Cadastre seu Veículo</a>
                            <a class="dropdown-item" href="meus_veiculos.php"><i class="fa-solid fa-truck"></i> Meus Veículos</a>
                            <a class="dropdown-item" href="pg_do_cliente.php"><i class="fa-solid fa-road"></i> Faça sua Reserva</a>
                            <a class="dropdown-item" href="Minhas_reservas.php"><i class="fa-regular fa-clock"></i> Minhas Reservas</a>
                            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'A'): ?>
                                <a class="dropdown-item" href="adm.php"><i class="fa-solid fa-computer"></i> Sistema</a>
                            <?php endif; ?>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><i class="fa-solid fa-door-closed"></i> Sair</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>Bem-vindo ao seu Estacionamento Moderno</h2>
            <p>Organização e praticidade ao seu alcance.</p>
        </section>

        <section class="features">
            <div class="feature-item">
                <i class="fas fa-car"></i>
                <h3>Fácil de Usar</h3>
                <p>Interface amigável e intuitiva.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <h3>Segurança</h3>
                <p>Monitoramento 24/7 para a sua tranquilidade.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-map-signs"></i>
                <h3>Orientação</h3>
                <p>Sinalização clara e precisa.</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Controle de Estacionamento.</p>
    </footer>
</body>

</html>
