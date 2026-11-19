<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   REGLAS DEL JUEGO: plataformas, niveles y rangos
   ========================================================== */
// gamer.php
// Cosas de la temática videojuegos: plataformas, nivel del jugador, etc.

// plataforma => icono que se muestra
$plataformas = [
    'PC'          => '🖥️',
    'PlayStation' => '🎮',
    'Xbox'        => '🟩',
    'Nintendo'    => '🕹️',
    'Móvil'       => '📱',
];

// El nivel NO se guarda en la base: se calcula con la fecha de alta.
// Cada 7 días en HORSMANN PROYECTO VIDEOJUEGOS el jugador sube un nivel.
function nivel_jugador($fecha_creacion) {
    $dias = (int)((time() - strtotime($fecha_creacion)) / 86400);
    $nivel = 1 + intdiv($dias, 7);
    $xp = (int)(($dias % 7) / 7 * 100);   // porcentaje de la barra de experiencia
    // el rango cambia según el nivel, como en un juego
    if ($nivel < 2) {
        $rango = "Novato";
    } else if ($nivel < 4) {
        $rango = "Aventurero";
    } else if ($nivel < 6) {
        $rango = "Veterano";
    } else {
        $rango = "Leyenda";
    }
    return ['nivel' => $nivel, 'xp' => $xp, 'dias' => $dias, 'rango' => $rango];
}
