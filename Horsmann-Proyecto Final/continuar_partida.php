<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   CONTINUAR PARTIDA: reactiva un jugador archivado
   ========================================================== */
// continuar_partida.php - "Continuar": le saca la fecha de archivado a la cuenta
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/solo_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: usuarios_game_over.php");
    exit;
}

if (!token_ok()) {
    $_SESSION['mensaje'] = "La solicitud no es válida o venció.";
    $_SESSION['tipo'] = "error";
    header("Location: usuarios_game_over.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['mensaje'] = "El jugador indicado no es válido.";
    $_SESSION['tipo'] = "error";
} else {
    // fecha_archivado en NULL = cuenta activa otra vez
    $c = $pdo->prepare("UPDATE usuarios SET fecha_archivado = NULL WHERE id = ? AND fecha_archivado IS NOT NULL");
    $c->execute([$id]);

    if ($c->rowCount() == 1) {
        $_SESSION['mensaje'] = "Jugador reactivado, ya puede volver a iniciar sesión.";
        $_SESSION['tipo'] = "ok";
    } else {
        $_SESSION['mensaje'] = "No se encontró un jugador archivado con ese ID.";
        $_SESSION['tipo'] = "error";
    }
}

header("Location: usuarios_game_over.php");
exit;
