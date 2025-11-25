<?php
echo "Início do script<br>";

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "bancouninove";

$conn = new mysqli($servername, $username, $password, $database);

echo "Depois do mysqli<br>";

if ($conn->connect_error) {
    die("Erro ao conectar: " . $conn->connect_error);
}

echo "Conectado!";
