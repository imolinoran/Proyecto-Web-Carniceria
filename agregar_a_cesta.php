<?php
session_start(); // Si usas sesiones para los usuarios


if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    
    // Verifica si el usuario está logueado (usando sesión, por ejemplo)
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : NULL;

    // Verificar si el producto ya está en la cesta
    $sql_check = "SELECT * FROM cesta WHERE id_producto = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ii", $id_producto, $id_usuario);
    $stmt->execute();
    $result_check = $stmt->get_result();

    if ($result_check->num_rows > 0) {
        // Si ya está en la cesta, aumentar la cantidad
        $sql_update = "UPDATE cesta SET cantidad = cantidad + 1 WHERE id_producto = ? AND id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $id_producto, $id_usuario);
        $stmt_update->execute();
    } else {
        // Si no está en la cesta, agregarlo
        $sql_insert = "INSERT INTO cesta (id_producto, cantidad, id_usuario) VALUES (?, 1, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $id_producto, $id_usuario);
        $stmt_insert->execute();
    }

    echo "Producto añadido a la cesta.";
    header("Location: cesta.php"); // Redirigir al usuario a la página de la cesta
} else {
    echo "No se ha seleccionado un producto válido.";
}
?>
