<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <title>Ludo-Patia</title>
</head>
<body>

  <!-- Fondo animadoo -->
  <div class="fondo"></div>
  <!-- Musica de Fondo -->
  <audio id="musicaFondo" src="Sonidos/Her-Hair-Was-Golden.wav" loop></audio>
  <script>
const musica = document.getElementById("musicaFondo");
musica.volume = 0.4;  
if (localStorage.getItem("musicaActiva") === "true") {
  musica.play().catch(() => {});
}
</script>
  <!-- Menú principal -->
  <header>
    <nav class="menu-principal">
      <div class="logo">
        <a href="index.php"><img src="imagenes/logo.png" alt="Home" /></a>
      <div>
        
      <div class="logo-juego">
        <!-- <img src="imagenes/logo.png" alt="Logo Ludo-Patia"> -->
      </div>
      <ul>
        <?php
        //si ha iniciado sesion
        if (isset($_SESSION['usuario'])) {
          echo '
            <li><a href="reglas.php">Reglas</a></li>
            <li><a href="historia.php">Historia</a></li>
            <li><a href="perfil.php">Perfil</a></li>
          ';
        } //si no ha iniciado sesion
        else {
          echo '
            <li><a href="reglas.php">Reglas</a></li>
            <li><a href="historia.php">Historia</a></li>
            <li><a href="iniciosesion.html">Iniciar sesion</a></li>
          ';
        }
        ?>
      </ul>
    </nav>
  </header>

  <div class="titulos">
  <h1 class="bienvenidos">Bienvenidos a</h1>
  <h1 class="titulo-ludo">LUDO-PATIA</h1>

  <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Si el usuario inició sesión -->
    <a href="jugadores.php" class="btn-jugar">JUGAR</a>
  <?php else: ?>
    <!-- Si el usuario NO inició sesión -->
    <p class="mensaje-inicio">Iniciá sesión para poder jugar y</p>
    <p class="mensaje-inicio"> descubrir mucho más...</p>
  <?php endif; ?>
</div>


  
  <!-- Contenedor de figuras animadas -->
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
</body>
 <!--Footer hermoso-->
     <footer class="main-footer">
      <div class="footer-content">
        <div class="footer-section">
          <h3>Redes Sociales</h3><!--Apartado de redes sociales-->
          <div class="redes-sociales">
            <p>📸 Instagram: @Ludo-patia</p>
            <p>📘 Facebook: LudoGame</p>
            <p>🐦 Twitter: @Ludo-Patia-Game</p>
          </div>
        </div>
   
        <div class="footer-section"><!--Contacto-->
          <h3>Contacto</h3>
          <p>Correo: Ludopatia@gmail.com.ar</p>
          <p>Teléfono: +54 3548-462010 </p>
        </div>

        <div class="footer-section"><!--Ubicación-->
          <h3>Ubicación:</h3>
          <p>La Falda, Cordoba, San Jorge</p>
          <p>San Esteban 76</p>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Ludo-Patia. Todos los derechos reservados.</p>
      </div>
    </footer>
</html>
