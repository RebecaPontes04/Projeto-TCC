<?php
session_start();

$token = $_POST["token"];
$token_hash = hash("sha256", $token);
$mysqli = require __DIR__ . "/Conectar.php";

$sql = "SELECT * FROM tb_clientes WHERE token_resetar = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = "";

if ($user === null) {
    $message = "Token não encontrado.";
} elseif (strtotime($user["token_expirar"]) <= time()) {
    $message = "Token expirou.";
} elseif (strlen($_POST["password"]) < 5) {
    $message = "A senha deve conter 5 ou mais caracteres.";
} elseif ($_POST["password"] !== $_POST["password_confirmation"]) {
    $message = "Senhas não são idênticas.";
} else {
    // Mantém o uso de md5 por causa do banco
    $password_hash = md5($_POST["password"]);
    
    $sql = "UPDATE tb_clientes SET senha = ?, token_resetar = NULL, token_expirar = NULL WHERE cod_cliente = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $password_hash, $user["cod_cliente"]);
    $stmt->execute();

    if ($stmt) {  // Verifica se a execução foi bem-sucedida
        $message = "Senha atualizada.";
        // Redirecionar para a página de login após o sucesso
        header("Location: login.php");
        exit();
    } else {
        $message = "Falha ao atualizar a senha.";
    }
}

$stmt->close();
$mysqli->close();
$_SESSION['message'] = $message;

// Redireciona de volta para a página de redefinição de senha com a mensagem
header("Location: nova_senha.php?token=" . urlencode($token));
exit();
?>
