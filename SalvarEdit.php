<?php
include_once('Conectar.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $cod_cliente = $_POST['cod_cliente'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $ativo = $_POST['ativo'];
    $cpf = $_POST['cpf'];
    $data_nasc = $_POST['data_nasc'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $tipo = $_POST['tipo'];
    $senha = $_POST['senha'];
    
    // Prepared statement para evitar SQL Injection
    $stmt = $conexao->prepare("UPDATE tb_clientes SET nome=?, email=?, ativo=?, cpf=?, data_nasc=?, telefone=?, cep=?, tipo=?, senha=MD5('$senha') WHERE cod_cliente=?");
    $stmt->bind_param("ssssssssi", $nome, $email, $ativo, $cpf, $data_nasc, $telefone, $cep, $tipo, $cod_cliente);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        header('Location: sistema.php'); // Redireciona para a página principal em caso de sucesso
        exit;
    } else {
        echo "Erro: " . $stmt->error; // Exibe o erro, se houver
    }

    $stmt->close(); // Fecha o statement
} else {
    header('Location: sistema.php'); // Redireciona se não for uma requisição POST
    exit;
}
?>
