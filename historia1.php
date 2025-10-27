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
            Cata recordó las tardes en casa de Valen jugando Ludo con amigos, con risas, quejas cuando alguien se quedaba atrapado, y los gritos cuando alguien ganaba.

—“Me gusta —dijo Cata—. Podemos hacer que el dado tenga animaciones cool, que el tablero sea más dinámico.”

Lupe comenzó a imaginar la lógica detrás del juego.

—“Podemos programar que sea justo, que tenga modos para jugar con amigos online, y hasta con inteligencia artificial para cuando no tengas con quién jugar.”

Cami ya estaba convencida.

—“Y le podemos poner un chat con emojis para picarnos durante la partida,” agregó con una sonrisa.
          </p>
        </div>
        <div class="columna">
          <div class="imgt">
          <p>
            El profe, que había escuchado todo, asintió satisfecho.

—“Ludo es un buen punto de partida. No subestimen la simpleza; a veces, lo simple bien hecho es lo que más engancha.”

La clase terminó, pero el debate continuó en el recreo. La discusión sobre cómo hacer que el Ludo digital fuera especial se volvió más intensa.

—“No podemos hacer un tablero aburrido, tiene que tener personalidad,” dijo Cata.

—“¿Y si cada ficha tiene una pequeña animación? Algo que refleje al jugador,” propuso Valen.

—“¿Qué tal si el dado hace sonidos divertidos? Como un dado real pero más entretenido,” sugirió Lupe.
          </p>
        </div> 
        
    </div>

    
  
    <!-- Fondo animado -->
    <div class="fondo"></div>

    <div class="imgd"> 
        <a href="historia.php">
         <img src="imagenes/flechaizq.png" alt=""> 
        </a> 
    </div> 
    <div class="imgi"> 
        <a href="historia2.php"> 
          <img src="imagenes/felchader.png" alt="">
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
