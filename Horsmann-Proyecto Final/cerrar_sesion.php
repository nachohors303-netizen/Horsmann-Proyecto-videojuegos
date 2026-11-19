<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   GUARDAR Y SALIR: cierre de sesión
   ========================================================== */
// cerrar_sesion.php
session_start();

// borro todo lo que había en la sesión y la destruyo
$_SESSION = [];
session_destroy();

// empiezo una sesión nueva solo para dejar el mensaje de despedida
session_start();
$_SESSION['mensaje'] = "Partida guardada. ¡Nos vemos en la próxima!";
$_SESSION['tipo'] = "ok";
header("Location: login.php");
exit;
