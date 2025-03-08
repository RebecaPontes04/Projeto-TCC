<?php
include_once('Conectar.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $cod_vaga = $_POST['cod_vaga'];
    $fk_cod_reserva = $_POST['fk_cod_reserva']; // Adicionando para receber o código da reserva
    $fk_cod_estacionamento = $_POST['fk_cod_estacionamento']; // Para o código do estacionamento
    $tipo_veiculo = $_POST['tipo_veiculo'];

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("UPDATE tb_vagas SET fk_cod_reserva=?, fk_cod_estacionamento=?, tipo_veiculo=? WHERE cod_vaga=?");
    $stmt->bind_param("iisi", $fk_cod_reserva, $fk_cod_estacionamento, $tipo_veiculo, $cod_vaga);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        header('Location: Sistema_vaga.php'); 
        exit;
    } else {
        echo "Erro: " . $stmt->error; // Exibe o erro, se houver
    }

    $stmt->close(); 
} else {
    header('Location: Sistema_vaga.php'); 
    exit;
}
?>
