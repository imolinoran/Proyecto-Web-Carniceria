<?php
session_start();

include 'conexion.php';

if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : NULL;

    // Eliminar producto de la cesta
    $sql = "DELETE FROM cesta WHERE id_producto = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_producto, $id_usuario);
    $stmt->execute();

    echo "Producto eliminado de la cesta.";
    header("Location: cesta.php"); // Redirigir a la página de la cesta
} else {
    echo "No se ha seleccionado un producto válido.";
}
?>
