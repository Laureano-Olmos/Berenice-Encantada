<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['descripcion']) && isset($_POST['categoria']) && isset($_POST['stock'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];

    // Agregar el nuevo producto
    $query = $mysqli->prepare("INSERT INTO productos (Nombre, Precio, Descripcion, Categoria, Stock) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sdssi", $nombre, $precio, $descripcion, $categoria, $stock);

    if ($query->execute()) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar el producto.";
    }
} else {
    echo "Por favor, completa todos los campos.";
}
?>
