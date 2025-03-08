<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Login.css">
    <title>Recuperar Senha</title>
</head>
<body>
    <a href="index.php">Voltar</a>

    <div>
        <h1>Recuperar Senha</h1>

        <?php
        // Exibe a mensagem armazenada na sessão, se houver
        if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
            if ($_SESSION['status'] === 'error') {
                echo '<p style="color: red;">' . $_SESSION['message'] . '</p>';
            } elseif ($_SESSION['status'] === 'success') {
                echo '<p style="color: green;">' . $_SESSION['message'] . '</p>';
            }
            // Limpa a mensagem após exibi-la
            unset($_SESSION['status']);
            unset($_SESSION['message']);
        }
        ?>

        <form method="post" action="enviar_reset_senha.php">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <br><br>
            <button class="inputSubmit" type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
