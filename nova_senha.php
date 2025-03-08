<?php
session_start(); 
$mysqli = require __DIR__ . "/conectar.php"; // Inclui o arquivo de conexão ao banco de dados
$message = isset($_SESSION['message']) ? $_SESSION['message'] : ''; // Recupera a mensagem da sessão, se existir
unset($_SESSION['message']); // Remove a mensagem da sessão
$token = $_GET["token"]; // Obtém o token da URL
$token_hash = hash("sha256", $token); // Cria um hash SHA256 do token
$sql = "SELECT * FROM tb_clientes WHERE token_resetar = ?"; // Consulta SQL para buscar usuário pelo token
$stmt = $mysqli->prepare($sql); 

if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $mysqli->error); // Verifica se a preparação falhou
}

$stmt->bind_param("s", $token_hash); // Liga o parâmetro do token
$stmt->execute();
$result = $stmt->get_result(); // Obtém o resultado
$user = $result->fetch_assoc(); // Busca os dados do usuário

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="senhas.css"> 
</head>
<body>
    <!-- Botão "Voltar" no canto superior esquerdo -->
    <a href="index.php" class="back-button">Voltar</a>

    <div class="login-container">
        <h1>Redefinir Senha</h1>

        <!-- Exibe a mensagem de sucesso ou erro, se existir -->
        <?php if ($message): ?>
            <p class="message <?= strpos($message, 'Falha') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
            <div id="countdown"></div> <!-- Exibe o contador de redirecionamento -->
        <?php endif; ?>

        <form method="post" action="processo_resetar_senha.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>"> <!-- Token oculto para validação -->

            <input type="password" name="password" class="input-field" placeholder="Senha nova" required> 
            <br><br>
            
            <input type="password" name="password_confirmation" class="input-field" placeholder="Confirmar senha" required> 
            <br><br>
            
            <input class="inputSubmit" type="submit" name="submit" value="Enviar"> 
        </form>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = 'login.php'; 
        }

        window.onload = function() {
            const message = '<?= addslashes($message) ?>'; // Obtém a mensagem da variável PHP
            if (message.includes("Senha atualizada")) { // Verifica se a mensagem contém "Senha atualizada"
                let countdown = 3; // Tempo de contagem regressiva
                const countdownDisplay = document.getElementById("countdown"); 
                countdownDisplay.innerText = `Redirecionando...`; 
                const interval = setInterval(function() {
                    countdown--;
                    if (countdown <= 0) { 
                        clearInterval(interval);
                        redirectToLogin();
                    }
                    countdownDisplay.innerText = `Redirecionando em ${countdown}...`; // Atualiza a mensagem do countdown
                }, 1000); 
            }
        };
    </script>
</body>
</html>
