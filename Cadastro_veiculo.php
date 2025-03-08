<?php
session_start();
include_once('Conectar.php');

// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    function limparPlaca($placa) {
        return preg_replace('/[^A-Za-z0-9]/', '', $placa);
    }

    $tipo_veiculo = $_POST['tipo_veiculo'];
    $placa = limparPlaca(trim($_POST['placa']));
    $modelo = trim($_POST['modelo']);
    $imagem = $_FILES['imagem'];
    $mensagem = '';
    $sucesso = false;
    $ativo = 'S'; // Define o veículo como ativo

    // Verifica se o cod_cliente está definido na sessão
    if (!isset($_SESSION['cod_cliente']) || empty($_SESSION['cod_cliente'])) {
        die('Erro: Código do cliente não encontrado na sessão.');
    }
    $cod_cliente = $_SESSION['cod_cliente'];

    if ($conexao) {
        // Verifica se a placa já está cadastrada
        $stmt = $conexao->prepare("SELECT placa FROM tb_veiculos WHERE placa = ?");
        if (!$stmt) {
            die("Erro ao preparar consulta: " . $conexao->error);
        }
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensagem = 'Este veículo já está registrado.';
        } else {
            $stmt->close();

            // Verifica e processa o upload da imagem
            if (isset($imagem) && $imagem['error'] === UPLOAD_ERR_OK) {
                $imagem_temp = $imagem['tmp_name'];
                $imagem_nome = uniqid() . '_' . $imagem['name'];
                $imagem = 'uploads/' . $imagem_nome;

                if (move_uploaded_file($imagem_temp, $imagem)) {
                    // Insere o veículo no banco de dados
                    $sql = "INSERT INTO tb_veiculos (tipo_veiculo, placa, modelo, imagem, cod_cliente, ativo) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conexao->prepare($sql);

                    if (!$stmt) {
                        die("Erro ao preparar consulta: " . $conexao->error);
                    }

                    $stmt->bind_param("ssssss", $tipo_veiculo, $placa, $modelo, $imagem, $cod_cliente, $ativo);

                    if ($stmt->execute()) {
                        $mensagem = 'Veículo cadastrado com sucesso!';
                        $sucesso = true;
                    } else {
                        $mensagem = 'Erro ao cadastrar veículo: ' . addslashes($stmt->error);
                    }
                } else {
                    $mensagem = 'Erro ao salvar a imagem.';
                }
            } else {
                $mensagem = 'Erro ao fazer o upload da imagem.';
            }
        }

        $stmt->close();
    } else {
        $mensagem = 'Erro ao conectar ao banco de dados.';
    }

    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Veículo</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <a href="index.php">Voltar</a>
    <div class="container">
        <form class="cadastro-form" method="POST" enctype="multipart/form-data">
            <h2>Cadastro do Veículo</h2>

            <?php if (isset($mensagem)): ?>
                <div class="alert <?= strpos($mensagem, 'sucesso') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <br><br>
            Tipo de Veículo:
            <select name="tipo_veiculo" required>
                <option value="Carro">Carro</option>
                <option value="Moto">Moto</option>
            </select>
            <input type="text" name="placa" placeholder="Placa" maxlength="8" oninput="formatarPlaca(this)" required>
            <input type="text" name="modelo" placeholder="Modelo" required>
            <div class="upload-container">
                <label for="imagem">Imagem:</label>
                <input type="file" name="imagem" id="imagem" required>
            </div>
            <br><br>

            <button type="submit" name="submit">Enviar</button>
        </form>
    </div>

    <?php if (isset($sucesso) && $sucesso): ?>
        <script>
            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000);
        </script>
    <?php endif; ?>
</body>
<script>
function formatarPlaca(input) {
    let placa = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    if (placa.length <= 3) {
        input.value = placa;
    } else if (placa.length <= 7) {
        input.value = placa.slice(0, 3) + '-' + placa.slice(3);
    } else {
        input.value = placa.slice(0, 3) + '-' + placa.slice(3, 7);
    }
}
</script>
</html>
