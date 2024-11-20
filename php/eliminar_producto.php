<?php
session_start();
require 'conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];

    // Eliminar el producto
    $query = $mysqli->prepare("DELETE FROM productos WHERE ID = ?");
    $query->bind_param("i", $producto_id);

    if ($query->execute()) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto.";
    }
} else {
    echo "ID del producto no encontrado.";
}
?>
