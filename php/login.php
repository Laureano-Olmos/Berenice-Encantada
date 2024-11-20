<?php
session_start();
require 'conexion.php'; // Importa la conexión a la base de datos

// Verifica que los campos se hayan enviado correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre_usuario']) && isset($_POST['password'])) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];

        // Consulta para verificar el usuario y obtener su rol
        $query = $mysqli->prepare("SELECT * FROM usuarios WHERE User = ? AND Contraseña = ?");
        $query->bind_param("ss", $nombre_usuario, $password);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Guardar el nombre de usuario y rol en la sesión
            $_SESSION['usuario'] = $user['User']; // Guardar nombre de usuario
            $_SESSION['user_id'] = $user['ID'];   // Guardar ID del usuario
            $_SESSION['rol'] = $user['rol'];      // Guardar rol del usuario
            
            // Redirigir a la página principal
            header("Location: ../index.php");
            exit(); // Asegurar que no se ejecuta más código después de la redirección
        } else {
            echo "Credenciales incorrectas. Intenta de nuevo.";
        }
    } else {
        echo "Por favor, rellena todos los campos.";
    }
}
?>
