<?php
session_start();

if (!empty($_GET['cod_vaga'])) {
    include_once('Conectar.php');

    $cod_vaga = $_GET['cod_vaga'];

    // Prepared statement para deletar as reservas
    $sqlDeleteReservas = $conexao->prepare("DELETE FROM tb_reservas WHERE fk_cod_vaga = ?");
    $sqlDeleteReservas->bind_param("i", $cod_vaga);

    if ($sqlDeleteReservas->execute()) {
        // Prepared statement para deletar a vaga
        $sqlDeleteVagas = $conexao->prepare("DELETE FROM tb_vagas WHERE cod_vaga = ?");
        $sqlDeleteVagas->bind_param("i", $cod_vaga);

        if ($sqlDeleteVagas->execute()) {
            $_SESSION['mensagem'] = 'Vagas e reservas excluídas com sucesso!';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir vaga: ' . addslashes($sqlDeleteVagas->error);
        }

        $sqlDeleteVagas->close(); 
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir reservas: ' . addslashes($sqlDeleteReservas->error);
    }

    $sqlDeleteReservas->close(); 
} else {
    $_SESSION['mensagem'] = 'Código da vaga não fornecido.';
}

header('Location: Sistema_vaga.php');
exit;

?>
