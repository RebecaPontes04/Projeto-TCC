<?php
session_start();
include_once('Conectar.php');

// Verifica se o código do cliente foi enviado 
if (!empty($_GET['cod_cliente'])) {
    $cod_cliente = $_GET['cod_cliente'];

    if (!$conexao) {
        die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
    }

    // Excluir registros em tb_clientes_estacionamentos relacionados ao cliente
    $stmt = $conexao->prepare("DELETE FROM tb_clientes_estacionamentos WHERE fk_cod_cliente = ?");
    if (!$stmt) {
        die('Erro ao preparar consulta SQL em tb_clientes_estacionamentos: ' . $conexao->error);
    }
    $stmt->bind_param("i", $cod_cliente);
    $stmt->execute();
    $stmt->close();

    // Excluir registros em tb_reservas relacionados ao cliente
    $stmt = $conexao->prepare("DELETE FROM tb_reservas WHERE fk_cod_cliente = ?");
    if (!$stmt) {
        die('Erro ao preparar consulta SQL em tb_reservas: ' . $conexao->error);
    }
    $stmt->bind_param("i", $cod_cliente);
    $stmt->execute();
    $stmt->close();

    // Excluir o cliente da tabela tb_clientes
    $stmt = $conexao->prepare("DELETE FROM tb_clientes WHERE cod_cliente = ?");
    if (!$stmt) {
        die('Erro ao preparar consulta SQL em tb_clientes: ' . $conexao->error);
    }
    $stmt->bind_param("i", $cod_cliente);
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = 'Cliente excluído com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir cliente: ' . addslashes($stmt->error);
    }
    $stmt->close();
}
header('Location: sistema.php');
exit;
?>
