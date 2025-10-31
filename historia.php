<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="historia.css">
    <title>Historia</title>
  </head>
  <body>

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

        </div>
        <div class="logo-juego">
          <!-- Aquí se agregará el logo del juego más adelantee -->
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
        <h2 class="titulo-ludo">Historia

        </h2>
      </div>
    <div class="recuadros-reglas">
      

      <div class="columna">
          <p>
           Era un lunes cualquiera en la escuela, pero en la clase de programación algo especial estaba por pasar. El profe entró con una sonrisa y dijo:

—“Chicos, para el proyecto final van a elegir qué quieren programar. Puede ser un juego, una app, lo que quieran, pero tienen que trabajar en equipo.”

Cata, Lupe, Cami y Valen se miraron. Era la oportunidad que habían estado esperando para hacer algo juntos, pero elegir el proyecto sería todo un desafío.

—“¿Y qué se les ocurre?” —preguntó el profe, mientras empezaba a repartir hojas.

Lupe fue la primera en hablar:
          </p>
        </div>
        <div class="columna">
          <div class="img">
          <p>
            —“Podríamos hacer un juego súper complejo, algo con gráficos 3D, inteligencia artificial…”

—“¿Pero quién va a terminar algo así a tiempo?” —interrumpió Cata con una sonrisa— “Yo digo que algo simple y divertido.”

Valen, que estaba medio distraído, levantó la mirada y soltó:

—“¿Y si hacemos un Ludo? Todos lo conocemos y sería divertido programarlo.”

Hubo un silencio incómodo. Cami se rió:
—“¿Ludo? ¿En serio? Eso es tan básico que no sé si vale la pena.”

—“Pero eso es lo bueno,” dijo Valen con entusiasmo— “Es simple, podemos hacerlo rápido, y si le ponemos cosas nuestras, seguro queda genial.”
          </p>
        </div> 
        
    </div>

    
  
    <!-- Fondo animado -->
    <div class="fondo"></div>
      <div class="imgi">
      <a href="historia1.php">
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
