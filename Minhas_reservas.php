<?php
session_start();
include_once('Conectar.php');

// Verifica se o cliente está logado
if (!isset($_SESSION['email'])) {
    echo "Acesso negado.";
    exit();
}

// Obtém o email do cliente a partir da sessão
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
    <title>Reservas Realizadas</title>
    <link rel="stylesheet" href="meus_veiculos.css">
</head>
<body>

<header>
    <nav class="navbar d-flex justify-content-between align-items-center p-3">
        <div class="auth-buttons d-flex align-items-center">
            <a href="Index.php" class="mr-3">Voltar</a>
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
            </div>
        </div>
    </nav>
</header>

<?php
if ($resultCliente->num_rows > 0) {
    $cliente = $resultCliente->fetch_assoc();
    $cod_cliente = $cliente['cod_cliente'];

    // Consulta para obter as reservas do cliente com INNER JOIN
    $sqlReservas = "
        SELECT 
            r.cod_reserva,
            r.data_entrada,
            r.data_saida,
            r.hora_entrada,
            r.hora_saida,
            e.nome AS estacionamento
        FROM 
            tb_reservas AS r
        INNER JOIN 
            tb_estacionamentos AS e 
        ON 
            r.fk_cod_estacionamento = e.cod_estacionamento
        WHERE 
            r.fk_cod_cliente = ?";
    
    $stmtReservas = $conexao->prepare($sqlReservas);

    if ($stmtReservas === false) {
        echo "Erro ao preparar a consulta das reservas.";
        exit();
    }

    $stmtReservas->bind_param("i", $cod_cliente);
    $stmtReservas->execute();
    $resultReservas = $stmtReservas->get_result();

    // Exibe as reservas do cliente
    echo "<div class='container'>";
    echo "<h2>Reservas Realizadas:</h2>";
    
    if ($resultReservas->num_rows > 0) {
        echo "<div class='card-container'>";
        while ($reserva = $resultReservas->fetch_assoc()) {
            // Exibe os detalhes da reserva em um card
            echo "<div class='card'>";
            echo "<div class='card-content'>";
            echo "<h3>Reserva #". htmlspecialchars($reserva['cod_reserva']) ."</h3>";
            echo "<p>Estacionamento: " . htmlspecialchars($reserva['estacionamento']) . "</p>";
            echo "<p>Entrada: " . htmlspecialchars($reserva['data_entrada']) . " às " . htmlspecialchars($reserva['hora_entrada']) . "</p>";
            echo "<p>Saída: " . htmlspecialchars($reserva['data_saida']) . " às " . htmlspecialchars($reserva['hora_saida']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='alert alert-error'>Nenhuma reserva encontrada.</div>";
    }
} else {
    echo "<div class='alert alert-error'>Cliente não encontrado.</div>";
}

// Fecha a conexão
$stmtCliente->close();
$stmtReservas->close();
$conexao->close();
?>

</body>
</html>
