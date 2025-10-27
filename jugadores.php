<?php
session_start(); 
// Inicia la sesión para saber si el usuario está conectado
require 'verificarSesion.php'; 
// Verifica que la sesión esté activa
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

<!-- Fondo borroso detrás de todo -->
<div class="fondo"></div>

<!-- Figuras animadas cayendo -->
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

<!-- Contenedor del formulario para elegir jugadores -->
<div class="contenedor-formulario">
  <p class="titulo">Elige la cantidad de jugadores</p>

  <!-- Opciones de cantidad de jugadores -->
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

  <!-- Botón para ir al siguiente paso -->
  <button type="button" class="boton-jugar" onclick="iniciarJuego()">Jugar</button>
</div>

<script>
// Función que se ejecuta al presionar "Jugar"
function iniciarJuego() {
  const seleccion = document.querySelector('input[name="jugadores"]:checked');
  if (!seleccion) {
    alert("Por favor elegí la cantidad de jugadores"); // alerta si no se seleccionó
    return;
  }
  // Redirige a la página de nombres y colores, pasando la cantidad de jugadores
  window.location.href = `tablero.php?jugadores=${seleccion.value}`;
}

// Añade un efecto visual al seleccionar una opción
const opciones = document.querySelectorAll('.boton-opcion');
opciones.forEach(opcion => {
  opcion.addEventListener('click', () => {
    opciones.forEach(o => o.classList.remove('seleccionado')); // quita la selección previa
    opcion.classList.add('seleccionado'); // marca la opción clickeada
  });
});
</script>

</body>
</html>
