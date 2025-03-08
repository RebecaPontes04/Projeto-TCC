<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadados e configuração do cabeçalho -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adm.css">
    <title>CONTROLE DO ADMINISTRADOR</title>
</head>
<body>

    <!-- Cabeçalho da página -->
    <header class="header">
        <div class="logo">
            <a href="index.php">Voltar</a>
        </div>
    </header>
    
    <!-- Conteúdo principal -->
    <main class="main-content">
        <h2>CONTROLE DO ADMINISTRADOR</h2>
        
        <!-- Seção de soluções do administrador -->
        <div class="solutions">
            <?php
                // Array contendo informações das soluções
                $solutions = [
                    ['name' => 'CLIENTES', 'icon' => '', 'url' => 'Sistema.php'],
                    ['name' => 'ESTACIONAMENTOS', 'icon' => '', 'url' => 'Sistema_estaci.php'],
                    ['name' => 'VAGAS', 'icon' => '', 'url' => 'Sistema_vaga.php'],
                    ['name' => 'RESERVAS', 'icon' => '', 'url' => 'Sistema_reserva.php'],
                    ['name' => 'VEÍCULOS', 'icon' => '', 'url' => 'Sistema_veiculo.php']
                ];

                // Loop para criar os botões de cada solução
                foreach ($solutions as $solution) {

                    // Verifica se há a classe de destaque
                    $highlightClass = isset($solution['highlight']) ? 'highlight' : '';
                    
                    // Verifica se a URL está definida
                    $url = isset($solution['url']) ? $solution['url'] : '#';
                    
                    // Renderiza o botão da solução
                    echo "<button class='solution-button $highlightClass' onclick=\"window.location.href='$url'\">";
                    echo "<span class='icon'>{$solution['icon']}</span>";
                    echo "<span class='name'>{$solution['name']}</span>";
                    echo "</button>";
                }
            ?>
        </div>
    </main>
</body>
</html>
