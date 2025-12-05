<?php
// logout.php - VERSIÓN MEJORADA PARA INFINITYFREE
session_start();

// Log para depuración
error_log("Logout iniciado desde: " . $_SERVER['REQUEST_URI']);

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se usa cookie de sesión, destruirla
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destruir la sesión
session_destroy();

// Redirigir inmediatamente
header('Location: login.php');
exit;
?>