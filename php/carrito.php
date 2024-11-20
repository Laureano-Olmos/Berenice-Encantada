<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

// Asegurarse de que el carrito está inicializado
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Verificar si se ha agregado un producto al carrito
if (isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    
    // Comprobar si ya existe el producto en el carrito
    if (isset($_SESSION['carrito'][$producto_id])) {
        // Si existe, aumentar la cantidad
        $_SESSION['carrito'][$producto_id]['cantidad'] += 1;
    } else {
        // Si no existe, agregarlo con cantidad inicial de 1
        $query = "SELECT * FROM productos WHERE ID = $producto_id";
        $result = $mysqli->query($query);
        $producto = $result->fetch_assoc();

        // Verificar si se encontró el producto en la base de datos
        if ($producto) {
            $_SESSION['carrito'][$producto_id] = [
                'nombre' => $producto['Nombre'],
                'precio' => $producto['Precio'],
                'cantidad' => 1
            ];
        }
    }
}

// Verificar si se eliminó un producto del carrito
if (isset($_GET['eliminar'])) {
    $producto_id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$producto_id]);
}

// Verificar si se actualizó la cantidad
if (isset($_POST['actualizar_cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $nueva_cantidad = $_POST['cantidad'];
    if ($nueva_cantidad > 0) {
        $_SESSION['carrito'][$producto_id]['cantidad'] = $nueva_cantidad;
    } else {
        unset($_SESSION['carrito'][$producto_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../css/styles.css" defer> <!-- Asegúrate de tener estilos -->
</head>
<body>
    <header>
        <h1>Carrito de Compras</h1>
    </header>

    <main>
        <section id="carrito">
            <h2>Carrito de Compras</h2>
            <?php if (!empty($_SESSION['carrito'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_carrito = 0;
                        foreach ($_SESSION['carrito'] as $producto_id => $producto): 
                            // Verificar que $producto sea un array
                            if (is_array($producto)) {
                                $total_producto = $producto['precio'] * $producto['cantidad'];
                                $total_carrito += $total_producto;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td>
                                    <form action="carrito.php" method="POST">
                                        <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                        <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" min="1">
                                        <button type="submit" name="actualizar_cantidad">Actualizar</button>
                                    </form>
                                </td>
                                <td>$<?php echo number_format($total_producto, 2); ?></td>
                                <td>
                                    <a href="carrito.php?eliminar=<?php echo $producto_id; ?>">Eliminar</a>
                                </td>
                            </tr>
                        <?php 
                            } // Cierre de if (is_array($producto))
                        endforeach; 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total del carrito</td>
                            <td>$<?php echo number_format($total_carrito, 2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <a href="../mercado.php">Seguir comprando</a>
                <a href="finalizar_compra.php">Finalizar compra</a> <!-- Puedes crear un archivo para el checkout -->
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
                <a href="../mercado.php">Volver al mercado</a>
            <?php endif; ?>
        </section>

    </main>
</body>
</html>
