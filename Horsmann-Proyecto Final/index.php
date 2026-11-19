<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   PANTALLA DE INICIO: PRESS START
   ========================================================== */
session_start();
require 'incluir/gamer.php';
$titulo = "Inicio";
require 'incluir/encabezado.php';
?>

<section class="portada">
    <p class="pantalla">1 JUGADOR</p>
    <h1>HORSMANN<br>PROYECTO<br>VIDEOJUEGOS</h1>
    <p class="parpadeo">PRESS START</p>
    <p>Sistema de jugadores: cada uno tiene su ficha, su nickname, su nivel y su rango.</p>

    <?php if (isset($_SESSION['id'])) { ?>
        <p>Bienvenido de vuelta, <b><?php echo htmlspecialchars($_SESSION['nombre']); ?></b>.</p>
        <a class="boton" href="panel.php">Ir a mi perfil</a>
    <?php } else { ?>
        <a class="boton" href="registro.php">Nueva partida</a>
        <a class="boton boton-claro" href="login.php">Continuar partida</a>
    <?php } ?>

    <p class="plataformas">
        <?php foreach ($plataformas as $nom => $icono) { echo '<span>' . $icono . ' ' . $nom . '</span>'; } ?>
    </p>
</section>

<?php require 'incluir/pie.php'; ?>
