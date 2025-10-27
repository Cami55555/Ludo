
<?php
/*verifica que la sesión esté iniciada
if (!isset($_SESSION['dni']) || !isset($_SESSION['clave'])) {
    echo("Primero inicie sesión. <a href='iniciosesion.html'>Login</a> <br>");
    die("¿No tiene cuenta? <a href='registrarse.html'>Registrarse</a>");
}
*/
// Obtener datos del usuario
session_start();
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="perfil.css" />
    <title>Perfil</title>
  </head>
  <body>
    <!-- HEADER -->
    <header class="main-header">
      <div class="logo">
        <a href="/index.php"><img src="/imagenes/logo.png" alt="Home" /></a>
        <div class="texto-logo">

        </div>
      </div>
      <nav class="nav-bar">
        <a href="jugadores.php">Jugar</a>
        <a href="historia.php">Historia</a>
        <a href="reglas.php">Reglas</a>

      </nav>
    </header>
     <!-- ======================
       FONDO BORROSO
       ====================== -->
  <div class="fondo"></div>

  <!-- ======================
       FIGURAS ANIMADAS
       ====================== -->
  <div class="figuras">
    <span class="figura rojo"></span>
    <span class="figura azul"></span>
    <span class="figura verde"></span>
    <span class="figura amarillo"></span>
    <span class="figura rojo"></span>
    <span class="figura azul"></span>
    <span class="figura verde"></span>
    <span class="figura amarillo"></span>
    <span class="figura rojo"></span>
    <span class="figura azul"></span>
  </div>

    <div class="form-container">
  <p class="title">Perfil del Usuario</p>
  
  <div class="perfil-datos">
    <div class="dato">
      <p class="etiqueta">Nombre:</p>
      <p class="valor"><?php echo ucfirst($_SESSION['nombre']); ?></p>
    </div>
    <div class="dato">
      <p class="etiqueta">Apellido:</p>
      <p class="valor"><?php echo ucfirst($_SESSION['apellido']); ?></p>
    </div>
    <div class="dato">
      <p class="etiqueta">Usuario:</p>
      <p class="valor"><?php echo $_SESSION['usuario']; ?></p>
    </div>
    <div class="dato">
      <p class="etiqueta">Correo electronico:</p>
      <p class="valor"><?php echo $_SESSION['mail']; ?></p>
    </div>
  </div>
  <div class="dato">
      <p class="etiqueta">Edad:</p>
      <p class="valor"><?php echo $_SESSION['edad']; ?></p>
    </div>
  </div>
  <div class="dato">
      <p class="etiqueta">Wins:</p>
      <p class="valor"><?php echo $_SESSION['wins']; ?></p>
    </div>

     <a href="cerrarsesion.php">
    <button class="sign">Cerrar Sesión</button>
  </a>
  </div>
   
 
  <div>
  </body>
</html>
