<?php
include 'conexion.php';

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="border p-4 mb-4 rounded">';
        echo '<h3 class="text-xl font-bold">' . htmlspecialchars($row['nombre']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
        echo '<p class="text-red-600 font-semibold">Precio: $' . number_format($row['precio'], 2) . '</p>';
        echo '<a href="agregar_a_cesta.php?id=' . $row['id'] . '" class="text-blue-500 hover:underline">Añadir a la cesta</a>';
        echo '</div>';
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}
?>
