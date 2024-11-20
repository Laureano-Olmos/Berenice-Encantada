<?php
$mysqli = new mysqli("localhost", "root", "", "berenice");

if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}
?>
