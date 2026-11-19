<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   SALA DEL GAME MASTER: lista y buscador de jugadores activos
   ========================================================== */
// admin_usuarios.php - listado de jugadores activos + buscador (solo administradores)
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/gamer.php';
require 'incluir/solo_admin.php';

$busqueda = trim($_GET['q'] ?? '');

if ($busqueda !== '') {
    // los % sirven para que busque el texto en cualquier parte del campo
    $texto = "%" . $busqueda . "%";
    $c = $pdo->prepare("SELECT id, nickname, plataforma, nombre, apellido, dni, email, rol, fecha_creacion
                        FROM usuarios
                        WHERE fecha_archivado IS NULL
                        AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? OR email LIKE ? OR nickname LIKE ?)
                        ORDER BY apellido, nombre");
    $c->execute([$texto, $texto, $texto, $texto, $texto]);
} else {
    $c = $pdo->prepare("SELECT id, nickname, plataforma, nombre, apellido, dni, email, rol, fecha_creacion
                        FROM usuarios
                        WHERE fecha_archivado IS NULL
                        ORDER BY apellido, nombre");
    $c->execute();
}
$jugadores = $c->fetchAll();

$titulo = "Jugadores";
require 'incluir/encabezado.php';
?>

<div class="caja ancha">
    <h1>Jugadores activos</h1>
    <p>Todos los que siguen en partida.</p>

    <form method="get" action="admin_usuarios.php" class="buscador">
        <input type="text" name="q" placeholder="Buscar por nickname, nombre, apellido, DNI o correo" value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit" class="boton">Buscar</button>
        <?php if ($busqueda !== '') { ?>
            <a href="admin_usuarios.php" class="boton boton-claro">Ver todos</a>
        <?php } ?>
    </form>

    <?php if (count($jugadores) === 0) { ?>
        <p class="vacio">No se encontró ningún jugador.</p>
    <?php } else { ?>
        <div class="scroll">
        <table class="lista">
            <tr>
                <th>ID</th>
                <th>Nickname</th>
                <th>Plataforma</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Alta</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($jugadores as $j) { ?>
            <tr>
                <td><?php echo (int)$j['id']; ?></td>
                <td class="nick"><?php echo htmlspecialchars($j['nickname']); ?></td>
                <td><?php echo ($plataformas[$j['plataforma']] ?? '') . ' ' . htmlspecialchars($j['plataforma']); ?></td>
                <td><?php echo htmlspecialchars($j['nombre']); ?></td>
                <td><?php echo htmlspecialchars($j['apellido']); ?></td>
                <td><?php echo htmlspecialchars($j['dni']); ?></td>
                <td><?php echo htmlspecialchars($j['email']); ?></td>
                <td><?php echo $j['rol'] === 'admin' ? 'Admin' : 'Jugador'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($j['fecha_creacion'])); ?></td>
                <td class="acciones">
                    <a class="boton boton-chico" href="editar_usuario.php?id=<?php echo (int)$j['id']; ?>">Editar</a>

                    <?php if ($j['id'] != $_SESSION['id']) { ?>
                    <form method="post" action="game_over_usuario.php" onsubmit="return confirm('¿Seguro que querés archivar a este jugador?');">
                        <?php echo campo_token(); ?>
                        <input type="hidden" name="id" value="<?php echo (int)$j['id']; ?>">
                        <button type="submit" class="boton boton-chico boton-rojo">Archivar</button>
                    </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
    <?php } ?>
</div>

<?php require 'incluir/pie.php'; ?>
