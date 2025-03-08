<?php
include_once('Conectar.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_veiculo = $_POST['cod_veiculo'];
    $tipo_veiculo = $_POST['tipo_veiculo'];
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $ativo = $_POST['ativo'];
    
    // Inicializa a variável imagem com null
    $imagem = null;

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['nova_imagem']) && $_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'C:/xampp/htdocs/TCC/uploads/';
        $timestamp = time();
        $imagemNomeOriginal = basename($_FILES['nova_imagem']['name']);
        $extensao = pathinfo($imagemNomeOriginal, PATHINFO_EXTENSION);
        
        // Nome único para a imagem
        $imagemNomeUnico = $cod_veiculo . '_' . $timestamp . '_' . $imagemNomeOriginal;
        $imagem = 'uploads/' . $imagemNomeUnico;
        $uploadFilePath = $uploadDir . $imagemNomeUnico;

        // Move o arquivo para o diretório de upload
        if (!move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $uploadFilePath)) {
            die("Erro ao mover o arquivo de imagem.");
        }
    } else {
        // Se não houver nova imagem, mantemos o valor atual da imagem
        // Consulta para pegar o valor da imagem atual
        $stmt = $conexao->prepare("SELECT imagem FROM tb_veiculos WHERE cod_veiculo = ?");
        $stmt->bind_param("s", $cod_veiculo);
        $stmt->execute();
        $stmt->bind_result($imagem_atual);
        $stmt->fetch();
        $stmt->close();

        // Se a imagem não foi enviada, usa a imagem atual
        $imagem = $imagem_atual;
    }

    // Prepara a consulta SQL
    $sql = "UPDATE tb_veiculos SET tipo_veiculo=?, placa=?, modelo=?, ativo=?, imagem=? WHERE cod_veiculo=?";
    $params = [$tipo_veiculo, $placa, $modelo, $ativo, $imagem, $cod_veiculo];

    // Prepara e executa a consulta
    $stmt = $conexao->prepare($sql);
    
    // O tipo de binding deve ser ajustado para incluir a quantidade correta de parâmetros
    $types = 'ssssss'; // Todos os parâmetros são do tipo string, exceto o cod_veiculo, que é inteiro
    $stmt->bind_param($types, ...$params);

    // Executa a consulta
    if ($stmt->execute()) {
        header('Location: Sistema_veiculo.php'); 
        exit;
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close(); 
} else {
    header('Location: Sistema_veiculo.php'); 
    exit;
}
?>
