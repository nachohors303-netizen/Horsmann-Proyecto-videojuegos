<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   EDITAR FICHA: el administrador modifica los datos de un jugador
   ========================================================== */
// editar_usuario.php - el administrador modifica los datos de un jugador
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/gamer.php';
require 'incluir/solo_admin.php';

// el id viene por la URL cuando abro la página y por el formulario cuando guardo
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$c = $pdo->prepare("SELECT id, nombre, apellido, dni, email, nickname, plataforma, rol FROM usuarios WHERE id = ? AND fecha_archivado IS NULL");
$c->execute([$id]);
$usuario = $c->fetch();

if (!$usuario) {
    $_SESSION['mensaje'] = "Ese jugador no existe o está archivado.";
    $_SESSION['tipo'] = "error";
    header("Location: admin_usuarios.php");
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!token_ok()) {
        $errores[] = "El formulario no es válido o venció. Probá de nuevo.";
    } else {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $dni = trim($_POST['dni'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $nickname = trim($_POST['nickname'] ?? '');
        $plataforma = $_POST['plataforma'] ?? 'PC';
        $rol = $_POST['rol'] ?? 'usuario';
        $clave = $_POST['clave'] ?? '';
        $clave2 = $_POST['clave2'] ?? '';

        // un admin no puede sacarse el rol a sí mismo (si no, nos quedamos sin admin)
        if ($id == $_SESSION['id']) {
            $rol = 'admin';
        }

        // guardo lo que escribió para que el formulario no se borre si hay errores
        $usuario['nombre'] = $nombre;
        $usuario['apellido'] = $apellido;
        $usuario['dni'] = $dni;
        $usuario['email'] = $email;
        $usuario['nickname'] = $nickname;
        $usuario['plataforma'] = $plataforma;
        $usuario['rol'] = $rol;

        if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $nickname === '') {
            $errores[] = "Tenés que completar nombre, apellido, DNI y correo.";
        } else {
            if (!preg_match('/^[\p{L} ]{2,50}$/u', $nombre)) {
                $errores[] = "El nombre solo puede tener letras (mínimo 2).";
            }
            if (!preg_match('/^[\p{L} ]{2,50}$/u', $apellido)) {
                $errores[] = "El apellido solo puede tener letras (mínimo 2).";
            }
            if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $nickname)) {
                $errores[] = "El nickname debe tener de 3 a 20 caracteres: letras, números o guion bajo.";
            }
            if (!array_key_exists($plataforma, $plataformas)) {
                $errores[] = "La plataforma elegida no es válida.";
            }
            if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
                $errores[] = "El DNI debe tener 7 u 8 números, sin puntos.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "El correo electrónico no es válido.";
            }
            if ($rol !== 'usuario' && $rol !== 'admin') {
                $errores[] = "El rol elegido no es válido.";
            }
        }

        // la contraseña nueva es opcional: solo la cambio si escribió algo
        $cambiar_clave = ($clave !== '' || $clave2 !== '');
        if ($cambiar_clave) {
            if (strlen($clave) < 8) {
                $errores[] = "La contraseña nueva debe tener al menos 8 caracteres.";
            }
            if ($clave !== $clave2) {
                $errores[] = "Las contraseñas no coinciden.";
            }
        }

        // que el DNI y el correo no los tenga OTRO usuario (por eso "id != ?")
        if (empty($errores)) {
            $c = $pdo->prepare("SELECT id FROM usuarios WHERE dni = ? AND id != ?");
            $c->execute([$dni, $id]);
            if ($c->fetch()) {
                $errores[] = "Otro usuario ya tiene ese DNI.";
            }

            $c = $pdo->prepare("SELECT id FROM usuarios WHERE nickname = ? AND id != ?");
            $c->execute([$nickname, $id]);
            if ($c->fetch()) {
                $errores[] = "Ese nickname ya lo usa otro jugador.";
            }

            $c = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $c->execute([$email, $id]);
            if ($c->fetch()) {
                $errores[] = "Otro usuario ya tiene ese correo.";
            }
        }

        if (empty($errores)) {
            if ($cambiar_clave) {
                $hash = password_hash($clave, PASSWORD_DEFAULT);
                $c = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, email = ?, nickname = ?, plataforma = ?, rol = ?, password = ? WHERE id = ?");
                $c->execute([$nombre, $apellido, $dni, $email, $nickname, $plataforma, $rol, $hash, $id]);
            } else {
                $c = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, email = ?, nickname = ?, plataforma = ?, rol = ? WHERE id = ?");
                $c->execute([$nombre, $apellido, $dni, $email, $nickname, $plataforma, $rol, $id]);
            }

            $_SESSION['mensaje'] = "Ficha del jugador actualizada.";
            $_SESSION['tipo'] = "ok";
            header("Location: admin_usuarios.php");
            exit;
        }
    }
}

$titulo = "Editar jugador";
require 'incluir/encabezado.php';
?>

<div class="caja">
    <h1>Editar jugador #<?php echo (int)$usuario['id']; ?></h1>
    <?php require 'incluir/errores.php'; ?>

    <form method="post" action="editar_usuario.php">
        <?php echo campo_token(); ?>
        <input type="hidden" name="id" value="<?php echo (int)$usuario['id']; ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" maxlength="50" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">

        <label>Apellido</label>
        <input type="text" name="apellido" maxlength="50" value="<?php echo htmlspecialchars($usuario['apellido']); ?>">

        <label>DNI</label>
        <input type="text" name="dni" maxlength="8" value="<?php echo htmlspecialchars($usuario['dni']); ?>">

        <label>Correo electrónico</label>
        <input type="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($usuario['email']); ?>">

        <label>Nickname (gamertag)</label>
        <input type="text" name="nickname" maxlength="20" value="<?php echo htmlspecialchars($usuario['nickname']); ?>">

        <label>Plataforma favorita</label>
        <select name="plataforma">
            <?php foreach ($plataformas as $nom_plat => $icono) { ?>
                <option value="<?php echo $nom_plat; ?>" <?php if ($usuario['plataforma'] === $nom_plat) echo 'selected'; ?>><?php echo $icono . ' ' . $nom_plat; ?></option>
            <?php } ?>
        </select>

        <label>Rol</label>
        <?php if ($usuario['id'] == $_SESSION['id']) { ?>
            <p>Administrador (no podés cambiar tu propio rol)</p>
        <?php } else { ?>
            <select name="rol">
                <option value="usuario" <?php if ($usuario['rol'] === 'usuario') echo 'selected'; ?>>Jugador</option>
                <option value="admin" <?php if ($usuario['rol'] === 'admin') echo 'selected'; ?>>Administrador</option>
            </select>
        <?php } ?>

        <label>Nueva contraseña (dejala vacía si no querés cambiarla)</label>
        <input type="password" name="clave" autocomplete="new-password">

        <label>Repetir nueva contraseña</label>
        <input type="password" name="clave2" autocomplete="new-password">

        <button type="submit" class="boton">Guardar cambios</button>
        <a href="admin_usuarios.php" class="boton boton-claro">Cancelar</a>
    </form>
</div>

<?php require 'incluir/pie.php'; ?>
