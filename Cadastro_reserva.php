<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include_once('Conectar.php');

$mensagem = '';

// Função para calcular as vagas disponíveis
function calcularVagasDisponiveis($data_entrada, $data_saida) {
    global $conexao;
    $total_vagas = 100;

    // Conta as reservas já feitas no período
    $stmt = $conexao->prepare("
        SELECT COUNT(*) as vagas_ocupadas
        FROM tb_reservas
        WHERE data_entrada < ? AND data_saida > ?
    ");
    $stmt->bind_param("ss", $data_saida, $data_entrada);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $vagas_ocupadas = $row['vagas_ocupadas'];
    $vagas_disponiveis = $total_vagas - $vagas_ocupadas;

    return $vagas_disponiveis;
}

// Processa requisição AJAX para retornar vagas disponíveis
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true' && isset($_POST['data_entrada'], $_POST['data_saida'])) {
    echo calcularVagasDisponiveis($_POST['data_entrada'], $_POST['data_saida']);
    exit();
}

// Busca o código do cliente
$email = $_SESSION['email'];
$stmt_cliente = $conexao->prepare("SELECT cod_cliente FROM tb_clientes WHERE email = ?");
$stmt_cliente->bind_param("s", $email);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();

if ($result_cliente->num_rows > 0) {
    $row_cliente = $result_cliente->fetch_assoc();
    $cod_cliente = $row_cliente['cod_cliente'];

    // Verifica se o formulário foi enviado
    if (isset($_POST['submit'])) {
        $fk_cod_vaga = $_POST['fk_cod_vaga'];
        $data_entrada = $_POST['data_entrada'];
        $data_saida = $_POST['data_saida'];
        $hora_entrada = $_POST['hora_entrada'];
        $hora_saida = $_POST['hora_saida'];

        // Busca automaticamente o código do estacionamento
        $stmt_vaga = $conexao->prepare("SELECT fk_cod_estacionamento FROM tb_vagas WHERE cod_vaga = ?");
        $stmt_vaga->bind_param("i", $fk_cod_vaga);
        $stmt_vaga->execute();
        $result_vaga = $stmt_vaga->get_result();

        if ($result_vaga->num_rows > 0) {
            $row_vaga = $result_vaga->fetch_assoc();
            $fk_cod_estacionamento = $row_vaga['fk_cod_estacionamento'];

            // Verifica se a data/hora de saída é maior que a de entrada
            if (strtotime($data_entrada . ' ' . $hora_entrada) >= strtotime($data_saida . ' ' . $hora_saida)) {
                $mensagem = 'A hora de saída deve ser maior que a hora de entrada.';
            } else {
                // Insere a reserva
                $stmt_reserva = $conexao->prepare("
                    INSERT INTO tb_reservas (fk_cod_cliente, fk_cod_estacionamento, fk_cod_vaga, data_entrada, data_saida, hora_entrada, hora_saida)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt_reserva->bind_param("iiissss", $cod_cliente, $fk_cod_estacionamento, $fk_cod_vaga, $data_entrada, $data_saida, $hora_entrada, $hora_saida);

                if ($stmt_reserva->execute()) {
                    $_SESSION['mensagem'] = 'Reserva realizada com sucesso!';
                } else {
                    $_SESSION['mensagem'] = 'Erro ao reservar: ' . addslashes($stmt_reserva->error);
                }

                $stmt_reserva->close();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $mensagem = 'Código de vaga inválido.';
        }

        $stmt_vaga->close();
    }
} else {
    $mensagem = 'Erro: Cliente não encontrado.';
}

$stmt_cliente->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça sua Reserva</title>
    <link rel="stylesheet" href="cadastro.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <a href="pg_do_cliente.php">Voltar</a>
    <div class="container">
        <form class="cadastro-form" method="POST">
            <h2>FAÇA SUA RESERVA</h2>
            <br>

            <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="alert <?= strpos($_SESSION['mensagem'], 'sucesso') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= $_SESSION['mensagem']; ?>
                </div>
                <script>
                    // Se a mensagem for de sucesso, aguarda 2 segundos e redireciona
                    <?php if (strpos($_SESSION['mensagem'], 'sucesso') !== false): ?>
                        setTimeout(function() {
                            window.location.href = 'pg_do_cliente.php';
                        }, 2000); // Aguarda 2 segundos antes de redirecionar
                    <?php endif; ?>
                </script>
                <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>

            <div id="vagas-disponiveis">
                Vagas disponíveis: <span id="contador-vagas">Carregando...</span>
            </div>
            <br>

            <label for="fk_cod_vaga">Selecione a Vaga:</label>
            <select name="fk_cod_vaga" id="fk_cod_vaga" required>
                <option value="">Selecione</option>
                <?php
                $stmt_vagas = $conexao->prepare("SELECT cod_vaga, tipo_veiculo FROM tb_vagas");
                $stmt_vagas->execute();
                $result_vagas = $stmt_vagas->get_result();

                while ($row_vaga = $result_vagas->fetch_assoc()) {
                    echo "<option value='{$row_vaga['cod_vaga']}'>Vaga {$row_vaga['cod_vaga']} - {$row_vaga['tipo_veiculo']}</option>";
                }

                $stmt_vagas->close();
                ?>
            </select>
            <br><br>

            <label for="data_entrada">Data de Entrada:</label>
            <input type="date" name="data_entrada" id="data_entrada" required min="<?= date('Y-m-d'); ?>">
            <br><br>

            <label for="hora_entrada">Hora de Entrada:</label>
            <select name="hora_entrada" id="hora_entrada" required>
                <?php for ($i = 7; $i <= 21; $i++): ?>
                    <option value="<?= sprintf('%02d:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                <?php endfor; ?>
            </select>
            <br><br>

            <label for="data_saida">Data de Saída:</label>
            <input type="date" name="data_saida" id="data_saida" required min="<?= date('Y-m-d'); ?>">
            <br><br>

            <label for="hora_saida">Hora de Saída:</label>
            <select name="hora_saida" id="hora_saida" required>
                <?php for ($i = 7; $i <= 22; $i++): ?>
                    <option value="<?= sprintf('%02d:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                <?php endfor; ?>
            </select>
            <br><br>

            <button type="submit" name="submit">Reservar</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            function atualizarVagas() {
                const dataEntrada = $('#data_entrada').val();
                const dataSaida = $('#data_saida').val();

                if (dataEntrada && dataSaida) {
                    $.ajax({
                        url: '',  // Mesmo arquivo PHP
                        type: 'POST',
                        data: { ajax: 'true', data_entrada: dataEntrada, data_saida: dataSaida },
                        success: function(response) {
                            $('#contador-vagas').text(response);
                        }
                    });
                }
            }

            // Atualiza as vagas ao mudar as datas
            $('#data_entrada, #data_saida').change(atualizarVagas);

            // Verifica os horários de entrada e saída
            $('#hora_entrada').change(function() {
                const horaEntrada = parseInt($('#hora_entrada').val().split(':')[0]);

                $('#hora_saida option').each(function() {
                    const horaSaida = parseInt($(this).val().split(':')[0]);
                    
                    if (horaSaida <= horaEntrada) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });

            // Previne o envio do formulário se não houver vagas disponíveis
            $('.cadastro-form').on('submit', function(e) {
                const vagas = parseInt($('#contador-vagas').text());
                if (isNaN(vagas) || vagas <= 0) {
                    e.preventDefault();
                    alert('Não há vagas disponíveis ou os dados ainda estão sendo carregados.');
                }
            });
        });
    </script>
</body>
</html>
