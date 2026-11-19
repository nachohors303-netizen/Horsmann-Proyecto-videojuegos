
<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   CONTINUAR PARTIDA: inicio de sesión
   ========================================================== */

session_start();

require 'conexion.php';
require 'incluir/csrf.php';

// Si ya inició sesión, va directamente al panel.
if (isset($_SESSION['id'])) {
    header("Location: panel.php");
    exit;
}

$errores = [];
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!token_ok()) {
        $errores[] = "El formulario no es válido o venció. Probá de nuevo.";

    } else {

        $email = strtolower(trim($_POST['correo'] ?? ''));
        $clave = $_POST['clave'] ?? '';

        if ($email === '' || $clave === '') {

            $errores[] = "Escribí tu correo y tu contraseña.";

        } else {

            // Buscamos al usuario por su correo.
            $c = $pdo->prepare(
                "SELECT id, nombre, password, rol, fecha_archivado
                 FROM usuarios
                 WHERE correo = ?"
            );

            $c->execute([$email]);
            $usuario = $c->fetch(PDO::FETCH_ASSOC);

            // Comprobamos que exista el usuario.
            if (!$usuario) {

                $errores[] = "Correo o contraseña incorrectos.";

            } elseif (!password_verify($clave, $usuario['password'])) {

                $errores[] = "Correo o contraseña incorrectos.";

            } elseif ($usuario['fecha_archivado'] !== null) {

                $errores[] = "Tu cuenta está archivada. Hablá con un administrador.";

            } else {

                // Inicio de sesión correcto.
                session_regenerate_id(true);

                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];

                if ($usuario['rol'] === 'admin') {
                    header("Location: admin_usuarios.php");
                } else {
                    header("Location: panel.php");
                }

                exit;
            }
        }
    }
}

$titulo = "Iniciar sesión";
require 'incluir/encabezado.php';
?>

<div class="caja">

    <h1>Continuar partida</h1>

    <p>Iniciá sesión con tu correo y tu contraseña.</p>

    <?php require 'incluir/errores.php'; ?>

    <form method="post" action="login.php">

        <?php echo campo_token(); ?>

        <label>Correo</label>
        <input
            type="email"
            name="correo"
            value="<?php echo htmlspecialchars($email); ?>"
            required
        >

        <label>Contraseña</label>
        <input
            type="password"
            name="clave"
            required
        >

        <button type="submit" class="boton">Jugar</button>

    </form>

    <p>¿No tenés cuenta?
        <a href="registro.php">Registrate acá</a>
    </p>

</div>

<?php require 'incluir/pie.php'; ?>
