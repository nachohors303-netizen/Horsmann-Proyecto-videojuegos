<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   FICHA DEL JUGADOR: panel personal (nivel, rango y datos)
   ========================================================== */
// panel.php - panel personal (solo con sesión iniciada)
session_start();
require 'conexion.php';
require 'incluir/proteger.php';
require 'incluir/gamer.php';

// busco los datos del usuario logueado. No traigo la contraseña
// porque no hace falta y así no hay riesgo de mostrarla.
$c = $pdo->prepare("SELECT id, nombre, apellido, dni, correo, nickname, plataforma, rol, fecha_registro FROM usuarios WHERE id = ?");$c->execute([$_SESSION['id']]);
$yo = $c->fetch();

// nivel y experiencia (se calculan con la fecha de alta, ver incluir/gamer.php)
$progreso = nivel_jugador($yo['fecha_registro']);

$titulo = "Mi perfil";
require 'incluir/encabezado.php';
?>

<div class="caja">
    <p class="pantalla">PLAYER <?php echo (int)$yo['id']; ?></p>
    <h1><?php echo htmlspecialchars($yo['gamertag'] ?? 'Jugador'); ?></h1>

    <div class="nivel">
        <span class="nivel-num">NIVEL <?php echo $progreso['nivel']; ?></span>
        <p class="rango">Rango: <?php echo $progreso['rango']; ?></p>
        <div class="barra-xp"><div class="relleno" style="width: <?php echo $progreso['xp']; ?>%"></div></div>
        <small>XP <?php echo $progreso['xp']; ?>% - llevás <?php echo $progreso['dias']; ?> día(s) en la partida</small>
    </div>

    <table class="ficha">
        <tr><th>Plataforma</th><td><?php echo ($plataformas[$yo['plataforma']] ?? '') . ' ' . htmlspecialchars($yo['plataforma']); ?></td></tr>
        <tr><th>Nombre</th><td><?php echo htmlspecialchars($yo['nombre']); ?></td></tr>
        <tr><th>Apellido</th><td><?php echo htmlspecialchars($yo['apellido']); ?></td></tr>
        <tr><th>DNI</th><td><?php echo htmlspecialchars($yo['dni']); ?></td></tr>
        <tr><th>Correo</th><td><?php echo htmlspecialchars($yo['correo']); ?></td></tr>
        <tr><th>Clase</th><td><?php echo $yo['rol'] === 'admin' ? '👑 Administrador (Game Master)' : '⚔️ Jugador'; ?></td></tr>
       <tr><th>Jugando desde</th><td><?php echo date('d/m/Y', strtotime($yo['fecha_registro'])); ?></td></tr>
    </table>

    <?php if ($yo['rol'] === 'admin') { ?>
        <a class="boton" href="admin_usuarios.php">Ir a administración</a>
    <?php } ?>
</div>

<?php require 'incluir/pie.php'; ?>
