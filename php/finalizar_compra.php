<link rel="stylesheet" href="../css/styles.css" defer> <!-- Asegúrate de tener estilos -->

<section id="finalizar-compra">
    <h2>Finalizar Compra</h2>
    <form action="/ProyectoBobi/php/procesar_compra.php" method="POST">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="direccion">Dirección de Envío:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="pago">Método de Pago:</label>
        <select id="pago" name="pago" required>
            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
            <option value="transferencia">Transferencia Bancaria</option>
        </select>

        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios"></textarea>

        <button type="submit">Confirmar Compra</button>
    </form>
</section>