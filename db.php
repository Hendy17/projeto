<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cep";

// Cria a conexão
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($mysqli->connect_error) {
    die("Conexão falhou: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
?>
