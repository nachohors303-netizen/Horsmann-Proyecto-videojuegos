<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   PUERTA DE NIVEL: solo pasan los jugadores con sesión iniciada
   ========================================================== */
// proteger.php
// Se incluye al principio de las páginas privadas. Si no hay sesión, te manda al login.
// (necesita session_start() y conexion.php ya cargados)

if (!isset($_SESSION['id'])) {
    $_SESSION['mensaje'] = "Zona bloqueada: primero tenés que iniciar sesión para entrar.";
    $_SESSION['tipo'] = "error";
    header("Location: login.php");
    exit;
}

// Chequeo en la base que el usuario siga existiendo y no esté archivado,
// y traigo su rol actual por si se lo cambiaron.
$consulta = $pdo->prepare("SELECT nombre, rol FROM usuarios WHERE id = ? AND fecha_archivado IS NULL");
$consulta->execute([$_SESSION['id']]);
$quien = $consulta->fetch();

if (!$quien) {
    session_destroy();
    session_start();
    $_SESSION['mensaje'] = "Tu sesión ya no es válida, iniciá sesión de nuevo.";
    $_SESSION['tipo'] = "error";
    header("Location: login.php");
    exit;
}

$_SESSION['nombre'] = $quien['nombre'];
$_SESSION['rol'] = $quien['rol'];
