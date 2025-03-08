<?php
include_once('Conectar.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_estacionamento = $_POST['cod_estacionamento'];
    $nome = $_POST['nome'];
    $capacidade = $_POST['capacidade'];
    $cobertura = $_POST['cobertura']; // Corrigido para pegar a cobertura
    $ativo = $_POST['ativo'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone'];
    $valor_hora = $_POST['valor_hora'];
    $valor_semanal = $_POST['valor_semanal'];
    $valor_mensal = $_POST['valor_mensal'];
    
    $imagem = null;

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['nova_imagem']) && $_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'C:/xampp/htdocs/TCC/uploads/';
        $timestamp = time();
        $imagemNomeOriginal = basename($_FILES['nova_imagem']['name']);
        $extensao = pathinfo($imagemNomeOriginal, PATHINFO_EXTENSION);
        
        // Nome único para a imagem
        $imagemNomeUnico = $cod_estacionamento . '_' . $timestamp . '_' . $imagemNomeOriginal;
        $imagem = 'uploads/' . $imagemNomeUnico;
        $uploadFilePath = $uploadDir . $imagemNomeUnico;

        // Move o arquivo para o diretório de upload
        if (!move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $uploadFilePath)) {
            die("Erro ao mover o arquivo de imagem.");
        }
    }

    // Prepara a consulta SQL
    $sql = "UPDATE tb_estacionamentos SET nome=?, capacidade_vaga=?, cobertura=?, ativo=?, cnpj=?, telefone=?, valor_hora=?, valor_semanal=?, valor_mensal=?";
    $params = [$nome, $capacidade, $cobertura, $ativo, $cnpj, $telefone, $valor_hora, $valor_semanal, $valor_mensal];
    
    // Se uma nova imagem foi enviada, adiciona ao SQL
    if ($imagem) {
        $sql .= ", imagem=?";
        $params[] = $imagem;
    }
    $sql .= " WHERE cod_estacionamento=?";
    $params[] = $cod_estacionamento;

    // Prepara e executa a consulta
    $stmt = $conexao->prepare($sql);
    
    // O tipo de binding deve ser ajustado para incluir a quantidade correta de parâmetros
    $types = str_repeat('s', count($params) - 1) . 'i'; // 's' para string, 'i' para integer
    $stmt->bind_param($types, ...$params);

    // Executa a consulta
    if ($stmt->execute()) {
        header('Location: Sistema_estaci.php'); 
        exit;
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close(); 
} else {
    header('Location: Sistema_estaci.php'); 
    exit;
}
?>