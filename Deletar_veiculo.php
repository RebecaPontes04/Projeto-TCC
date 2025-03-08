<?php
session_start();
include_once('Conectar.php');

// Verifica se o código do veículo foi enviado 
if (!empty($_GET['cod_veiculo'])) {
    $cod_veiculo = $_GET['cod_veiculo'];

    // Excluir registros na tabela tb_veiculos_clientes relacionados ao veículo
    $stmt = $conexao->prepare("DELETE FROM tb_veiculos WHERE cod_veiculo = ?");
    if (!$stmt) {
        $_SESSION['mensagem'] = 'Erro na preparação da query (tb_veiculos): ' . $conexao->error;
        header('Location: Sistema_veiculo.php');
        exit;
    }
    $stmt->bind_param("i", $cod_veiculo);

    if ($stmt->execute()) {
        $stmt->close();

        // Excluir registros em tb_reservas relacionados ao veículo
        $stmt = $conexao->prepare("DELETE FROM tb_reservas WHERE cod_veiculo = ?");
        if (!$stmt) {
            $_SESSION['mensagem'] = 'Erro na preparação da query (tb_reservas): ' . $conexao->error;
            header('Location: Sistema_veiculo.php');
            exit;
        }
        $stmt->bind_param("i", $cod_veiculo);

        if ($stmt->execute()) {
            $stmt->close();

            // Excluir o veículo
            $stmt = $conexao->prepare("DELETE FROM tb_veiculos WHERE cod_veiculo = ?");
            if (!$stmt) {
                $_SESSION['mensagem'] = 'Erro na preparação da query (tb_veiculos - exclusão final): ' . $conexao->error;
                header('Location: Sistema_veiculo.php');
                exit;
            }
            $stmt->bind_param("i", $cod_veiculo);

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = 'Veículo e reservas excluídos com sucesso!';
            } else {
                $_SESSION['mensagem'] = 'Erro ao excluir veículo: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir reservas: ' . $stmt->error;
            $stmt->close();
        }
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir veículos clientes: ' . $stmt->error;
        $stmt->close();
    }
} else {
    $_SESSION['mensagem'] = 'Código do veículo não fornecido.';
}

header('Location: Sistema_veiculo.php');
exit;
?>
