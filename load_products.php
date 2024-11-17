<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "velas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de productos
$query = "SELECT * FROM productos";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    // Mostrar productos
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-card'>
                <img src='{$row['ruta_imagen']}' alt='{$row['nombre']}'>
                <h3>{$row['nombre']}</h3>
                <p>Precio: $ {$row['precio']}</p>
                <button class='add-to-cart' data-id='{$row['id']}' data-nombre='{$row['nombre']}' data-precio='{$row['precio']}'>Agregar al carrito</button>
            </div>";
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}

// Cerrar la conexión
$conexion->close();
?>
