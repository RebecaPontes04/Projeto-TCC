<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php"; // Inclui o autoload do Composer para o PHPMailer

function sendPasswordResetEmail($email, $token_hash)
{
    // Instanciar PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP(); // Configura o PHPMailer para usar SMTP
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Desativa a saída de debug
        $mail->SMTPAuth = true; // Ativa a autenticação SMTP
        $mail->Host = 'smtp.gmail.com'; // Define o servidor SMTP
        $mail->Port = 465; // Define a porta SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usa SSL
        $mail->Username = 'cityestaciona@gmail.com'; // Usuário do SMTP
        $mail->Password = 'pkgruhdoscdwjujz'; // Senha do SMTP
        $mail->setFrom("noreply@example.com"); // Define o remetente
        $mail->addAddress($email); // Adiciona o destinatário
        $mail->Subject = "Redefinir Senha"; // Define o assunto do e-mail
        $mail->isHTML(true); // Define o formato do e-mail como HTML

        // Corpo do e-mail com HTML
        $mail->Body = <<<END
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .message {
            margin: 20px 0;
            line-height: 1.6;
            color: #333;
        }
        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Redefinição de Senha</h2>
        </div>
        <div class="message">
            <p>Prezado(a) usuário(a),</p>
            <p>Recebemos um pedido de redefinição de senha.</p>
            <p>Segue o link abaixo para definir uma nova senha:</p>
            <p><a href="http://localhost/nova_senha.php?token=$token_hash">Redefinir senha</a></p>
            <p>Caso não tenha feito essa solicitação, desconsidere este e-mail.</p>
        </div>
    </div>
</body>
</html>
END;

        $mail->send(); // Envia o e-mail
        echo "Mensagem enviada, verifique sua caixa de entrada.";
    } catch (Exception $e) {
        echo "Ocorreu um erro ao enviar o e-mail. Tente novamente mais tarde."; // Mensagem de erro
    }
}
