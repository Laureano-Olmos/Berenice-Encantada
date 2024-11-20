<?php
// success.php
session_start();
require 'conexion.php';
require 'vendor/autoload.php';

// Verificar si el ID de la compra se ha guardado en la sesión o se pasa por URL
$compra_id = $_SESSION['compra_id']; // Asegúrate de tenerlo guardado en la sesión después de la compra

// Obtener el detalle de la compra de la base de datos
$query = "SELECT * FROM compras WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $compra_id);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();

// Mostrar los detalles de la compra y el código de pago
echo "<h2>Gracias por tu compra, {$compra['nombre']}</h2>";
echo "<p>Tu pedido ha sido registrado con éxito.</p>";
echo "<p>Código de pago: {$compra['codigo_pago']}</p>"; // Asegúrate de guardar el código de pago en la base de datos

// Agrega cualquier otro detalle que desees mostrar al cliente

?>
