<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   ESCUDO: token anti solicitudes falsas (CSRF)
   ========================================================== */
// csrf.php
// Token secreto para los formularios. Sirve para que otra página no pueda
// mandarle datos a la nuestra haciéndose pasar por un usuario logueado.
// (hay que haber hecho session_start() antes de incluir este archivo)

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// el input escondido que va dentro de cada <form method="post">
function campo_token() {
    return '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">';
}

// true si el token que llegó es igual al de la sesión
function token_ok() {
    return isset($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token']);
}
