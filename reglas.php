<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reglas.css">
    <title>Reglas</title>
  </head>
  <body>

     <!-- Musica de Fondoo -->
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

        </div>
        <div class="logo-juego">
          <!-- Aquí se agregará el logo del juego más adelante -->
        </div>
<ul>
        <?php
        //si ha iniciado sesion
        if (isset($_SESSION['usuario'])) {
          echo '
            <li><a href="jugadores.php">Jugar</a></li>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="historia.php">Historia</a></li>
            <li><a href="perfil.php">Perfil</a></li>
          ';
        } //si no ha iniciado sesion
        else {
          echo '
            <li><a href="index.php">Inicio</a></li>
            <li><a href="historia.php">Historia</a></li>
            <li><a href="iniciosesion.html">Iniciar sesion</a></li>
          ';
        }
        ?>
      </ul>
      </nav>
    </header>
       
    <!--titulo-->
       <div class="titulos">
        <h2 class="titulo-ludo">Reglas</h2>
      </div>
    <div class="recuadros-reglas">
      
      
      <div class="columna">
          <p>
            <h1>Preparación:</h1>
            Cada jugador elige un color (rojo, azul, amarillo o verde).
            Se puede jugar de 2, 3 o 4 jugadores.
            Cada jugador coloca sus 4 fichas en la prisión (su zona de inicio).
          </p>
        </div>
        <div class="columna">
          <div class="img">
          <p>
            <img src="imagenes/tableroreglas.png" alt="">
          </p>
        </div> 
        
    </div>

    
  
    <!-- Fondo animado -->
    <div class="fondo"></div>
      <div class="imgi">
      <a href="reglas1.php">
        <img src="imagenes/felchader.png" alt="Descripción de la imagen">
      </a>
    </div>

  <!-- Contenedor de figuras animadas -->
  <div class="figuras">
    <!-- Se repiten varias figuras con diferentes colores -->
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
</html>
