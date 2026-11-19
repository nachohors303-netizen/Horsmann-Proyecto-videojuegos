<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   PUERTA DEL JEFE: solo pasan los Game Masters (rol admin)
   ========================================================== */
// solo_admin.php
// Para las páginas del administrador: primero verifica que haya sesión
// y después que el rol sea "admin".

require 'incluir/proteger.php';

if ($_SESSION['rol'] !== 'admin') {
    $_SESSION['mensaje'] = "Zona bloqueada: solo los Game Masters (administradores) pueden entrar acá.";
    $_SESSION['tipo'] = "error";
    header("Location: panel.php");
    exit;
}
