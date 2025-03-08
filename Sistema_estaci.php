<?php
session_start();
include_once('Conectar.php');

if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['email'], $_SESSION['senha']);
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['mensagem'])) {
    $tipo_mensagem = $_SESSION['tipo_mensagem'] ?? '';
    $classe_alerta = ($tipo_mensagem === 'sucesso') ? 'alert-success' : 'alert-danger';
    echo "<div class='alert $classe_alerta' role='alert'>
            {$_SESSION['mensagem']}
          </div>";
    unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
}

$email = $_SESSION['email'];

if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM tb_estacionamentos WHERE cod_estacionamento LIKE '%$data%' OR nome LIKE '%$data%' ORDER BY cod_estacionamento ASC";
} else {
    $sql = "SELECT * FROM tb_estacionamentos ORDER BY cod_estacionamento ASC";
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
    <title>CITY ESTACIONA</title>
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
            </div>
        </div>
    </nav>

    <br><br>

    <!-- Caixa de pesquisa -->
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>

    <div class="m-5">
        <!-- Tabela de estacionamentos -->
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Capacidade</th>
                    <th scope="col">Cobertura</th>
                    <th scope="col">Imagem</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">CNPJ</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Valor Hora</th>
                    <th scope="col">Valor Semanal</th>
                    <th scope="col">Valor Mensal</th>
                    <th scope="col">Botões</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($estac_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $estac_data['cod_estacionamento'] . "</td>";
                    echo "<td>" . $estac_data['nome'] . "</td>";
                    echo "<td>" . $estac_data['capacidade_vaga'] . "</td>";
                    echo "<td>" . $estac_data['cobertura'] . "</td>";
                    echo "<td><img src='" . $estac_data['imagem'] . "' alt='Imagem' style='width: 100px; height: auto;'></td>";
                    echo "<td>" . ($estac_data['ativo'] == 'S' ? 'Sim' : 'Não') . "</td>"; // Exibindo 'Sim' ou 'Não'
                    echo "<td>" . str_pad($estac_data['cnpj'], 14, '0', STR_PAD_LEFT) . "</td>"; // Formatando CNPJ
                    echo "<td>" . str_pad($estac_data['telefone'], 11, '0', STR_PAD_LEFT) . "</td>"; // Formatando Telefone
                    echo "<td>R$ " . number_format($estac_data['valor_hora'], 2, ',', '.') . "</td>"; // Formatando Valor Hora
                    echo "<td>R$ " . number_format($estac_data['valor_semanal'], 2, ',', '.') . "</td>"; // Formatando Valor Semanal
                    echo "<td>R$ " . number_format($estac_data['valor_mensal'], 2, ',', '.') . "</td>"; // Formatando Valor Mensal
                    echo "<td>";
                    echo "<a class='btn btn-sm btn-primary' href='Editar_estaci.php?cod_estacionamento=" . $estac_data['cod_estacionamento'] . "'>Editar</a>";
                    echo "<a class='btn btn-sm btn-danger' href='Deletar_estaci.php?cod_estacionamento=" . $estac_data['cod_estacionamento'] . "' onclick='return confirm(\"Tem certeza que deseja deletar este estacionamento?\");'>Deletar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

<script>
var search = document.getElementById('pesquisar');
search.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        searchData();
    }
});

function searchData() {
    window.location = 'Sistema_estaci.php?search=' + encodeURIComponent(search.value);
}
</script>

</html>