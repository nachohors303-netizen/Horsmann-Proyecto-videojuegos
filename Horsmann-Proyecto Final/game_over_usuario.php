<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   GAME OVER: archiva un jugador (no lo borra)
   ========================================================== */
// game_over_usuario.php
// "Game Over": no borra al usuario, solo le pone la fecha de archivado.
// Esta página no muestra nada, solo hace la acción y vuelve al listado.
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/solo_admin.php';

// solo funciona si viene de un formulario (POST), no abriendo un link
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_usuarios.php");
    exit;
}

if (!token_ok()) {
    $_SESSION['mensaje'] = "La solicitud no es válida o venció.";
    $_SESSION['tipo'] = "error";
    header("Location: admin_usuarios.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['mensaje'] = "El jugador indicado no es válido.";
    $_SESSION['tipo'] = "error";
} else if ($id == $_SESSION['id']) {
    $_SESSION['mensaje'] = "No podés archivar tu propia cuenta.";
    $_SESSION['tipo'] = "error";
} else {
    $c = $pdo->prepare("UPDATE usuarios SET fecha_archivado = NOW() WHERE id = ? AND fecha_archivado IS NULL");
    $c->execute([$id]);

    // rowCount dice cuántas filas cambió el UPDATE
    if ($c->rowCount() == 1) {
        $_SESSION['mensaje'] = "Jugador archivado. Lo podés reactivar desde la sección Game Over.";
        $_SESSION['tipo'] = "ok";
    } else {
        $_SESSION['mensaje'] = "No se encontró un jugador activo con ese ID.";
        $_SESSION['tipo'] = "error";
    }
}

header("Location: admin_usuarios.php");
exit;
