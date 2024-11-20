<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['producto_id']) && isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['descripcion'])) {
    $producto_id = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    $query = $mysqli->prepare("UPDATE productos SET Nombre = ?, Precio = ?, Descripcion = ? WHERE ID = ?");
    $query->bind_param("sdsi", $nombre, $precio, $descripcion, $producto_id);

    if ($query->execute()) {
        echo "Producto modificado correctamente.";
    } else {
        echo "Error al modificar el producto.";
    }
} else {
    echo "Por favor, completa todos los campos.";
}
?>
