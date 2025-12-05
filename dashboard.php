<?php
require_once 'inclusiones/auth.php';

$usuario = obtenerUsuarioActual();
$mensaje = '';
$error = '';

// Si no está logueado, redirigir al login
if (!isset($_SESSION['usuario_autenticado'])) {
    header('Location: login.php');
    exit;
}

// Redirigir según rol
redirigirAModulo($usuario['rol']);
