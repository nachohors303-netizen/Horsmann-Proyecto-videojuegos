<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   CARTEL DE ERRORES: lista de problemas de un formulario
   ========================================================== */
// errores.php - muestra la lista $errores si tiene algo
if (!empty($errores)) {
    echo '<div class="aviso error"><ul>';
    foreach ($errores as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul></div>';
}
