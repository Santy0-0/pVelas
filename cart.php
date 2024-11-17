<!-- cart.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Enlace al archivo CSS externo -->
</head>
<body>
    <h1>Carrito de Compras</h1>

    <table id="cart-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="cart-items">
            <!-- Los productos del carrito se cargarán aquí -->
        </tbody>
    </table>

    <button class="checkout-button" onclick="checkout()">Realizar Compra</button>

    <script>
        // Cargar carrito desde localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Función para mostrar el carrito
        function loadCart() {
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = '';  // Limpiar la tabla

            let totalPrice = 0;

            // Iterar sobre los productos del carrito
            cart.forEach((item, index) => {
                const totalItem = (item.cantidad * item.precio).toFixed(2);
                totalPrice += parseFloat(totalItem);

                cartItems.innerHTML += `
                    <tr>
                        <td>${item.nombre}</td>
                        <td><input type="number" min="1" value="${item.cantidad}" onchange="updateQuantity(${index}, this.value)"></td>
                        <td>$${item.precio}</td>
                        <td>$${totalItem}</td>
                        <td><button onclick="removeFromCart(${index})">Eliminar</button></td>
                    </tr>
                `;
            });

            // Añadir total
            cartItems.innerHTML += `
                <tr>
                    <td colspan="3" class="total">Total:</td>
                    <td>$${totalPrice.toFixed(2)}</td>
                    <td></td>
                </tr>
            `;
        }

        // Función para actualizar cantidad
        function updateQuantity(index, newQuantity) {
            cart[index].cantidad = parseInt(newQuantity);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCart(); // Recargar el carrito
        }

        // Función para eliminar un producto del carrito
        function removeFromCart(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCart(); // Recargar el carrito
        }

        // Función para realizar la compra
        function checkout() {
    if (cart.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    fetch('checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart: cart }) // Enviando los productos del carrito
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Compra realizada con éxito.");

            // Limpiar carrito en localStorage
            localStorage.removeItem('cart');

            // Redirigir a confirmation.php
            window.location.href = 'confirmation.php';
        } else {
            alert("Error al realizar la compra.");
        }
    });
}


        // Cargar el carrito al iniciar
        window.onload = loadCart;
    </script>
</body>
</html>
