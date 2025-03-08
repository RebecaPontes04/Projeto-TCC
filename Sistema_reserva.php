<?php
session_start();
include_once('Conectar.php');

// Verifique se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Verifique se o usuário tem permissão (tipo 'A' para administrador)
if ($_SESSION['tipo'] !== 'A') {
    echo "<h1>Acesso negado</h1>";
    exit;
}

$email = $_SESSION['email'];

// Consulta com ou sem filtro de pesquisa
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM tb_reservas WHERE cod_reserva LIKE '%$data%' OR fk_cod_cliente LIKE '%$data%' ORDER BY cod_reserva DESC";
} else {
    $sql = "SELECT * FROM tb_reservas ORDER BY cod_reserva ASC";
}

$result = $conexao->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclui o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>CITY ESTACIONA</title>
    <!-- Inclui o CSS personalizado -->
    <link rel="stylesheet" href="Sistema.css">
</head>
<body>
    <!-- Navbar do site -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
        <a href="adm.php">Voltar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex">
            </div>
        </div>
    </nav>
    <br>
    <br>

    <!-- Exibe mensagem de sucesso ou erro apenas se houver redirecionamento após uma ação -->
    <?php if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $_SESSION['mensagem']; ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <!-- Caixa de pesquisa -->
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary">
            <!-- Ícone de pesquisa -->
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>
    
    <div class="m-5">
        <!-- Tabela para exibir os dados das reservas -->
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">Cod Cliente</th>
                    <th scope="col">Data da Entrada</th>
                    <th scope="col">Data da Saída</th>
                    <th scope="col">Hora da Entrada</th>
                    <th scope="col">Hora da Saída</th>
                    <th scope="col">Cod Estacionamento</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop para exibir os dados de cada reserva
                    while($user_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$user_data['cod_reserva']."</td>";
                        echo "<td>".$user_data['fk_cod_cliente']."</td>";
                        echo "<td>".$user_data['data_entrada']."</td>";
                        echo "<td>".$user_data['data_saida']."</td>";
                        echo "<td>".$user_data['hora_entrada']."</td>";
                        echo "<td>".$user_data['hora_saida']."</td>";
                        echo "<td>".$user_data['fk_cod_estacionamento']."</td>";
                        echo "<td>";
                        // Botão para editar
                        echo "<a class=\"btn btn-sm btn-primary mr-2\" href=\"Editar_reserva.php?cod_reserva=" . $user_data['cod_reserva'] . "\">";
                        echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"24\" viewBox=\"0 0 24 24\">";
                        echo "<path d=\"M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z\"/>";
                        echo "</svg>";
                        echo "</a>";

                        // Botão para deletar com confirmação
                        echo "<a class=\"btn btn-sm btn-danger\" href=\"Deletar_reserva.php?cod_reserva=" . $user_data['cod_reserva'] . "\" onclick=\"return confirm('Tem certeza que deseja deletar esta reserva?');\">";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>";
                        echo "<path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5z'/>";
                        echo "</svg>";
                        echo "</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para redirecionar a página com base no valor de pesquisa
        function searchData() {
            var search = document.getElementById('pesquisar').value;
            window.location = 'Sistema_reserva.php?search=' + search;
        }
    </script>
    
    <!-- Inclui o JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXlRA3xF1dIc7Rp1stIB8kqP1p8ip5l9K/rzRo1x/3i9kzW2v5DkcpkSmj6G" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhG81r603YFm49f6I6MKGJx6wl3j6MO3e4l7xZzV6go5Ic6Dk4t4KK7UVlG" crossorigin="anonymous"></script>
</body>
</html>
