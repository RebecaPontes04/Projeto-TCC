<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'tcc';

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

return $conexao;