<?php
session_start();

// Guardar mensaje antes de borrar la sesión
$_SESSION['message'] = 'Sesión cerrada con éxito.';

// Borrar la sesión
session_destroy();

// Redirigir al inicio de sesión con el mensaje
header("Location: login.php");
exit();
?>
