<?php
session_start();

// Función para verificar autenticación
function verificarAutenticacion()
{
    if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
        header('Location: ../login.php');
        exit;
    }
}

// Función para obtener usuario actual
function obtenerUsuarioActual()
{
    return $_SESSION['usuario'] ?? null;
}

// Función para verificar permisos
function tienePermiso($rolRequerido)
{
    $usuario = obtenerUsuarioActual();

    if (!$usuario) {
        return false;
    }

    if ($usuario['rol'] === 'admin') {
        return true;
    }

    return $usuario['rol'] === $rolRequerido;
}

// Función para redirigir al módulo correspondiente
function redirigirAModulo($rol)
{
    switch ($rol) {
        case 'bomberos':
            header('Location: modulos/bomberos/index.php');
            break;
        case 'admin':
            header('Location: modulos/admin/index.php');
            break;
        case 'medico':
            header('Location: modulos/medico/index.php');
            break;
        case 'policia':
            header('Location: modulos/policia/index.php');
            break;
        default:
            header('Location: index.php');
    }
    exit;
}

// Función para cerrar sesión
function cerrarSesion()
{
    session_destroy();
    header('Location: login.php');
    exit;
}
