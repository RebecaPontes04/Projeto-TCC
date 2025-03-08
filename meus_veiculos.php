<?php
session_start();
include_once('Conectar.php');

// Verifica se o cliente está logado
if (!isset($_SESSION['email'])) {
    echo "Acesso negado.";
    exit();
}

// Obtém o cod_cliente a partir da sessão
$email = $_SESSION['email'];

// Prepara a consulta para obter os dados do cliente
$sqlCliente = "SELECT cod_cliente FROM tb_clientes WHERE email = ?";
$stmtCliente = $conexao->prepare($sqlCliente);

if ($stmtCliente === false) {
    echo "Erro ao preparar a consulta do cliente.";
    exit();
}

$stmtCliente->bind_param("s", $email);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos Cadastrados</title>
    <link rel="stylesheet" href="meus_veiculos.css">
</head>
<body>

<header>
    <nav class="navbar d-flex justify-content-between align-items-center p-3">
        <div class="auth-buttons d-flex align-items-center">
            <a href="Index.php" class="mr-3"> Voltar
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </a>
            </div>
        </div>
    </nav>
</header>

<?php
if ($resultCliente->num_rows > 0) {
    $cliente = $resultCliente->fetch_assoc();
    $cod_cliente = $cliente['cod_cliente'];

    // Prepara a consulta para obter os veículos do cliente logado
    $sqlVeiculos = "
        SELECT 
            v.cod_veiculo,
            v.placa,
            v.modelo,
            v.imagem
        FROM 
            tb_veiculos AS v
        WHERE 
            v.cod_cliente = ?";
    
    $stmtVeiculos = $conexao->prepare($sqlVeiculos);

    if ($stmtVeiculos === false) {
        echo "Erro ao preparar a consulta dos veículos.";
        exit();
    }

    $stmtVeiculos->bind_param("i", $cod_cliente);
    $stmtVeiculos->execute();
    $resultVeiculos = $stmtVeiculos->get_result();

    // Exibe os veículos do cliente
    echo "<div class='container'>";
    echo "<h2>Veículos Cadastrados:</h2>";
    
    if ($resultVeiculos->num_rows > 0) {
        echo "<div class='card-container'>";
        while ($veiculo = $resultVeiculos->fetch_assoc()) {
            // Exibe a imagem e os detalhes do veículo em um card
            echo "<div class='card'>";
            echo "<img src='" . htmlspecialchars($veiculo['imagem']) . "' alt='Imagem do veículo' class='vehicle-image'>";
            echo "<div class='card-content'>";
            echo "<h3>" . htmlspecialchars($veiculo['modelo']) . "</h3>";
            echo "<p>Placa: " . htmlspecialchars($veiculo['placa']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='alert alert-error'>Nenhum veículo cadastrado.</div>";
    }
} else {
    echo "<div class='alert alert-error'>Cliente não encontrado.</div>";
}

// Fecha a conexão
$stmtCliente->close();
$stmtVeiculos->close();
$conexao->close();
?>

</body>
</html>
