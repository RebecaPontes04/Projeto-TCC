<?php
session_start();
include_once('Conectar.php');

if (!empty($_GET['cod_reserva'])) {
    $cod_reserva = $_GET['cod_reserva'];

    try {
        $stmt = $conexao->prepare("DELETE FROM tb_reservas WHERE cod_reserva = ?");
        $stmt->bind_param("i", $cod_reserva);
        $stmt->execute();
        $stmt->close();

        $_SESSION['mensagem'] = 'Reserva excluída com sucesso!';
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $_SESSION['mensagem'] = 'Erro: Não é possível excluir esta reserva pois ela está associada a outras entidades.';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir reserva: ' . addslashes($e->getMessage());
        }
    }
}
header('Location: Sistema_reserva.php');
exit;
?>
