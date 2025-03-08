<?php
session_start();
include_once('Conectar.php');

// Verifica se o campo de e-mail está vazio
$email = $_POST["email"];
if (empty($email)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Campo de e-mail vazio.';
    header("Location: recuperar_senha.php");
    exit();
}

// Gera um token aleatório e calcula o hash
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // Define a expiração do token para 30 minutos

// Verifica se o e-mail está cadastrado
$sql_check_email = "SELECT email FROM tb_clientes WHERE email = ?";
$stmt_check_email = $conexao->prepare($sql_check_email);
if ($stmt_check_email === false) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao preparar a consulta de verificação de e-mail: ' . $conexao->error;
    header("Location: recuperar_senha.php");
    exit();
}

$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$stmt_check_email->store_result();

// Se o e-mail não foi encontrado, retorna mensagem de erro
if ($stmt_check_email->num_rows === 0) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'O E-mail informado não foi encontrado.';
    header("Location: recuperar_senha.php");
    exit();
}
$stmt_check_email->close();

// Atualiza o banco de dados com o token e a data de expiração
$sql = "UPDATE tb_clientes SET token_resetar = ?, token_expirar = ? WHERE email = ?";
$stmt = $conexao->prepare($sql);

if ($stmt === false) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao preparar a consulta de atualização: ' . $conexao->error;
    header("Location: recuperar_senha.php");
    exit();
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);
if ($stmt->execute() === false) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao executar a consulta de atualização: ' . $stmt->error;
    header("Location: recuperar_senha.php");
    exit();
} else {
    require __DIR__ . "/mailer.php"; // Inclui o arquivo de envio de e-mail
    sendPasswordResetEmail($email, $token); // Envia o e-mail de redefinição de senha
    $_SESSION['status'] = 'success';
    $_SESSION['message'] = 'E-mail enviado! Cheque sua caixa de entrada';
}

$stmt->close();
$conexao->close();

header("Location: recuperar_senha.php");
exit();
?>
