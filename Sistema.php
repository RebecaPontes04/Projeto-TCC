<?php
session_start();
include_once('Conectar.php');

// Verifica se as variáveis de sessão 'email' e 'senha' estão definidas
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

// Exibe mensagem de ação realizada
if (isset($_SESSION['mensagem'])) {
    $tipo_mensagem = $_SESSION['tipo_mensagem'] ?? '';
    $classe_alerta = ($tipo_mensagem === 'sucesso') ? 'alert-success' : 'alert-danger';
    echo "<div class='alert $classe_alerta' role='alert'>
            {$_SESSION['mensagem']}
          </div>";
    unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
}

// Armazena o email da sessão em uma variável
$email = $_SESSION['email'];

// Verifica se a busca foi feita
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM tb_clientes WHERE cod_cliente LIKE '%$data%' OR nome LIKE '%$data%' ORDER BY cod_cliente ASC";
} else {
    $sql = "SELECT * FROM tb_clientes ORDER BY cod_cliente ASC";
}

$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>CITY CLIENTES</title>
    <link rel="stylesheet" href="Sistema.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a href="adm.php">Voltar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex">
                <a href="Sair.php" class="btn btn-danger me-5">Sair</a>
            </div>
        </div>
    </nav>

    <br><br>

    <!-- Caixa de pesquisa -->
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
        </button>
    </div>

    <div class="m-5">
        <!-- Tabela de clientes -->
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Data Nasc.</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">CEP</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Botões</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop para exibir os dados de cada cliente
                while ($cliente_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $cliente_data['cod_cliente'] . "</td>";
                    echo "<td>" . $cliente_data['nome'] . "</td>";
                    echo "<td>" . $cliente_data['email'] . "</td>";
                    echo "<td>" . ($cliente_data['ativo'] == 'S' ? 'Sim' : 'Não') . "</td>";
                    echo "<td>" . str_pad($cliente_data['cpf'], 11, '0', STR_PAD_LEFT) . "</td>";
                    echo "<td>" . $cliente_data['data_nasc'] . "</td>";
                    echo "<td>" . $cliente_data['telefone'] . "</td>";
                    echo "<td>" . str_pad($cliente_data['cep'], 10, '0', STR_PAD_LEFT) . "</td>";
                    echo "<td>" . ($cliente_data['tipo'] == 'C' ? 'Cliente' : 'Admin') . "</td>";
                    echo "<td>" . $cliente_data['senha'] . "</td>";
                    echo "<td>";
                    echo "<a class='btn btn-sm btn-primary' href='Editar.php?cod_cliente=" . $cliente_data['cod_cliente'] . "'>";
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' viewBox='0 0 24 24'>";
                    echo "<path d='M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z'/>";
                    echo "</svg>";
                    echo "</a>";
                    echo "<a class='btn btn-sm btn-danger' href='Deletar.php?cod_cliente=" . $cliente_data['cod_cliente'] . "' onclick='return confirm(\"Tem certeza que deseja deletar este cliente?\");'>";
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' fill='currentColor' class='bi bi-trash-fill'>";
                    echo "<path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zM5 5v7a.5.5 0 0 1-1 0V5a.5.5 0 0 1 1 0zm3 0v7a.5.5 0 0 1-1 0V5a.5.5 0 0 1 1 0zm3 0v7a.5.5 0 0 1-1 0V5a.5.5 0 0 1 1 0z'/>";
                    echo "</svg>";
                    echo "</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

<script>
    // Função de pesquisa
    var search = document.getElementById('pesquisar');
    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            searchData();
        }
    });

    function searchData() {
        window.location = 'Sistema.php?search=' + encodeURIComponent(search.value);
    }
</script>

</html>