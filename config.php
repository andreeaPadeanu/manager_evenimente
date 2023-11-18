<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "evenimente1";

// Configurare a locației socket-ului MAMP
$socket = '/Applications/MAMP/tmp/mysql/mysql.sock';

$conn = new mysqli($host, $username, $password, $database, null, $socket);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}
?>