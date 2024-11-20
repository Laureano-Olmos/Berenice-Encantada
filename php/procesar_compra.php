<?php
session_start();
require 'conexion.php';

// Verificar si el carrito tiene productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit;
}

require '../vendor/autoload.php'; // Asegúrate de haber instalado el SDK

MercadoPago\SDK::setAccessToken('YOUR_ACCESS_TOKEN'); // Reemplaza con tu Access Token

// Obtener los datos del formulario de compra
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$email = $_POST['email'];
$metodo_pago = $_POST['pago'];
$comentarios = $_POST['comentarios'];

// Insertar la compra en la tabla 'compras'
// Añadir columna 'codigo_pago' en la tabla 'compras' (debe existir en la base de datos)
$query = "INSERT INTO compras (nombre, direccion, email, metodo_pago, comentarios, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sssss", $nombre, $direccion, $email, $metodo_pago, $comentarios);

if ($stmt->execute()) {
    $compra_id = $stmt->insert_id; // Obtener el ID de la compra recién creada

    // Insertar cada producto del carrito en la tabla 'detalles_compra'
    foreach ($_SESSION['carrito'] as $producto_id => $producto) {
        if (isset($producto['nombre']) && isset($producto['precio']) && isset($producto['cantidad'])) {
            $nombre_producto = $producto['nombre'];
            $precio = $producto['precio'];
            $cantidad = $producto['cantidad'];

            $query_detalles = "INSERT INTO detalles_compra (compra_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt_detalles = $mysqli->prepare($query_detalles);
            $stmt_detalles->bind_param("iiid", $compra_id, $producto_id, $cantidad, $precio);
            $stmt_detalles->execute();
        }
    }

    // Generar un código de pago para efectivo o transferencia si corresponde
    $codigo_pago = null;
    if ($metodo_pago == 'Pago Fácil' || $metodo_pago == 'CobroExpress' || $metodo_pago == 'Rapipago') {
        // Generar código de pago (puedes usar un UUID o un código único)
        $codigo_pago = uniqid('Pago_');
        // Actualiza la compra con el código de pago
        $update_query = "UPDATE compras SET codigo_pago = ? WHERE id = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param("si", $codigo_pago, $compra_id);
        $update_stmt->execute();
    }

    // Si el método de pago es transferencia, mostrar detalles
    if ($metodo_pago == 'Transferencia') {
        echo "
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Detalles de Transferencia</title>
            <link rel='stylesheet' href='../css/styles.css'> <!-- Link al archivo de estilos -->
        </head>
        <body>
            <div class='container'>
                <h2>Detalles de Transferencia</h2>
                <p>Por favor realiza la transferencia a los siguientes datos:</p>
                <p><strong>CVU:</strong> 1234567890123456789012</p>
                <p><strong>Alias:</strong> MiCuenta</p>
                <p><strong>Nombre del dueño de la cuenta:</strong> Juan Pérez</p>
                <p><strong>Banco destino:</strong> Mercado Pago</p>
                <p><strong>Aclaración:</strong> Por favor envía el comprobante de la transferencia por WhatsApp (+54 9 341 3169279).</p>
            </div>
        </body>
        </html>";
        exit;
    }

    // Vaciar el carrito después de la compra
    unset($_SESSION['carrito']);

    // Página de confirmación de compra con estilos
    echo "
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Confirmación de Compra</title>
        <link rel='stylesheet' href='../css/styles.css'> <!-- Link al archivo de estilos -->
        <style>
            body {
                background-color: #f7f0f7;
                font-family: 'Alegreya', serif;
                color: #333;
            }
            .container {
                text-align: center;
                padding: 50px;
                max-width: 600px;
                margin: 0 auto;
            }
            h2 {
                color: #8e44ad;
                font-size: 2.5rem;
            }
            p {
                font-size: 1.2rem;
                color: #555;
            }
            .btn {
                display: inline-block;
                background-color: #8e44ad;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
            .btn:hover {
                background-color: #732d91;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Gracias por tu compra, $nombre</h2>
            <p>Tu pedido ha sido registrado con éxito.</p>
            <p>Te hemos enviado un correo a $email con los detalles de tu compra.</p>";
    
    if ($codigo_pago) {
        echo "<p><strong>Código de pago:</strong> $codigo_pago</p>"; // Muestra el código de pago
    }
    
    echo "
            <a href='../mercado.php' class='btn'>Volver al Mercado</a>
            <a href='../index.php' class='btn'>Ir a la Página Principal</a>
        </div>
    </body>
    </html>";
} else {
    echo "<p>Hubo un error al procesar la compra. Por favor, intenta de nuevo.</p>";
    echo "<a href='../mercado.php' class='btn'>Volver al Mercado</a>";
}
?>
