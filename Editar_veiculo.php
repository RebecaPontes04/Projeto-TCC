<?php
include_once('Conectar.php'); 

// Verifica se o parâmetro cod_veiculo foi fornecido na URL
if (!empty($_GET['cod_veiculo'])) {
    $cod_veiculo = $_GET['cod_veiculo']; 

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT * FROM tb_veiculos WHERE cod_veiculo = ?");
    $stmt->bind_param("i", $cod_veiculo); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Obtém os dados do veículo
        $user_data = $result->fetch_assoc();
        $tipo_veiculo = $user_data['tipo_veiculo'];
        $placa = $user_data['placa'];
        $modelo = $user_data['modelo'];
        $ativo = $user_data['ativo'];
        $imagem = $user_data['imagem'];
    } else {
        // Redireciona para a página Sistema_veiculo.php se nenhum resultado for encontrado
        header('Location: Sistema_veiculo.php');
        exit; 
    }
    // Fecha o statement para liberar recursos
    $stmt->close();
} else {
    // Redireciona para a página Sistema_veiculo.php se o parâmetro não for fornecido
    header('Location: Sistema_veiculo.php');
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <link rel="stylesheet" href="Editar.css"> 
</head>
<body>
    <a href="Sistema_veiculo.php">Voltar</a>
    <div class="box">
        <!-- Formulário para editar os dados do veículo -->
        <form action="SalvarEdit_veiculo.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <h2><b>Editar Veículo</b></h2>
                <br>
                <!-- Campo oculto para armazenar o código do veículo -->
                <input type="hidden" name="cod_veiculo" value="<?php echo htmlspecialchars($cod_veiculo); ?>">
                
                <!-- Campo para editar o tipo do veículo -->
                <div class="inputBox">
                    <input type="text" name="tipo_veiculo" id="tipo_veiculo" class="inputUser" value="<?php echo htmlspecialchars($tipo_veiculo); ?>" required>
                    <label for="tipo_veiculo" class="labelInput">Tipo do Veículo</label>
                </div>
                <br><br>
                
                <!-- Campo para editar a placa do veículo -->
                <div class="inputBox">
                    <input type="text" name="placa" id="placa" class="inputUser" value="<?php echo htmlspecialchars($placa); ?>" required>
                    <label for="placa" class="labelInput">Placa</label>
                </div>
                <br><br>
                
                <!-- Campo para editar o modelo do veículo -->
                <div class="inputBox">
                    <input type="text" name="modelo" id="modelo" class="inputUser" value="<?php echo htmlspecialchars($modelo); ?>" required>
                    <label for="modelo" class="labelInput">Modelo</label>
                </div>
                <br><br>
                
                <!-- Campo para editar o status do veículo -->
                <div class="inputBox">
                    <input type="text" name="ativo" id="ativo" class="inputUser" value="<?php echo htmlspecialchars($ativo); ?>" required>
                    <label for="ativo" class="labelInput">Ativo</label>
                </div>
                <br><br>
                <!-- Campo para upload de uma nova imagem -->
                <div class="inputBox">
                    <input type="file" name="nova_imagem" id="nova_imagem" class="inputUser">
                    <label for="nova_imagem" class="labelInput">Carregar Nova Imagem</label>
                </div>
                <br><br>

                <input type="submit" name="update" id="submit">
            </fieldset>
        </form>
    </div>
</body>
</html>
