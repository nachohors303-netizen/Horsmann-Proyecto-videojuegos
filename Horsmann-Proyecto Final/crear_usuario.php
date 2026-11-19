<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   SUMAR JUGADOR: el administrador registra un jugador nuevo
   ========================================================== */
// crear_usuario.php - el administrador registra un jugador nuevo
session_start();
require 'conexion.php';
require 'incluir/csrf.php';
require 'incluir/gamer.php';
require 'incluir/solo_admin.php';

$errores = [];
$nombre = "";
$apellido = "";
$dni = "";
$email = "";
$nickname = "";
$plataforma = "PC";
$rol = "usuario";

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

        if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $nickname === '' || $clave === '') {
            $errores[] = "Tenés que completar todos los campos.";
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
            if (strlen($clave) < 8) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres.";
            }
            if ($clave !== $clave2) {
                $errores[] = "Las contraseñas no coinciden.";
            }
            // el rol viene de un select pero igual lo controlo, porque se puede modificar
            if ($rol !== 'usuario' && $rol !== 'admin') {
                $errores[] = "El rol elegido no es válido.";
            }
        }

        if (empty($errores)) {
            $c = $pdo->prepare("SELECT id FROM usuarios WHERE dni = ?");
            $c->execute([$dni]);
            if ($c->fetch()) {
                $errores[] = "Ya hay un usuario registrado con ese DNI.";
            }

            $c = $pdo->prepare("SELECT id FROM usuarios WHERE nickname = ?");
            $c->execute([$nickname]);
            if ($c->fetch()) {
                $errores[] = "Ese nickname ya lo tiene otro jugador, elegí otro.";
            }

            $c = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $c->execute([$email]);
            if ($c->fetch()) {
                $errores[] = "Ya hay un usuario registrado con ese correo.";
            }
        }

        if (empty($errores)) {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $c = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, dni, email, nickname, plataforma, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $c->execute([$nombre, $apellido, $dni, $email, $nickname, $plataforma, $hash, $rol]);

            $_SESSION['mensaje'] = "¡Nuevo jugador sumado a la partida!";
            $_SESSION['tipo'] = "ok";
            header("Location: admin_usuarios.php");
            exit;
        }
    }
}

$titulo = "Nuevo jugador";
require 'incluir/encabezado.php';
?>

<div class="caja">
    <h1>Registrar jugador</h1>
    <?php require 'incluir/errores.php'; ?>

    <form method="post" action="crear_usuario.php">
        <?php echo campo_token(); ?>

        <label>Nombre</label>
        <input type="text" name="nombre" maxlength="50" value="<?php echo htmlspecialchars($nombre); ?>">

        <label>Apellido</label>
        <input type="text" name="apellido" maxlength="50" value="<?php echo htmlspecialchars($apellido); ?>">

        <label>DNI (sin puntos)</label>
        <input type="text" name="dni" maxlength="8" value="<?php echo htmlspecialchars($dni); ?>">

        <label>Correo electrónico</label>
        <input type="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($email); ?>">

        <label>Nickname (tu gamertag)</label>
        <input type="text" name="nickname" maxlength="20" value="<?php echo htmlspecialchars($nickname); ?>">

        <label>Plataforma favorita</label>
        <select name="plataforma">
            <?php foreach ($plataformas as $nom_plat => $icono) { ?>
                <option value="<?php echo $nom_plat; ?>" <?php if ($plataforma === $nom_plat) echo 'selected'; ?>><?php echo $icono . ' ' . $nom_plat; ?></option>
            <?php } ?>
        </select>

        <label>Rol</label>
        <select name="rol">
            <option value="usuario" <?php if ($rol === 'usuario') echo 'selected'; ?>>Jugador</option>
            <option value="admin" <?php if ($rol === 'admin') echo 'selected'; ?>>Administrador</option>
        </select>

        <label>Contraseña (mínimo 8 caracteres)</label>
        <input type="password" name="clave">

        <label>Repetir contraseña</label>
        <input type="password" name="clave2">

        <button type="submit" class="boton">Sumar jugador</button>
        <a href="admin_usuarios.php" class="boton boton-claro">Cancelar</a>
    </form>
</div>

<?php require 'incluir/pie.php'; ?>
