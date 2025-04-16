<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $totalProducto = $precio * $cantidad;
    $foto = $_POST['foto'];

    if (isset($_SESSION['cesta'][$producto])) {
        $_SESSION['cesta'][$producto]['cantidad'] += $cantidad;
        $_SESSION['cesta'][$producto]['total'] = $_SESSION['cesta'][$producto]['cantidad'] * $precio;
    } else {
        $_SESSION['cesta'][$producto] = [
            'precio' => $precio,
            'cantidad' => $cantidad,
            'total' => $totalProducto,
            'foto' => $foto
        ];
    }
}

if (isset($_GET['vaciar'])) {
    session_unset();
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito Lateral</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .cart-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 350px;
        height: 100%;
        background-color: #fff;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease-in-out;
        z-index: 1000;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .close-cart {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }
    .cart-items {
        flex-grow: 1;
        overflow-y: auto;
        margin-top: 20px;
    }
    .cart-item {
        display: flex;
        margin-bottom: 15px;
    }
    .cart-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 10px;
    }
    .cart-item-info p {
        margin: 2px 0;
    }
    .cart-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .cart-footer button {
        padding: 10px;
        background-color: #ff5722;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .cart-footer button:hover {
        background-color: #e64a19;
    }
    .open-cart {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #ff5722;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<!-- Botón flotante para abrir el carrito -->
<button id="openCart" class="open-cart">🛒</button>

<!-- Panel lateral para la cesta -->
<div id="cart" class="cart-panel">
    <div class="cart-header">
        <h2>Tu Cesta</h2>
        <button id="closeCart" class="close-cart">X</button>
    </div>
    <div id="cartItems" class="cart-items">
        <!-- Los productos añadidos se mostrarán aquí -->
    </div>
    <div class="cart-footer">
        <button onclick="clearCart()">Vaciar Cesta</button>
        <button onclick="window.location.href='checkout.php'">Finalizar Compra</button>
    </div>
</div>

<script>
let cart = [];

document.getElementById('openCart').addEventListener('click', () => {
    document.getElementById('cart').style.right = '0';
});

document.getElementById('closeCart').addEventListener('click', () => {
    document.getElementById('cart').style.right = '-400px';
});

function addToCart(nombre, precio, cantidad, foto) {
    const existente = cart.find(p => p.nombre === nombre);
    if (existente) {
        existente.cantidad += cantidad;
    } else {
        cart.push({ nombre, precio, cantidad, foto });
    }
    actualizarCarrito();
}

function actualizarCarrito() {
    const contenedor = document.getElementById('cartItems');
    contenedor.innerHTML = '';
    let total = 0;

    cart.forEach(producto => {
        const item = document.createElement('div');
        item.classList.add('cart-item');
        item.innerHTML = `
            <img src="${producto.foto}" alt="${producto.nombre}" class="cart-item-image">
            <div class="cart-item-info">
                <p><strong>${producto.nombre}</strong></p>
                <p>${producto.cantidad} x ${producto.precio.toFixed(2)} €</p>
                <p><strong>${(producto.cantidad * producto.precio).toFixed(2)} €</strong></p>
            </div>
        `;
        contenedor.appendChild(item);
        total += producto.cantidad * producto.precio;
    });

    document.querySelector('.cart-footer button:nth-child(2)').innerText = `Finalizar Compra - ${total.toFixed(2)} €`;
}

function clearCart() {
    cart = [];
    actualizarCarrito();
}
</script>
</body>
</html>
