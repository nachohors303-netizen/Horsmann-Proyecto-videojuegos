# HORSMANN PROYECTO VIDEOJUEGOS
Trabajo Práctico Final - Sistema de gestión de usuarios con PHP y MySQL.
Tema: videojuegos. Cada usuario es un jugador con nickname, plataforma, nivel y rango.

## Cómo ejecutarlo
1. Copiar la carpeta `horsmann_proyecto_videojuegos` dentro de `C:\xampp\htdocs\`.
2. Abrir XAMPP y prender Apache y MySQL.
3. En http://localhost/phpmyadmin ir a "Importar" y subir `sql/horsmann_videojuegos.sql`.
4. Entrar a http://localhost/horsmann_proyecto_videojuegos/

## Usuarios de prueba
- Administrador (Game Master): admin@horsmann.com / Admin1234
- Jugador: lucia@correo.com / Jugador123
- Jugador: mateo@correo.com / Jugador123
- Archivado (Game Over): sofia@correo.com / Jugador123

## Archivos (equivalencia con los nombres sugeridos en la consigna)
| Consigna              | En este proyecto           |
|-----------------------|----------------------------|
| conexion.php          | conexion.php               |
| registro.php          | registro.php               |
| login.php             | login.php                  |
| panel.php             | panel.php                  |
| admin_usuarios.php    | admin_usuarios.php         |
| (registrar usuarios)  | crear_usuario.php          |
| editar_usuario.php    | editar_usuario.php         |
| archivar_usuario.php  | game_over_usuario.php      |
| usuarios_archivados.php | usuarios_game_over.php   |
| reactivar_usuario.php | continuar_partida.php      |
| cerrar_sesion.php     | cerrar_sesion.php          |
| css/estilos.css       | css/estilos.css            |

Carpeta `incluir/`: partes compartidas (menú, protección de páginas, token CSRF, reglas gamer).
