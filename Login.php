<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link rel="stylesheet" href="Login.css"> 
</head>
<body>
    <a href="index.php">Voltar</a> 
    
    <div>
        <h1>Login</h1>

        <?php
        include_once('Conectar.php');
        session_start(); // Inicia a sessão para verificar mensagens de erro

        // Exibe a mensagem de erro, se existir
        if (isset($_SESSION['login_error'])) {
            echo "<p style='color:red;'>" . $_SESSION['login_error'] . "</p>";
            unset($_SESSION['login_error']); // Limpa a mensagem de erro após exibí-la
        }
        ?>

        <!-- Formulário de login -->
        <form action="TestarLogin.php" method="POST">
            <input type="text" name="email" placeholder="Email" required> 
            <br><br>
            <input type="password" name="senha" placeholder="Senha" required>
            <br><br>
            <input class="inputSubmit" type="submit" name="submit" value="Enviar"> 
        </form>

        <p><a href="recuperar_senha.php" style="color: blue; text-decoration: underline;">Esqueceu a senha?</a></p>
    </div>
</body>
</html>
