<?php
include_once('Conectar.php'); 

// Verifica se o parâmetro cod_reserva foi fornecido na URL
if (!empty($_GET['cod_reserva'])) {
    $cod_reserva = $_GET['cod_reserva'];

    // Consulta preparada para evitar injeção de SQL
    $stmt = $conexao->prepare("SELECT * FROM tb_reservas WHERE cod_reserva = ?");
    $stmt->bind_param("i", $cod_reserva); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Obtém os dados da reserva
        $user_data = $result->fetch_assoc();
        $data_entrada = $user_data['data_entrada'];
        $data_saida = $user_data['data_saida'];
        $hora_entrada = $user_data['hora_entrada'];
        $hora_saida = $user_data['hora_saida'];
    } else {
        // Redireciona para a página Sistema_reserva.php se nenhum resultado for encontrado
        header('Location: Sistema_reserva.php');
        exit; // Garante que o script seja interrompido após o redirecionamento
    }
    // Fecha o statement para liberar recursos
    $stmt->close();
} else {
    // Redireciona para a página Sistema_reserva.php se o parâmetro não for fornecido
    header('Location: Sistema_reserva.php');
    exit; 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
    <link rel="stylesheet" href="Editar.css"> 
</head>
<body>
    <a href="Sistema_reserva.php">Voltar</a>
    <div class="box">
        <!-- Formulário para editar os dados da reserva -->
        <form action="SalvarEdit_reserva.php" method="POST">
            <fieldset>
                <h2><b>Editar Reserva</b></h2>
                <br>
                <!-- Campo oculto para armazenar o código da reserva -->
                <input type="hidden" name="cod_reserva" value="<?php echo htmlspecialchars($cod_reserva); ?>">
                
                <!-- Campo para editar a data de entrada -->
                <div class="inputBox">
                    <input type="date" name="data_entrada" id="data_entrada" class="inputUser" value="<?php echo htmlspecialchars($data_entrada); ?>" required>
                    <label for="data_entrada" class="labelInput">Data da Entrada</label>
                </div>
                <br><br>
                
                <!-- Campo para editar a data de saída -->
                <div class="inputBox">
                    <input type="date" name="data_saida" id="data_saida" class="inputUser" value="<?php echo htmlspecialchars($data_saida); ?>" required>
                    <label for="data_saida" class="labelInput">Data da Saída</label>
                </div>
                <br><br>
                
                <!-- Campo para editar a hora de entrada -->
                <div class="inputBox">
                    <input type="time" name="hora_entrada" id="hora_entrada" class="inputUser" value="<?php echo htmlspecialchars($hora_entrada); ?>" required>
                    <label for="hora_entrada" class="labelInput">Hora da Entrada</label>
                </div>
                <br><br>
                
                <!-- Campo para editar a hora de saída -->
                <div class="inputBox">
                    <input type="time" name="hora_saida" id="hora_saida" class="inputUser" value="<?php echo htmlspecialchars($hora_saida); ?>" required>
                    <label for="hora_saida" class="labelInput">Hora da Saída</label>
                </div>
                <br><br>

                <input type="submit" name="update" id="submit" value="Salvar Alterações">
            </fieldset>
        </form>
    </div>
</body>
</html>
