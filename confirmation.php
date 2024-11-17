<!-- confirmation.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Enlace al archivo CSS externo -->
</head>
<body>
    <div class="container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu compra ha sido procesada con éxito.</p>

        <?php
        // Simulamos que el cliente está logueado con id 1
        $usuario_id = 1;

        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "velas");

        // Obtener la última venta del cliente
        $query = "SELECT * FROM ventas WHERE usuario_id = $usuario_id ORDER BY id DESC LIMIT 1";
        $result = $conexion->query($query);
        $venta = $result->fetch_assoc();

        // Mostrar detalles de la venta
        if ($venta) {
            echo "<h2>Detalles de la compra</h2>";
            echo "<p><strong>Fecha:</strong> " . $venta['fecha'] . "</p>";
            echo "<p><strong>Total pagado:</strong> $" . $venta['total'] . "</p>";
            echo "<p><strong>Método de pago:</strong> " . $venta['metodo_pago'] . "</p>";

            // Obtener los productos de la descripción de la venta
            $venta_id = $venta['id'];
            $query_productos = "SELECT p.nombre, dv.cantidad, p.precio 
                                FROM descripcion_venta dv
                                JOIN productos p ON dv.producto_id = p.id
                                WHERE dv.venta_id = $venta_id";
            $result_productos = $conexion->query($query_productos);

            echo "<h3>Productos comprados:</h3>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>";

            // Mostrar los productos
            while ($producto = $result_productos->fetch_assoc()) {
                $total_producto = $producto['cantidad'] * $producto['precio'];
                echo "<tr>
                        <td>" . $producto['nombre'] . "</td>
                        <td>" . $producto['cantidad'] . "</td>
                        <td>$" . $producto['precio'] . "</td>
                        <td>$" . number_format($total_producto, 2) . "</td>
                    </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No se pudo encontrar la información de tu compra.</p>";
        }
        ?>

        <a href="index.php" class="cart-button">Regresar a la tienda</a>
    </div>
</body>
</html>
