<?php
// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    include_once('Conectar.php');

    // Função para limpar máscara
    function limparMascara($valor) {
        return preg_replace('/[^0-9]/', '', $valor);
    }

    // Função para formatar CNPJ
    function formatarCNPJ($cnpj) {
        $cnpj = limparMascara($cnpj);
        return (strlen($cnpj) == 14) ? preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) : false;
    }

    // Função para formatar telefone
    function formatarTelefone($telefone) {
        $telefone = limparMascara($telefone);
        return (strlen($telefone) == 11) ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $telefone) : false;
    }

    // Captura os dados do formulário
    $nome = trim($_POST['nome']);
    $capacidade = trim($_POST['capacidade']);
    $cnpj = intval(limparMascara(trim($_POST['cnpj'])));
    $telefone = intval(limparMascara(trim($_POST['telefone'])));
    $valor_hora = trim($_POST['valor_hora']);
    $valor_semanal = trim($_POST['valor_semanal']);
    $valor_mensal = trim($_POST['valor_mensal']);
    $cod_cliente = 1; // Exemplo de código do cliente, substitua conforme necessário

    // Verifica se há uma imagem e se o upload foi bem-sucedido
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem_temp = $_FILES['imagem']['tmp_name'];
        $imagem_nome = $_FILES['imagem']['name'];

        $destino = 'uploads/' . $imagem_nome;

        // Move o arquivo de imagem para o destino
        if (move_uploaded_file($imagem_temp, $destino)) {

            // Verifica se a conexão com o banco foi estabelecida
            if ($conexao) {

                // Verifica se o nome já está registrado no banco de dados
                $stmt = $conexao->prepare("SELECT nome FROM tb_estacionamentos WHERE nome = ?");
                $stmt->bind_param("s", $nome);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $mensagem = 'Este nome já está registrado.';
                } else {
                    $stmt->close();

                    // Insere os dados no banco de dados
                    $stmt = $conexao->prepare("INSERT INTO tb_estacionamentos 
                        (nome, capacidade_vaga, imagem, cnpj, telefone, valor_hora, valor_semanal, valor_mensal) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    if ($stmt) {
                        $stmt->bind_param(
                            "sissssss", 
                            $nome, 
                            $capacidade, 
                            $destino, 
                            $cnpj, 
                            $telefone, 
                            $valor_hora, 
                            $valor_semanal, 
                            $valor_mensal
                        );

                        // Verifica se a inserção foi bem-sucedida
                        if ($stmt->execute()) {
                            $cod_estacionamento = $conexao->insert_id; // Obtemos o ID do estacionamento inserido

                            // Inserir na tabela multivalorada tb_clientes_estacionamentos
                            $stmt_cliente_estacionamento = $conexao->prepare("INSERT INTO tb_clientes_estacionamentos (fk_cod_estacionamento, fk_cod_cliente) VALUES (?, ?)");
                            $stmt_cliente_estacionamento->bind_param("ii", $cod_estacionamento, $cod_cliente);

                            if ($stmt_cliente_estacionamento->execute()) {
                                $mensagem = 'Cadastro realizado com sucesso!';
                                header("Location: pg_do_cliente.php");
                                exit;
                            } else {
                                $mensagem = 'Erro ao inserir na tabela multivalorada: ' . $stmt_cliente_estacionamento->error;
                            }

                            $stmt_cliente_estacionamento->close();
                        } else {
                            $mensagem = 'Erro ao cadastrar: ' . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        $mensagem = 'Erro ao preparar a consulta: ' . $conexao->error;
                    }
                }
            } else {
                $mensagem = 'Erro ao conectar ao banco de dados.';
            }
        } else {
            $mensagem = 'Erro ao salvar a imagem.';
        }
    } else {
        $mensagem = 'Erro ao fazer o upload da imagem.';
    }

    // Fecha a conexão com o banco de dados
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Estacionamento</title>
    <link rel="stylesheet" href="cadastro.css">
    <script>
        // Função para aplicar a máscara de CNPJ
        function aplicarMascaraCNPJ(cnpj) {
            cnpj = cnpj.replace(/\D/g, ""); 
            cnpj = cnpj.replace(/(\d{2})(\d)/, "$1.$2"); 
            cnpj = cnpj.replace(/(\d{3})(\d)/, "$1.$2"); 
            cnpj = cnpj.replace(/(\d{3})(\d{1,4})/, "$1/$2"); 
            cnpj = cnpj.replace(/(\d{4})(\d{2})$/, "$1-$2"); 
            return cnpj;
        }

        // Função para aplicar a máscara de telefone
        function aplicarMascaraTelefone(telefone) {
            telefone = telefone.replace(/\D/g, ""); 
            telefone = telefone.replace(/^(\d{2})(\d)/g, "($1)$2"); 
            telefone = telefone.replace(/(\d{5})(\d{4})$/, "$1-$2"); 
            return telefone;
        }

        // Aplica a máscara quando o documento é carregado
        document.addEventListener("DOMContentLoaded", function() {
            const cnpjInput = document.querySelector('input[name="cnpj"]');
            const telefoneInput = document.querySelector('input[name="telefone"]');

            cnpjInput.addEventListener("input", function() {
                this.value = aplicarMascaraCNPJ(this.value);
            });

            telefoneInput.addEventListener("input", function() {
                this.value = aplicarMascaraTelefone(this.value);
            });
        });
    </script>
</head>
<body>
    <a href="index.php">Voltar</a>
    <div class="container">

        <!-- Formulário de cadastro -->
        <form class="cadastro-form" method="POST" enctype="multipart/form-data">
            <h2>Cadastro do Estacionamento</h2>

            <!-- Exibe mensagem de erro ou sucesso -->
            <?php if (isset($mensagem)): ?>
                <div class="alert <?= strpos($mensagem, 'sucesso') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <input type="text" name="nome" placeholder="Nome" required>
            <input type="number" name="capacidade" placeholder="Capacidade" required>

            <div class="upload-container">
                <label for="imagem">Imagem:</label>
                <input type="file" name="imagem" id="imagem" required>
            </div>
            
            <input type="text" name="cnpj" placeholder="CNPJ" required maxlength="18">
            <input type="text" name="telefone" placeholder="Telefone" required maxlength="13">
            <input type="text" name="valor_hora" placeholder="Valor Hora" required>
            <input type="text" name="valor_semanal" placeholder="Valor Semanal" required>
            <input type="text" name="valor_mensal" placeholder="Valor Mensal" required>
            <br><br>
            <button type="submit" name="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
