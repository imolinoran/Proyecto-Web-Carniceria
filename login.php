<?php
session_start();
include 'conexion.php';
$mensaje = "";
$modo = $_POST["modo"] ?? "login";

// LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && $modo === "login") {
  $usuario = $_POST["usuario"] ?? '';
  $clave = $_POST["clave"] ?? '';

  $sql = "SELECT * FROM usuarios WHERE usuario = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows == 1) {
    $fila = $resultado->fetch_assoc();
    if (password_verify($clave, $fila["clave"])) {
      $_SESSION["usuario"] = $usuario;
      $mensaje = "Bienvenido, <strong>$usuario</strong>.";
    } else {
      $mensaje = "Contraseña incorrecta.";
    }
  } else {
    $mensaje = "Usuario no encontrado.";
  }

  $stmt->close();
}

// REGISTRO
if ($_SERVER["REQUEST_METHOD"] == "POST" && $modo === "registro") {
  $usuario = htmlspecialchars($_POST["usuario"]);
  $email = htmlspecialchars($_POST["email"]);
  $clave = password_hash($_POST["clave"], PASSWORD_DEFAULT);

  $sql = "INSERT INTO usuarios (usuario, email, clave) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param("sss", $usuario, $email, $clave);
    if ($stmt->execute()) {
      $mensaje = "Usuario <strong>$usuario</strong> registrado con éxito.";
    } else {
      $mensaje = "Error al registrar: " . $stmt->error;
    }
    $stmt->close();
  } else {
    $mensaje = "Error en la preparación de la consulta.";
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login / Registro | Carnicería Navarra</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
  <a href="carniceriaNavarra.html" class="absolute top-4 left-4 text-red-700 hover:text-red-900 font-semibold text-sm hover:underline">
    ← Volver
  </a>
  <script>
    function toggleForm(modo) {
      document.getElementById('loginForm').classList.add('hidden');
      document.getElementById('registroForm').classList.add('hidden');
      document.getElementById(modo + 'Form').classList.remove('hidden');
    }
  </script>
  <div class="p-8 relative">
</head>
<body class="bg-gradient-to-br from-red-100 via-white to-red-200 min-h-screen flex items-center justify-center font-sans">
  <div class="bg-white shadow-2xl rounded-3xl max-w-2xl w-full overflow-hidden grid grid-cols-1 lg:grid-cols-2 transition-all duration-500">
    <!-- Panel izquierdo -->
    <div class="bg-red-700 text-white p-10 flex flex-col justify-center items-center space-y-6">
      <img src="fotos/logo.jpg" class="w-20 rounded-full shadow-lg" alt="Logo">
      <h2 class="text-2xl font-bold text-center">Carnicería Navarra</h2>
      <p class="text-center text-sm">¡Bienvenido! Inicia sesión o regístrate para empezar a comprar nuestros mejores productos.</p>
      <div class="space-x-2">
        <button onclick="toggleForm('login')" class="bg-white text-red-700 font-semibold px-4 py-2 rounded hover:bg-red-100 transition">Login</button>
        <button onclick="toggleForm('registro')" class="border border-white px-4 py-2 rounded hover:bg-white hover:text-red-700 transition">Registro</button>
      </div>
    </div>

    <!-- Panel derecho -->
    <div class="p-8">
      <?php if ($mensaje): ?>
        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 p-4 rounded">
          <?= $mensaje ?>
        </div>
      <?php endif; ?>

      <!-- Formulario Login -->
      <form id="loginForm" method="POST" class="<?= $modo === 'registro' ? 'hidden' : '' ?> space-y-5">
        <input type="hidden" name="modo" value="login">
        <h3 class="text-xl font-semibold text-red-700">Iniciar sesión</h3>
        <div>
          <label class="block text-sm">Usuario</label>
          <input type="text" name="usuario" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-red-400" required>
        </div>
        <div>
          <label class="block text-sm">Contraseña</label>
          <input type="password" name="clave" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-red-400" required>
        </div>
        <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 transition">Ingresar</button>
      </form>

      <!-- Formulario Registro -->
      <form id="registroForm" method="POST" class="<?= $modo === 'registro' ? '' : 'hidden' ?> space-y-5">
        <input type="hidden" name="modo" value="registro">
        <h3 class="text-xl font-semibold text-red-700">Crear cuenta</h3>
        <div>
          <label class="block text-sm">Nombre de usuario</label>
          <input type="text" name="usuario" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-red-400" required>
        </div>
        <div>
          <label class="block text-sm">Correo electrónico</label>
          <input type="email" name="email" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-red-400" required>
        </div>
        <div>
          <label class="block text-sm">Contraseña</label>
          <input type="password" name="clave" class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-red-400" required>
        </div>
        <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 transition">Registrarse</button>
      </form>
    </div>
  </div>
</body>
</html>
