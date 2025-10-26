<?php
session_start();
require 'verificarSesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elegir Jugadores</title>
  <link rel="stylesheet" href="jugadores.css?v=1.0">

</head>
<body>
  <!-- Fondo borroso -->
  <div class="fondo"></div>

  <!-- Figuras animadas -->
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

  <!-- Menú de navegación -->
  <header>
    <nav class="menu-principal">
      <div class="logo">
        <a href="index.php"><img src="imagenes/logo.png" alt="Home" /></a>
      </div>
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="reglas.php">Reglas</a></li>
          <li><a href="historia.php">Historia</a></li>
          <li><a href="perfil.php">Perfil</a></li>
        </ul>
    </nav>
  </header>

  <!-- Contenedor formulario -->
  <div class="contenedor-formulario">
    <p class="titulo">Elige la cantidad de jugadores</p>

    <div class="grupo-opciones">
      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="2" required>
        2 jugadores
      </label>
      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="3">
        3 jugadores
      </label>
      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="4">
        4 jugadores
      </label>
    </div>

    <button type="button" class="boton-jugar" onclick="iniciarJuego()">Jugar</button>
  </div>

  <script>
    function iniciarJuego() {
      const seleccion = document.querySelector('input[name="jugadores"]:checked');
      if (!seleccion) {
        alert("Por favor elegí la cantidad de jugadores");
        return;
      }
      window.location.href = `color_y_nombres.php?jugadores=${seleccion.value}`;
    }

    const opciones = document.querySelectorAll('.boton-opcion');
    opciones.forEach(opcion => {
      opcion.addEventListener('click', () => {
        opciones.forEach(o => o.classList.remove('seleccionado'));
        opcion.classList.add('seleccionado');
      });
    });
  </script>
</body>
</html>
