<?php
include_once('Conectar.php');

// Lógica de consulta aos estacionamentos
if ($conexao) {
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $pesquisa = $conexao->real_escape_string($_GET['query']);
        $sql = "SELECT cod_estacionamento, nome, capacidade_vaga, cobertura, imagem, ativo, cnpj, telefone, valor_hora, valor_semanal, valor_mensal FROM tb_estacionamentos WHERE nome LIKE '%$pesquisa%'";
    } else {
        $sql = "SELECT cod_estacionamento, nome, capacidade_vaga, cobertura, imagem, ativo, cnpj, telefone, valor_hora, valor_semanal, valor_mensal FROM tb_estacionamentos";
    }

    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        // Dados encontrados
    } else {
        echo "Nenhum estacionamento encontrado.";
    }
} else {
    echo "Erro ao conectar ao banco de dados.";
}

// Requisição AJAX para buscar as vagas
if (isset($_POST['cod_estacionamento'])) {
    $cod_estacionamento = $_POST['cod_estacionamento'];
    $sql_vagas = "SELECT capacidade_vaga - COUNT(*) as vagas_disponiveis FROM tb_reservas WHERE cod_estacionamento = ?";
    $stmt = $conexao->prepare($sql_vagas);
    $stmt->bind_param("i", $cod_estacionamento);
    $stmt->execute();
    $result_vagas = $stmt->get_result();
    $row_vagas = $result_vagas->fetch_assoc();
    $vagas_disponiveis = $row_vagas['vagas_disponiveis'];

    echo $vagas_disponiveis;
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estacionamentos Disponíveis</title>
    <link rel="stylesheet" href="pg_do_cliente.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

<body>
<header>
    <!-- Botão voltar -->
    <a href="index.php" class="voltar">Voltar</a>
    <nav class="navbar d-flex justify-content-between align-items-center p-3">
        <div class="auth-buttons d-flex align-items-center">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control custom-search-input" placeholder="Digite aqui o estacionamento desejado" value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                <button type="submit" class="btn btn-secondary custom-search-button ml-2">Pesquisar</button>
            </form>
        </div>
    </nav>
</header>


    <div class="container">
        <h1 class="text-center my-4">ESTACIONAMENTOS DISPONÍVEIS</h1>
        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($row['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nome']) ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($row['nome']) ?></h5>
                                <button class="btn btn-info" data-toggle="modal" data-target="#modal-<?= $row['cod_estacionamento'] ?>">Saiba Mais</button>

                                <!-- Modal -->
                                <div class="modal fade" id="modal-<?= $row['cod_estacionamento'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel-<?= $row['cod_estacionamento'] ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-<?= $row['cod_estacionamento'] ?>">Detalhes do Estacionamento</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Cobertura: <?= $row['cobertura'] == 'SEM' ? 'Sem Cobertura' : 'Com Cobertura' ?></p>
                                                <p>CNPJ: <?= htmlspecialchars($row['cnpj']) ?></p>
                                                <p>Telefone: <?= htmlspecialchars($row['telefone']) ?></p>
                                                <p>Valor/Hora: R$ <?= number_format($row['valor_hora'], 2, ',', '.') ?></p>
                                                <p>Valor Semanal: R$ <?= number_format($row['valor_semanal'], 2, ',', '.') ?></p>
                                                <p>Valor Mensal: R$ <?= number_format($row['valor_mensal'], 2, ',', '.') ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="Cadastro_reserva.php" method="POST">
                                    <input type="hidden" name="cod_estacionamento" value="<?= $row['cod_estacionamento'] ?>">
                                    <button type="submit" class="btn btn-success mt-2">Realizar Reserva</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum estacionamento disponível.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>

