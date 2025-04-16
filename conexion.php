<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$database = "carniceria"; // Asegúrate de que tu base de datos se llame así

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// No pongas echo aquí
?>
