<?php
session_start();
include_once('Conectar.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT v.cod_vaga, v.fk_cod_estacionamento, v.tipo_veiculo, r.cod_reserva 
            FROM tb_vagas v 
            LEFT JOIN tb_reservas r ON v.fk_cod_reserva = r.cod_reserva 
            WHERE v.cod_vaga LIKE '%$data%' OR v.tipo_veiculo LIKE '%$data%' 
            ORDER BY v.cod_vaga DESC";
} else {
    $sql = "SELECT v.cod_vaga, v.fk_cod_estacionamento, v.tipo_veiculo, r.cod_reserva 
            FROM tb_vagas v 
            LEFT JOIN tb_reservas r ON v.fk_cod_reserva = r.cod_reserva 
            ORDER BY v.cod_vaga ASC";
}
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>CITY ESTACIONA</title>
    <link rel="stylesheet" href="Sistema.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="adm.php">Voltar</a>
        </div>
        <div class="d-flex">
            <a href="Sair.php" class="btn btn-danger me-5">Sair</a>
        </div>
    </nav>
    <br><br>

    <!-- Exibe mensagem de sucesso ou erro apenas se houver redirecionamento após uma ação -->
    <?php if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $_SESSION['mensagem']; ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>

    <div class="m-5">
        <table class=" table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">Cod Estacionamento</th>
                    <th scope="col">Tipo do Veículo</th>
                    <th scope="col">Cod Reserva</th>
                    <th scope="col">Botões</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($user_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$user_data['cod_vaga']."</td>";
                        echo "<td>".$user_data['fk_cod_estacionamento']."</td>";
                        echo "<td>".$user_data['tipo_veiculo']."</td>";
                        echo "<td>".$user_data['cod_reserva']."</td>";
                        echo "<td>";
                        echo "<a class='btn btn-sm btn-primary mr-2' href='Editar_vaga.php?cod_vaga=" . $user_data['cod_vaga'] . "'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' viewBox='0 0 24 24'>";
                        echo "<path d='M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z'/>";
                        echo "</svg>";
                        echo "</a>";

                        echo "<a class='btn btn-sm btn-danger' href='deletar_vagas.php?cod_vaga=" . $user_data['cod_vaga'] . "' onclick='return confirm(\"Tem certeza que deseja deletar esta vaga?\");'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='24' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>";
                        echo "<path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>";
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
        var search = document.getElementById('pesquisar');

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                searchData();
            }
        });

        function searchData() {
            window.location = 'Sistema_vaga.php?search=' + search.value;
        }
    </script>
</body>
</html>