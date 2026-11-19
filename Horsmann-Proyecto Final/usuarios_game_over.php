<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   SALA GAME OVER: lista de jugadores archivados
   ========================================================== */
// usuarios_game_over.php - cuentas en "Game Over", con botón para reactivarlas
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/solo_admin.php';

$c = $pdo->prepare("SELECT id, nickname, nombre, apellido, dni, email, fecha_archivado
                    FROM usuarios
                    WHERE fecha_archivado IS NOT NULL
                    ORDER BY fecha_archivado DESC");
$c->execute();
$archivados = $c->fetchAll();

$titulo = "Game Over";
require 'incluir/encabezado.php';
?>

<div class="caja ancha">
    <h1>GAME OVER</h1>
    <p>Acá están las cuentas archivadas. No pueden iniciar sesión hasta que las reactives.</p>

    <?php if (count($archivados) === 0) { ?>
        <p class="vacio">No hay usuarios archivados, todos siguen en juego.</p>
    <?php } else { ?>
        <div class="scroll">
        <table class="lista">
            <tr>
                <th>ID</th>
                <th>Nickname</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Correo</th>
                <th>Archivado el</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($archivados as $a) { ?>
            <tr>
                <td><?php echo (int)$a['id']; ?></td>
                <td class="nick"><?php echo htmlspecialchars($a['nickname']); ?></td>
                <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                <td><?php echo htmlspecialchars($a['apellido']); ?></td>
                <td><?php echo htmlspecialchars($a['dni']); ?></td>
                <td><?php echo htmlspecialchars($a['email']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($a['fecha_archivado'])); ?></td>
                <td class="acciones">
                    <form method="post" action="continuar_partida.php" onsubmit="return confirm('¿Reactivar a este jugador y dejarlo seguir jugando?');">
                        <?php echo campo_token(); ?>
                        <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
                        <button type="submit" class="boton boton-chico">Continuar partida</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
    <?php } ?>
</div>

<?php require 'incluir/pie.php'; ?>
