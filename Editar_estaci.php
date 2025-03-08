<?php
include_once('Conectar.php');

// Verifica se o parâmetro cod_estacionamento foi fornecido na URL
if (!empty($_GET['cod_estacionamento'])) {
    $cod_estacionamento = $_GET['cod_estacionamento'];
    if ($conexao) {
        $stmt = $conexao->prepare("SELECT * FROM tb_estacionamentos WHERE cod_estacionamento = ?");
        $stmt->bind_param("i", $cod_estacionamento); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 

        // Verifica se a consulta retornou algum resultado
        if ($result->num_rows > 0) {
            // Obtém os dados do estacionamento
            $user_data = $result->fetch_assoc(); 
            $nome = $user_data['nome'];
            $capacidade = $user_data['capacidade_vaga'];
            $cobertura = $user_data['cobertura']; // Corrigido para pegar a coluna correta
            $imagem = $user_data['imagem'];
            $ativo = $user_data['ativo'];
            $cnpj = $user_data['cnpj'];
            $telefone = $user_data['telefone'];
            $valor_hora = $user_data['valor_hora'];
            $valor_semanal = $user_data['valor_semanal'];
            $valor_mensal = $user_data['valor_mensal'];
        } else {
            header('Location: Sistema_estaci.php');
            exit;
        }
        $stmt->close();
    } else {
        // Exibe mensagem de erro se a conexão falhar
        die("Erro de conexão com o banco de dados.");
    }
} else {
    // Redireciona para a página Sistema_estaci.php se o parâmetro não for fornecido
    header('Location: Sistema_estaci.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <link rel="stylesheet" href="Editar.css">
</head>
<body>
    <a href="Sistema_estaci.php">Voltar</a>
    <div class="box">
        <form action="SalvarEdit_estaci.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <h2><b>Editar Estacionamento</b></h2>
                <br>
                <!-- Campo oculto para armazenar o código do estacionamento -->
                <input type="hidden" name="cod_estacionamento" value="<?php echo htmlspecialchars($cod_estacionamento); ?>">
                
                <!-- Campo para editar o nome do estacionamento -->
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser " value="<?php echo htmlspecialchars($nome); ?>" required>
                    <label for="nome" class="labelInput">Nome do Estacionamento</label>
                </div>
                <br><br>
                <!-- Campo para editar a capacidade do estacionamento -->
                <div class="inputBox">
                    <input type="number" name="capacidade" id="capacidade" class="inputUser " value="<?php echo htmlspecialchars($capacidade); ?>" required>
                    <label for="capacidade" class="labelInput">Capacidade</label>
                </div>
                <br><br>
                <!-- Campo para indicar se o estacionamento é sem cobertura -->
                <div class="inputBox">
                    <select name="cobertura" id="cobertura" class="inputUser " required>
                        <option value="SEM" <?php echo ($cobertura == 'SEM') ? 'selected' : ''; ?>>Sem Cobertura</option>
                        <option value="COM" <?php echo ($cobertura == 'COM') ? 'selected' : ''; ?>>Com Cobertura</option>
                    </select>
                    <label for="cobertura" class="labelInput">Cobertura</label>
                </div>
                <br><br>

                <!-- Campo para fazer upload de uma nova imagem -->
                <div class="inputBox">
                    <input type="file" name="nova_imagem" id="nova_imagem" class="inputUser ">
                    <label for="nova_imagem " class="labelInput">Imagem</label>
                </div>
                <br><br>

                <!-- Campo para indicar se o estacionamento está ativo -->
                <div class="inputBox">
                    <select name="ativo" id="ativo" class="inputUser  " required>
                        <option value="S" <?php echo ($ativo == 'S') ? 'selected' : ''; ?>>Sim</option>
                        <option value="N" <?php echo ($ativo == 'N') ? 'selected' : ''; ?>>Não</option>
                    </select>
                    <label for="ativo" class="labelInput">Ativo</label>
                </div>
                <br><br>
                <!-- Campo para editar o CNPJ do estacionamento -->
                <div class="inputBox">
                    <input type="text" name="cnpj" id="cnpj" class="inputUser  " value="<?php echo htmlspecialchars($cnpj); ?>" required>
                    <label for="cnpj" class="labelInput">CNPJ</label>
                </div>
                <br><br>
                <!-- Campo para editar o telefone do estacionamento -->
                <div class="inputBox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser  " value="<?php echo htmlspecialchars($telefone); ?>" required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>
                <br><br>
                <!-- Campo para editar o valor por hora do estacionamento -->
                <div class="inputBox">
                    <input type="number" step="0.01" name="valor_hora" id="valor_hora" class="inputUser  " value="<?php echo htmlspecialchars($valor_hora); ?>" required>
                    <label for="valor_hora" class="labelInput">Valor Hora</label>
                </div>
                <br><br>
                <!-- Campo para editar o valor semanal do estacionamento -->
                <div class="inputBox">
                    <input type="number" step="0.01" name="valor_semanal" id="valor_semanal" class="inputUser  " value="<?php echo htmlspecialchars($valor_semanal); ?>" required>
                    <label for="valor_semanal" class="labelInput">Valor Semanal</label>
                </div>
                <br><br>
                <!-- Campo para editar o valor mensal do estacionamento -->
                <div class="inputBox">
                    <input type="number" step="0.01" name="valor_mensal" id="valor_mensal" class="inputUser  " value="<?php echo htmlspecialchars($valor_mensal); ?>" required>
                    <label for="valor_mensal" class="labelInput">Valor Mensal</label>
                </div>
                <br><br>
                
                <input type="submit" name="update" id="submit" value="Salvar">
            </fieldset>
        </form>
    </div>
</body>
</html>