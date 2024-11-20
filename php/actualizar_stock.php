<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo tiene la conexión correcta a tu base de datos

// Verifica que el usuario sea administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Verificar si se ha enviado el formulario
if (isset($_POST['producto_id']) && isset($_POST['nuevo_stock'])) {
    $producto_id = $_POST['producto_id'];
    $nuevo_stock = $_POST['nuevo_stock'];

    // Actualizar el stock del producto en la base de datos
    $query = $mysqli->prepare("UPDATE productos SET Stock = ? WHERE ID = ?");
    $query->bind_param("ii", $nuevo_stock, $producto_id);
    
    if ($query->execute()) {
        echo "Stock actualizado correctamente.";
    } else {
        echo "Error al actualizar el stock.";
    }
} else {
    echo "Por favor, selecciona un producto y el nuevo stock.";
}
?>
