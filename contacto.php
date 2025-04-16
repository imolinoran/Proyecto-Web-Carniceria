
<?php
include 'conexion.php'; // asegúrate de que este archivo conecta correctamente

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = htmlspecialchars($_POST["nombre"]);
  $email = htmlspecialchars($_POST["email"]);
  $mensajeTexto = htmlspecialchars($_POST["mensaje"]);

  $stmt = $conn->prepare("INSERT INTO contacto (nombre, email, mensaje) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nombre, $email, $mensajeTexto);

  if ($stmt->execute()) {
    $mensaje = "¡Gracias $nombre! Hemos recibido tu mensaje.";
  } else {
    $mensaje = "Hubo un error al enviar tu mensaje. Intenta de nuevo.";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto | Carnicería Navarra</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white text-red-700 font-sans">
<header class="bg-red-700 text-white p-6 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <a href="carniceriaNavarra.html"><img src="fotos/logo.jpg" width="50" alt="Logo"></a>
        <a href="carniceriaNavarra.html"><h1 class="text-3xl font-bold">Carnicería Navarra</h1></a>
      </div>
      <nav class="space-x-4">
        <a href="carniceriaNavarra.html" class="hover:underline">Inicio</a>
        <a href="galeria.html" class="hover:underline">Productos</a>
        <a href="contacto.php" class="hover:underline">Contacto</a>
        <a href="login.php" class="hover:underline">Login</a>
        <a href="cesta.php">
          <i class="fas fa-shopping-cart"></i> Cesta
      </a>
      </nav>
    </div>
  </header>
  <main class="max-w-xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Formulario de Contacto</h2>
    <?php if ($mensaje): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div>
        <label class="block">Nombre</label>
        <input type="text" name="nombre" class="w-full border p-2 rounded" required>
      </div>
      <div>
        <label class="block">Correo electrónico</label>
        <input type="email" name="email" class="w-full border p-2 rounded" required>
      </div>
      <div>
        <label class="block">Mensaje</label>
        <textarea name="mensaje" class="w-full border p-2 rounded" rows="4" required></textarea>
      </div>
      <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Enviar</button>
    </form>
  </main>
</body>
</html>
