<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener el rol desde la sesión
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : NULL;

require 'php/conexion.php'; // Conexión a la base de datos

// Inicializar variables para filtros
$busqueda = isset($_GET['busqueda']) ? $mysqli->real_escape_string($_GET['busqueda']) : '';

// Consulta de productos
$query = "SELECT * FROM productos";
if (!empty($busqueda)) {
    $query .= " WHERE Nombre LIKE '%$busqueda%' OR Categoria LIKE '%$busqueda%'";
}
$result = $mysqli->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Mercado Berenice</h1>
        <nav>
            <ul>
                <li><a href="./index.php">Inicio</a></li>
                <li><a href="./mercado.php">Productos</a></li>
                <li><a href="#acerca">Acerca de</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </nav>
        <div class="user-options">
            <?php if (isset($_SESSION['usuario'])): ?>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                <a href="php/logout.php" class="btn">Cerrar sesión</a>
                <a href="php/carrito.php">🛒 Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : '0'; ?>)</a>
            
            <?php else: ?>
                <a href="#" class="btn" id="loginBtn">Login</a>
                <a href="#" class="btn" id="registerBtn">Registro</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <section id="productos">
            <h2>Productos disponibles</h2>

            <!-- Si es administrador, mostrar formulario de búsqueda -->
            <?php if ($rol === 'admin'): ?>
                <form method="GET" action="mercado.php" class="busqueda-form">
                    <label for="busqueda">Buscar producto:</label>
                    <input type="text" id="busqueda" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Nombre o categoría">
                    <button type="submit">Buscar</button>
                </form>
            <?php endif; ?>

            <?php if ($result->num_rows > 0): ?>
                <div class="producto-lista">
                    <?php while($producto = $result->fetch_assoc()): ?>
                        <div class="producto">
                            <h3><?php echo htmlspecialchars($producto['Nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['Descripcion']); ?></p>
                            <p>Precio: $<?php echo number_format($producto['Precio'], 2); ?></p>
                            <p>Stock: <?php echo htmlspecialchars($producto['Stock']); ?></p>

                            <!-- Si el usuario es cliente regular -->
                            <?php if ($rol == NULL): ?>
                                <form action="php/carrito.php" method="POST">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto['ID']; ?>">
                                    <button type="submit">Agregar al carrito</button>
                                </form>
                            <!-- Si el usuario es administrador, mostrar opciones de gestión -->
                            <?php elseif ($rol == 'admin'): ?>
                                <div class="admin-opciones">
                                    <form action="php/actualizar_stock.php" method="POST">
                                        <input type="hidden" name="producto_id" value="<?php echo $producto['ID']; ?>">
                                        <label for="nuevo_stock">Actualizar stock:</label>
                                        <input type="number" name="nuevo_stock" min="0" value="<?php echo $producto['Stock']; ?>">
                                        <button type="submit">Actualizar</button>
                                    </form>
                                    <form action="php/eliminar_producto.php" method="POST">
                                        <input type="hidden" name="producto_id" value="<?php echo $producto['ID']; ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No se encontraron productos para la búsqueda realizada.</p>
            <?php endif; ?>

            <?php if ($rol === 'admin'): ?>
                <h2>Agregar nuevo producto</h2>
                <form action="php/agregar_producto.php" method="POST">
                    <label for="nombre">Nombre del producto:</label>
                    <input type="text" name="nombre" id="nombre" required>

                    <label for="categoria">Categoría:</label>
                    <input type="text" name="categoria" id="categoria" required>

                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" required></textarea>

                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" step="0.01" required>

                    <label for="stock">Stock:</label>
                    <input type="number" name="stock" id="stock" required>

                    <label for="imagen">URL de la imagen:</label>
                    <input type="text" name="imagen" id="imagen" required>

                    <button type="submit">Agregar producto</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
