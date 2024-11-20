<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];

    // Inserta nuevo usuario en la tabla
    $query = $mysqli->prepare("INSERT INTO usuarios (User, Contraseña) VALUES (?, ?)");
    $query->bind_param("ss", $nombre_usuario, $password);
    $query->execute();

    header("Location: ../php/login.php"); // Redirige a la página de login
}
?>
