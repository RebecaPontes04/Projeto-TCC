<?php
if (isset($_POST['submit'])) {
    include_once('Conectar.php');

    function limparMascara($valor) {
        return preg_replace('/[^0-9]/', '', $valor);
    }

    function validarData($data) {
        $dataFormatada = DateTime::createFromFormat('Y-m-d', $data);
        return $dataFormatada && $dataFormatada->format('Y-m-d') === $data;
    }

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cpf = limparMascara(trim($_POST['cpf'])); 
    $data_nasc = $_POST['data_nasc'];
    $telefone = limparMascara(trim($_POST['telefone']));
    $cep = limparMascara(trim($_POST['cep']));
    $senha = trim($_POST['senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);
    $senhaCriptografada = md5($senha);

    $mensagem = ""; 
    $tipoMensagem = "error"; 

    if (!validarData($data_nasc) || $data_nasc < '1935-01-01' || $data_nasc > '2006-01-01') {
        $mensagem = 'Data de nascimento inválida. Permitido apenas entre 1935 e 2006.';
    } elseif ($senha !== $confirmar_senha) {
        $mensagem = 'As senhas não coincidem.';
    } elseif (strlen($cpf) < 11) {
        $mensagem = 'CPF incompleto. Deve conter 11 dígitos.';
    } elseif (strlen($telefone) < 11) {
        $mensagem = 'Telefone incompleto. Deve conter 11 dígitos com DDD.';
    } elseif (strlen($cep) < 8) {
        $mensagem = 'CEP incompleto. Deve conter 8 dígitos.';
    } else {
        // Verificação de duplicidade no banco de dados
        $queryCheck = "SELECT * FROM tb_clientes WHERE cpf = '$cpf' OR telefone = '$telefone' OR email = '$email'";
        $resultadoCheck = $conexao->query($queryCheck);

        if ($resultadoCheck->num_rows > 0) {
            $row = $resultadoCheck->fetch_assoc();

            if ($row['cpf'] === $cpf) {
                $mensagem = 'CPF já cadastrado.';
            } elseif ($row['telefone'] === $telefone) {
                $mensagem = 'Telefone já cadastrado.';
            } elseif ($row['email'] === $email) {
                $mensagem = 'E-mail já cadastrado.';
            }
        } else {
            // Caso não haja duplicatas, insere o registro
            $queryInsert = "INSERT INTO tb_clientes (nome, email, cpf, data_nasc, telefone, cep, senha, ativo) 
                            VALUES ('$nome', '$email', '$cpf', '$data_nasc', '$telefone', '$cep', '$senhaCriptografada', 'S')";
            if ($conexao->query($queryInsert) === TRUE) {
                $mensagem = "Cadastro realizado com sucesso!";
                $tipoMensagem = "success";

                // Limpa os valores do formulário
                $nome = $email = $cpf = $data_nasc = $telefone = $cep = $senha = $confirmar_senha = null;
            } else {
                $mensagem = "Erro ao cadastrar: " . $conexao->error;
            }
        }
    }
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <a href="index.php">Voltar</a>
    <div class="container">
        <form class="cadastro-form" method="POST" id="formCadastro">
            <h2>Cadastro do Cliente</h2>
            
            <!-- Exibe mensagem de erro ou sucesso se houver -->
            <?php if (!empty($mensagem)): ?>
                <div id="mensagemErro" class="alert alert-<?= $tipoMensagem; ?>">
                    <?= $mensagem; ?>
                </div>
            <?php endif; ?>

            <!-- Campos do formulário -->
            <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($nome ?? ''); ?>" required>
            <input type="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($email ?? ''); ?>" required>
            <input type="text" name="cpf" placeholder="CPF" maxlength="11" value="<?= htmlspecialchars($cpf ?? ''); ?>" required>
            <input type="date" name="data_nasc" value="<?= htmlspecialchars($data_nasc ?? ''); ?>" required>
            <input type="text" name="telefone" placeholder="Telefone" maxlength="11" value="<?= htmlspecialchars($telefone ?? ''); ?>" required>
            <input type="text" name="cep" placeholder="CEP" maxlength="8" value="<?= htmlspecialchars($cep ?? ''); ?>" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
            <button type="submit" name="submit">Cadastrar</button>
        </form>
    </div>
</body>

<script>
function aplicarMascaraCPF(cpf) {
    cpf = cpf.replace(/\D/g, ""); 
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); 
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); 
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); 
    return cpf;
}

function aplicarMascaraTelefone(telefone) {
    telefone = telefone.replace(/\D/g, ""); 
    telefone = telefone.replace(/^(\d{2})(\d)/g, "($1)$2");
    telefone = telefone.replace(/(\d{5})(\d{4})$/, "$1-$2"); 
    return telefone;
}

function aplicarMascaraCEP(cep) {
    cep = cep.replace(/\D/g, "");
    cep = cep.replace(/(\d{5})(\d{3})$/, "$1-$2");
    return cep;
}

document.addEventListener("DOMContentLoaded", function() {
    const cpfInput = document.querySelector('input[name="cpf"]');
    const telefoneInput = document.querySelector('input[name="telefone"]');
    const cepInput = document.querySelector('input[name="cep"]');

    cpfInput.addEventListener("input", function() {
        this.value = aplicarMascaraCPF(this.value);
    });

    telefoneInput.addEventListener("input", function() {
        this.value = aplicarMascaraTelefone(this.value);
    });

    cepInput.addEventListener("input", function() {
        this.value = aplicarMascaraCEP(this.value);
    });

    // Redireciona após 2 segundos se houver mensagem de sucesso
    <?php if ($tipoMensagem === 'success'): ?>
        setTimeout(function() {
            window.location.href = 'login.php'; 
        }, 2000);
    <?php endif; ?>
});
</script>
</html>