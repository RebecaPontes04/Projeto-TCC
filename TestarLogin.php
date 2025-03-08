<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('Conectar.php');

    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $sql = "SELECT cod_cliente, nome, tipo FROM tb_clientes WHERE email = ? AND senha = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        $_SESSION['login_error'] = 'Usuário não cadastrado ou senha incorreta.';
        header('Location: Login.php');
        exit();
    } else {
        $user = $result->fetch_assoc();
        $_SESSION['cod_cliente'] = $user['cod_cliente'];
        $_SESSION['nome_cliente'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];
        $_SESSION['email'] = $email; // Salva o email na sessão
        $_SESSION['senha'] = $senha; // Salva a senha na sessão

        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['login_error'] = 'Preencha todos os campos.';
    header('Location: Login.php');
    exit();
}
?>
