<?php
include_once('Conectar.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $cod_reserva = $_POST['cod_reserva'];
    $data_entrada = $_POST['data_entrada'];
    $data_saida = $_POST['data_saida'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_saida = $_POST['hora_saida'];

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("UPDATE tb_reservas SET data_entrada=?, data_saida=?, hora_entrada=?, hora_saida=? WHERE cod_reserva=?");
    $stmt->bind_param("ssssi", $data_entrada, $data_saida, $hora_entrada, $hora_saida, $cod_reserva);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        header('Location: Sistema_reserva.php'); 
        exit;
    } else {
        echo "Erro: " . $stmt->error; // Exibe o erro, se houver
    }

    $stmt->close(); 
} else {
    header('Location: Sistema_reserva.php'); 
    exit;
}
?>
