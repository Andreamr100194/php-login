<?php
require 'database.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Manejar la solicitud para eliminar la cuenta
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['user_id']);

    if ($stmt->execute()) {
        session_destroy(); // Cerrar la sesión
        header("Location: signup.php"); // Redirigir al registro
        exit();
    } else {
        $message = 'Error al intentar borrar la cuenta.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="delete_account" value="1">
      <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">Borrar mi cuenta</button>
    </form>

    <form method="post" action="logout_action.php">
      <button type="submit">Cerrar sesión</button>
    </form>
  </body>
</html>
