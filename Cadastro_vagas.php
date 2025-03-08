<?php
// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    include_once('Conectar.php');

    // Captura os dados do formulário
    $num_vaga = $_POST['num_vaga'];
    $tipo_veiculo = $_POST['tipo_veiculo'];
    $imagem = $_POST['imagem'];

    // Verifica a conexão com o banco de dados
    if ($conexao) {

        // Verifica se a vaga já está registrada
        $stmt = $conexao->prepare("SELECT nome FROM tb_vagas WHERE num_vaga = ?");
        $stmt->bind_param("s", $num_vaga); 
        $stmt->execute();
        $stmt->store_result(); 

        if ($stmt->num_rows > 0) {
            $mensagem = 'Esta vaga já está registrada.';
        } else {

            $stmt->close();

            // Insere a nova vaga no banco de dados
            $stmt = $conexao->prepare("INSERT INTO tb_vagas (num_vaga, tipo_veiculo, imagem) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $num_vaga, $tipo_veiculo, $imagem); 

            if ($stmt->execute()) {
                $mensagem = 'Cadastro realizado com sucesso!';
                exit; 
            } else {
                $mensagem = 'Erro ao cadastrar: ' . addslashes($stmt->error); 
            }
        }

        $stmt->close();
    } else {
        $mensagem = 'Erro ao conectar ao banco de dados.';
    }

    $conexao->close();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da vaga</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <a href="index.php">Voltar</a>
    <div class="container">

        <form class="cadastro-form" method="POST">
            <h2>Cadastro da vaga</h2>

            <?php if (isset($mensagem)): ?>
                <div class="alert <?= strpos($mensagem, 'sucesso') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <!-- Campos do formulário -->
            <input type="text" name="num_vaga" placeholder="Número da Vaga" required>
            <input type="number" name="tipo_veiculo" placeholder="Tipo do Veículo" required>
            <input type="text" name="imagem" placeholder="Imagem" required>
            <br><br>

            <button type="submit" name="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
