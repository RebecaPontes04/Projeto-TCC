<?php
session_start();

if (!empty($_GET['cod_estacionamento'])) {
    include_once('Conectar.php');

    $cod_estacionamento = $_GET['cod_estacionamento'];

    if (!$conexao) {
        die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
    }

    try {
        // Excluir registros da tabela tb_clientes_estacionamentos relacionados ao estacionamento
        $sqlDeleteClientesEstacionamentos = "DELETE FROM tb_clientes_estacionamentos WHERE fk_cod_estacionamento = ?";
        $stmtDeleteClientesEstacionamentos = $conexao->prepare($sqlDeleteClientesEstacionamentos);
        if (!$stmtDeleteClientesEstacionamentos) {
            throw new Exception('Erro ao preparar consulta SQL para tb_clientes_estacionamentos: ' . $conexao->error);
        }
        $stmtDeleteClientesEstacionamentos->bind_param("i", $cod_estacionamento);
        $stmtDeleteClientesEstacionamentos->execute();
        $stmtDeleteClientesEstacionamentos->close();

        // Excluir registros da tabela tb_vagas relacionados ao estacionamento
        $sqlDeleteVagas = "DELETE FROM tb_vagas WHERE fk_cod_estacionamento = ?";
        $stmtDeleteVagas = $conexao->prepare($sqlDeleteVagas);
        if (!$stmtDeleteVagas) {
            throw new Exception('Erro ao preparar consulta SQL para tb_vagas: ' . $conexao->error);
        }
        $stmtDeleteVagas->bind_param("i", $cod_estacionamento);
        $stmtDeleteVagas->execute();
        $stmtDeleteVagas->close();

        // Verificar se o estacionamento existe
        $sqlSelect = "SELECT * FROM tb_estacionamentos WHERE cod_estacionamento = ?";
        $stmtSelect = $conexao->prepare($sqlSelect);
        if (!$stmtSelect) {
            throw new Exception('Erro ao preparar consulta SQL para verificar estacionamento: ' . $conexao->error);
        }
        $stmtSelect->bind_param("i", $cod_estacionamento);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();

        if ($result->num_rows > 0) {
            // Excluir o estacionamento
            $sqlDelete = "DELETE FROM tb_estacionamentos WHERE cod_estacionamento = ?";
            $stmtDelete = $conexao->prepare($sqlDelete);
            if (!$stmtDelete) {
                throw new Exception('Erro ao preparar consulta SQL para deletar estacionamento: ' . $conexao->error);
            }
            $stmtDelete->bind_param("i", $cod_estacionamento);

            if ($stmtDelete->execute()) {
                $_SESSION['mensagem'] = 'Estacionamento excluído com sucesso!';
                $_SESSION['tipo_mensagem'] = 'sucesso';
            } else {
                throw new Exception('Estacionamento sendo usado');
            }

            $stmtDelete->close();
        } else {
            $_SESSION['mensagem'] = 'Estacionamento não encontrado.';
            $_SESSION['tipo_mensagem'] = 'erro';
        }

        $stmtSelect->close();
    } catch (mysqli_sql_exception $e) {
        // Tratamento para erro de chave estrangeira (FK constraint)
        if ($conexao->errno == 1451) {
            $_SESSION['mensagem'] = 'Erro: O estacionamento não pode ser excluído porque está sendo usado por outra tabela (ex.: reservas ou clientes). Verifique as dependências antes de tentar novamente.';
            $_SESSION['tipo_mensagem'] = 'erro';
        } else {
            // Outros erros de SQL
            $_SESSION['mensagem'] = 'Erro inesperado: ' . $e->getMessage();
            $_SESSION['tipo_mensagem'] = 'erro';
        }
    } catch (Exception $e) {
        $_SESSION['mensagem'] = 'Erro: ' . $e->getMessage();
        $_SESSION['tipo_mensagem'] = 'erro';
    }
}

header('Location: Sistema_estaci.php');
exit;
?>
