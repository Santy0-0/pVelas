<!-- index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado Virtual</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Enlace al archivo CSS externo 2222222222222222222222222222222222223333333333333333333-->
</head>
<body>
    <div class="header">
        <div>
            <h1>Mercado Virtual</h1>
        </div>
        <div>
            <button onclick="showLogin()">Iniciar Sesión / Registrarse</button>
            <a href="cart.php" class="cart-button">Ver Carrito</a>
        </div>
    </div>

    <div class="container" id="product-list">
        <!-- Incluir el archivo de productos aquí -->
        <?php include 'load_products.php'; ?>
    </div>

    <script>
        // Inicializar el carrito desde localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Función para agregar productos al carrito
        function addToCart(productId, productName, productPrice) {
            const productInCart = cart.find(item => item.id === productId);
            if (productInCart) {
                productInCart.cantidad += 1;
            } else {
                cart.push({ id: productId, nombre: productName, precio: productPrice, cantidad: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            alert("Producto agregado al carrito.");
        }

        // Añadir evento click a los botones de agregar al carrito
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-nombre');
                const productPrice = parseFloat(button.getAttribute('data-precio'));
                addToCart(productId, productName, productPrice);
            });
        });
    </script>
</body>
</html>
