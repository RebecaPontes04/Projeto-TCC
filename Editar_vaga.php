<?php
include_once('Conectar.php'); 

// Verifica se o parâmetro cod_vaga foi fornecido na URL
if (!empty($_GET['cod_vaga'])) {
    $cod_vaga = $_GET['cod_vaga']; 

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT * FROM tb_vagas WHERE cod_vaga = ?");
    $stmt->bind_param("i", $cod_vaga); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Obtém os dados da vaga
        $user_data = $result->fetch_assoc();
        $fk_cod_estacionamento = $user_data['fk_cod_estacionamento'] ?? ''; // Verifica se existe
        $tipo_veiculo = $user_data['tipo_veiculo'] ?? ''; // Verifica se existe
        $ativo = $user_data['ativo'] ?? ''; // Verifica se existe
        $imagem = $user_data['imagem'] ?? ''; // Verifica se existe
        $fk_cod_reserva = $user_data['fk_cod_reserva'] ?? ''; // Verifica se existe
    } else {
        // Redireciona para a página Sistema_vaga.php se nenhum resultado for encontrado
        header('Location: Sistema_vaga.php');
        exit;
    }
    // Fecha o statement para liberar recursos
    $stmt->close();
} else {
    // Redireciona para a página Sistema_vaga.php se o parâmetro não for fornecido
    header('Location: Sistema_vaga.php');
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vaga</title>
    <link rel="stylesheet" href="Editar.css"> 
</head>
<body>
    <a href="Sistema_vaga.php">Voltar</a>
    <div class="box">
        <!-- Formulário para editar os dados da vaga -->
        <form action="SalvarEdit_vaga.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <h2><b>Editar Vaga</b></h2>
                <br>
                
                <!-- Campo oculto para armazenar o código da vaga -->
                <input type="hidden" name="cod_vaga" value="<?php echo htmlspecialchars($cod_vaga); ?>">

                <!-- Exibe o código da reserva -->
                <div class="inputBox">
                    <input type="number" name="fk_cod_reserva" id="fk_cod_reserva" class="inputUser " value="<?php echo htmlspecialchars($fk_cod_reserva); ?>" readonly>
                </div>
                <br><br>
                
                <!-- Campo para editar o código do estacionamento -->
                <div class="inputBox">
                    <input type="number" name="fk_cod_estacionamento" id="fk_cod_estacionamento" class="inputUser " value="<?php echo htmlspecialchars($fk_cod_estacionamento); ?>" readonly>
                </div>
                <br><br>

                <!-- Campo para editar o tipo de veículo -->
                <div class="inputBox">
                    <input type="text" name="tipo_veiculo" id="tipo_veiculo" class="inputUser " value="<?php echo htmlspecialchars($tipo_veiculo); ?>" required>
                    <label for="tipo_veiculo" class="labelInput">Tipo do Veículo</label>
                </div>
                <br><br>

                <input type="submit" name="Salvar" value="Salvar">
                <input type="button" value="Fechar" onclick="window.location.href='Sistema_vaga.php';">
            </fieldset>
        </form>
    </div>
</body>
</html>