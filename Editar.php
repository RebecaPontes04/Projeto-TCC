<?php
include_once('Conectar.php'); 

// Verifica se o parâmetro cod_cliente foi fornecido na URL
if (!empty($_GET['cod_cliente'])) {
    $cod_cliente = $_GET['cod_cliente']; 

    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT * FROM tb_clientes WHERE cod_cliente = ?");
    $stmt->bind_param("i", $cod_cliente); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Obtém os dados do cliente
        $user_data = $result->fetch_assoc();
        $nome = $user_data['nome'];
        $email = $user_data['email'];
        $ativo = $user_data['ativo'];
        $cpf = $user_data['cpf'];
        $data_nasc = $user_data['data_nasc'];
        $telefone = $user_data['telefone'];
        $cep = $user_data['cep'];
        $tipo = $user_data['tipo'];
        $senha = $user_data['senha']; 
    } else {
        // Redireciona para a página sistema.php se nenhum resultado for encontrado
        header('Location: sistema.php');
        exit; 
    }
    // Fecha o statement para liberar recursos
    $stmt->close();
} else {
    // Redireciona para a página sistema.php se o parâmetro não for fornecido
    header('Location: sistema.php');
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
    <a href="sistema.php">Voltar</a>
    <div class="box">
        <!-- Formulário para editar os dados do cliente -->
        <form action="SalvarEdit.php" method="POST">
            <fieldset>
                <h2><b>Editar Cliente</b></h2>
                <br>
                <!-- Campo oculto para armazenar o código do cliente -->
                <input type="hidden" name="cod_cliente" value="<?php echo htmlspecialchars($cod_cliente); ?>">
                
                <!-- Campo para editar o nome do cliente -->
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser " value="<?php echo htmlspecialchars($nome); ?>" required>
                    <label for="nome" class="labelInput">Nome completo</label>
                </div>
                <br><br>
                <!-- Campo para editar o email do cliente -->
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser " value="<?php echo htmlspecialchars($email); ?>" required>
                    <label for="email" class="labelInput">Email</label>
                </div>
                <br><br>
                <!-- Campo para editar o ativo do cliente -->
                <div class="inputBox">
                    <label for="ativo"><b>Ativo:</b></label>
                    <select name="ativo" id="ativo" class="inputUser " required>
                        <option value="S" <?php echo ($ativo == 'S') ? 'selected' : ''; ?>>Sim</option>
                        <option value="N" <?php echo ($ativo == 'N') ? 'selected' : ''; ?>>Não</option>
                    </select>
                </div>
                <br><br>
                <!-- Campo para editar o CPF -->
                <div class="inputBox">
                    <input type="text" name="cpf" id="cpf" class="inputUser " value="<?php echo htmlspecialchars($cpf); ?>" required>
                    <label for="cpf" class="labelInput">CPF</label>
                </div>
                <br><br>
                <!-- Campo para editar a data_nasc do cliente -->
                <label for="data_nasc"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nasc" id="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>" required>
                <br><br><br>
                <!-- Campo para editar o telefone do cliente -->
 <div class="inputBox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser  " value="<?php echo htmlspecialchars($telefone); ?>" required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>
                <br><br>
                <!-- Campo para editar o cep do cliente -->
                <div class="inputBox">
                    <input type="text" name="cep" id="cep" class="inputUser  " value="<?php echo htmlspecialchars($cep); ?>" required>
                    <label for="cep" class="labelInput">CEP</label>
                </div>
                <br><br>
                <!-- Campo para editar o tipo do cliente -->
                <div class="inputBox">
                    <label for="tipo"><b>Tipo:</b></label>
                    <select name="tipo" id="tipo" class="inputUser  " required>
                        <option value="C" <?php echo ($tipo == 'C') ? 'selected' : ''; ?>>Cliente</option>
                        <option value="A" <?php echo ($tipo == 'A') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <br><br>
                <!-- Campo para editar a senha -->
                <div class="inputBox">
                    <input type="password" name="senha" id="senha" class="inputUser  " value="<?php echo htmlspecialchars($senha); ?>" required>
                    <label for="senha" class="labelInput">Senha</label>
                </div>
                <br><br>
                
                <input type="submit" name="update" id="submit">
            </fieldset>
        </form>
    </div>
</body>
</html>