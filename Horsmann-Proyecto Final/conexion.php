<?php
/* ==========================================================
   HORSMANN PROYECTO VIDEOJUEGOS
   CABLE DE RED: conecta el juego con la base de datos (PDO)
   ========================================================== */
// conexion.php
// Conexión a la base de datos con PDO. Lo incluyo en todas las páginas que usan la base.

$host = "localhost";
$bd = "horsmann_videojuegos";
$user = "root";   // XAMPP trae root por defecto
$pass = "";       // y sin contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bd;charset=utf8mb4", $user, $pass);
    // que tire error si algo sale mal con una consulta
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // para que los resultados vengan como $fila['nombre']
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos. Fijate que MySQL esté prendido en XAMPP.");
}
