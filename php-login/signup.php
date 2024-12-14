<?php
require 'database.php';
session_start();

$message = '';

// Verificar si hay un mensaje en la sesión (por ejemplo, tras eliminar cuenta)
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Limpiar el mensaje
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar si los campos de correo y contraseña están completos
    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {

        // Verificar que las contraseñas coincidan
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $message = 'Las contraseñas no coinciden.';
        } else {
            // Comprobar si el correo ya está registrado
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->execute();
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                $message = 'El correo electrónico ya está registrado.';
            } else {
                // Registrar el usuario en la base de datos
                $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $_POST['email']);
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña
                $stmt->bindParam(':password', $password);

                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Usuario creado con éxito.';
                    header('Location: login.php'); // Redirigir a la página de login
                    exit;
                } else {
                    $message = 'Error al crear el usuario. Inténtalo nuevamente.';
                }
            }
        }
    } else {
        $message = 'Por favor, completa todos los campos del formulario.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <!-- Mensaje-->
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h1>Registrarse</h1>
    <span>o <a href="login.php">Iniciar Sesión</a></span>

    <form action="signup.php" method="post">
      <label for="email">Correo electrónico:</label>
      <input type="email" id="email" name="email" placeholder="Introduce tu correo" required>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>

      <label for="confirm_password">Confirma tu contraseña:</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirma tu contraseña" required>

      <input type="submit" value="Registrarse">
    </form>
  </body>
</html>
