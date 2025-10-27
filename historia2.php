<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="historia.css">
    <title>Historia</title>
  </head>
  <body>
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
            <li><a href="reglas.php">Reglas</a></li>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
          ';
        } //si no ha iniciado sesion
        else {
          echo '
            <li><a href="reglas.php">Reglas</a></li>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="iniciosesion.html">Iniciar sesion</a></li>
          ';
        }
        ?>
      </ul>
      </nav>
    </header>
    
    <!--titulo-->
       <div class="titulos">
        <h2 class="titulo-ludo">Historia</h2>
      </div>
    <div class="recuadros-reglas">
      

      <div class="columna">
          <p>
            —“Y que las partidas puedan guardarse para retomarlas después,” añadió Cami, pensando en lo práctico.

La emoción crecía, y con ella las ganas de comenzar a programar.

Durante las semanas siguientes, las discusiones siguieron. Hubo momentos en que casi se pelean porque Lupe quería que el juego fuera súper justo y sin trampas, mientras Valen insistía en poner “sorpresas” para que la partida fuera impredecible.

Cami quería una interfaz limpia, pero Cata soñaba con colores y animaciones que explotaran en la pantalla.

Pero cada desacuerdo los hacía pensar mejor, buscar soluciones y sobre todo, trabajar más unidos.
          </p>
        </div>
        <div class="columna">
          <div class="imgt">
          <p>
          Finalmente, en la última semana antes de entregar el proyecto, después de muchas horas sin dormir, el Ludo digital estaba listo.

Cuando lo probaron juntos por primera vez, hubo risas, gritos de emoción y hasta algún “¡No puede ser!” cuando una ficha inesperadamente sacó ventaja.

Ese día, entendieron que no solo habían programado un juego, sino que habían construido algo mucho más grande: una experiencia, un recuerdo y una historia para contar.


          </p>
        </div> 
        
    </div>

    
  
    <!-- Fondo animado -->
    <div class="fondo"></div>

    <div class="imgd"> 
        <a href="historia1.php">
         <img src="imagenes/flechaizq.png" alt=""> 
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
