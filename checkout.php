<?php
// checkout.php

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "velas");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos del carrito desde el cuerpo de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$cart = $data['cart'];
$usuario_id = 1; // Suponiendo que el cliente está logueado con id 1
$total = 0;

// Calcular el total de la venta sumando los precios de los productos en el carrito
foreach ($cart as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Registrar la venta en la tabla `ventas`
$fecha = date('Y-m-d H:i:s');
$metodo_pago = 'Tarjeta'; // Puedes obtener este valor del frontend si deseas que el usuario lo seleccione
$insertVenta = $conexion->prepare("INSERT INTO ventas (usuario_id, fecha, total, metodo_pago) VALUES (?, ?, ?, ?)");
$insertVenta->bind_param("isds", $usuario_id, $fecha, $total, $metodo_pago);
$insertVenta->execute();

// Obtener el ID de la venta insertada
$venta_id = $conexion->insert_id;

// Registrar los productos en la tabla `descripcion_venta`, incluyendo `precio_unitario`
foreach ($cart as $item) {
    $producto_id = $item['id'];
    $cantidad = $item['cantidad'];
    $precio_unitario = $item['precio']; // Precio unitario que proviene del carrito
    
    // Insertar cada producto del carrito en la tabla `descripcion_venta`
    $insertDescripcion = $conexion->prepare("INSERT INTO descripcion_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $insertDescripcion->bind_param("iiid", $venta_id, $producto_id, $cantidad, $precio_unitario);
    $insertDescripcion->execute();
}

// Cerrar las conexiones
$insertVenta->close();
$insertDescripcion->close();
$conexion->close();

// Responder con éxito
echo json_encode(['success' => true]);
?>
