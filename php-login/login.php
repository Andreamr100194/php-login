<?php
require 'database.php';
session_start();

$message = '';


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $sql = "SELECT id, email, password FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            // Guardar información del usuario en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: logout.php");
            exit();
        } else {
            $message = 'Correo o contraseña incorrectos.';
        }
    } else {
        $message = 'Por favor, completa todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <!-- Mostrar el mensaje de error-->
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h1>Iniciar sesión</h1>
    <span>o <a href="signup.php">Registrarse</a></span>

    <form action="login.php" method="post">
      <label for="email">Correo electrónico:</label>
      <input type="email" id="email" name="email" placeholder="Introduce tu correo" required>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>

      <input type="submit" value="Iniciar sesión">
    </form>
  </body>
</html>
