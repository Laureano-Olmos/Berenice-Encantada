<?php
session_start(); // Inicia la sesión al cargar la página
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berenice Encantada</title>
    <link rel="stylesheet" href="estilos.css">

</head>
  <body>
    <header>
        <h1>Berenice Encantada</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="mercado.php">Productos</a></li>
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

    <!-- Modal para Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeLogin">&times;</span>
            <h2>Login</h2>
            <form id="loginForm" method="POST" action="php/login.php">
                <label for="nombre_usuario">Usuario:</label>
                <input type="text" name="nombre_usuario" required>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <!-- Modal para Registro -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeRegister">&times;</span>
            <h2>Registro</h2>
            <form id="registerForm" method="POST" action="php/registro.php">
                <label for="nombre_usuario">Usuario:</label>
                <input type="text" name="nombre_usuario" required>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>

    <main>
        <section id="inicio">
            <h2>Bienvenido a Berenice Encantada</h2>
            <p>Dandole magia a tu vida desde 2020</p>
        </section>

        <section id="acerca">
            <h2>Acerca de Nosotros</h2>
            <p>Información sobre la tienda y su misión.</p>
        </section>

        <section id="contacto">
            <h2>Contacto</h2>
            <p>¿Dudas? ¿sugerencias?, ¡ponte en contacto con nosotros!</p>
            <form action="#" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2020 Berenice Encantada. Todos los derechos reservados.</p>
    </footer>
    <script src="scripts.js"></script> <!-- El archivo JS para controlar los modales -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>