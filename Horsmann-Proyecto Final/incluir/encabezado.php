<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   BARRA SUPERIOR: menú y mensajes del juego
   ========================================================== */
// encabezado.php - parte de arriba de todas las páginas (menú y mensajes)
// Cada página define $titulo antes de incluir este archivo.
if (!isset($titulo)) { $titulo = "HORSMANN PROYECTO VIDEOJUEGOS"; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($titulo); ?> - HORSMANN PROYECTO VIDEOJUEGOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <a href="index.php" class="logo">🎮 HORSMANN <small>PROYECTO VIDEOJUEGOS</small></a>
    <nav>
        <?php if (isset($_SESSION['id'])) { ?>
            <a href="panel.php">Mi perfil</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') { ?>
                <a href="admin_usuarios.php">Jugadores</a>
                <a href="crear_usuario.php">Nuevo jugador</a>
                <a href="usuarios_game_over.php">Game Over</a>
            <?php } ?>
            <a href="cerrar_sesion.php">Salir</a>
        <?php } else { ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarme</a>
        <?php } ?>
    </nav>
</header>

<main>
<?php
// mensaje que guardó otra página antes de redirigir (se muestra una sola vez)
if (isset($_SESSION['mensaje'])) {
    echo '<div class="aviso ' . htmlspecialchars($_SESSION['tipo']) . '">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo']);
}
