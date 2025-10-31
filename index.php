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
            <li><a href="jugadores.php">Jugar</a></li>
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

  <!-- Títulos principales -->
  <div class="titulos">
    <h2 class="bienvenidos">Bienvenidos a</h2>
    <h1 class="titulo-ludo">LUDO-PATIA</h1>
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
</html>
