<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bancouninove";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_errno) {
    die("Falha na conexão: " . $conn->connect_error);
}

return $conn; // ESSENCIAL
