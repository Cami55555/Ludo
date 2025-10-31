<?php
session_start(); 
// Inicia sesión para saber quién está conectadoo

require 'verificarSesion.php'; 
// Verifica que la sesión esté activa

// Obtiene la cantidad de jugadores desde la URL (si no hay, pone 2)
$cantidadJugadores = isset($_GET['jugadores']) ? intval($_GET['jugadores']) : 2;
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="color_y_nombres.css">
  <title>Elegir Nombres y Colores</title>
</head>
<body>



<!-- Contenedor del formulario -->
<div class="form-container">
  <h1 class="title">Configurar Jugadores</h1>

  <!-- Formulario que enviará los datos al tablero -->
  <form action="tablero.php?jugadores=<?= $cantidadJugadores ?>" method="POST" id="form-jugadores">
    <!-- Guarda la cantidad de jugadores de manera oculta -->
    <input type="hidden" name="cantidadJugadores" value="<?= $cantidadJugadores ?>">

    <!-- Crea dinámicamente los inputs de cada jugador -->
    <?php for ($i = 1; $i <= $cantidadJugadores; $i++): ?>
    <div class="jugador-container" id="jugador-<?= $i ?>">
      <h2>Jugador <?= $i ?></h2>

      <!-- Nombre del jugador -->
      <div class="input-group">
        <label for="nombre<?= $i ?>">Nombre:</label>
        <input type="text" name="nombre[]" id="nombre<?= $i ?>" required>
      </div>

      <!-- Círculos para elegir color -->
      <div class="colores-container">
        <div class="circulo-color" data-color="blue"></div>
        <div class="circulo-color" data-color="red"></div>
        <div class="circulo-color" data-color="green"></div>
        <div class="circulo-color" data-color="yellow"></div>
      </div>
    </div>
    <?php endfor; ?>

    <!-- Botón para enviar el formulario -->
    <button type="submit" class="sign">Enviar</button>
  </form>
</div>

<script>
// Guarda la cantidad de jugadores en JS
const cantidadJugadores = <?= $cantidadJugadores ?>;
// Arreglo para guardar el color elegido por cada jugador
const coloresSeleccionados = Array(cantidadJugadores).fill(null);

// Configura los círculos de colores para seleccionar
document.querySelectorAll('.jugador-container').forEach((jugadorContainer, idxJugador) => {
  const circulos = jugadorContainer.querySelectorAll('.circulo-color');

  circulos.forEach(circulo => {
    // Estilo base de los círculos
    circulo.style.width = '30px';
    circulo.style.height = '30px';
    circulo.style.borderRadius = '50%';
    circulo.style.display = 'inline-block';
    circulo.style.marginRight = '10px';
    circulo.style.cursor = 'pointer';
    circulo.style.backgroundColor = '#fff';
    circulo.style.border = '2px solid #000';

    // Al hacer click, se selecciona el color
    circulo.addEventListener('click', () => {
      // Deselecciona todos los círculos del jugador
      circulos.forEach(c => {
        c.style.backgroundColor = '#fff';
        c.style.border = '2px solid #000';
      });

      // Selecciona el círculo clickeado
      circulo.style.backgroundColor = circulo.dataset.color;
      circulo.style.border = '3px solid gold';
      coloresSeleccionados[idxJugador] = circulo.dataset.color;
    });
  });
});

// Validación al enviar el formulario
document.getElementById('form-jugadores').addEventListener('submit', (e) => {
  // Obtiene los nombres y colores seleccionados
  const nombres = Array.from(document.querySelectorAll('input[name="nombre[]"]')).map(inp => inp.value.trim());
  const nombresSet = new Set(nombres);
  const coloresSet = new Set(coloresSeleccionados);

  let mensaje = '';
  // Verifica que no haya nombres ni colores repetidos
  if (nombresSet.size !== nombres.length && coloresSet.size !== coloresSeleccionados.length) {
    mensaje = "Los nombres y colores no deben repetirse entre jugadores.";
  } else if (nombresSet.size !== nombres.length) {
    mensaje = "Los nombres no deben repetirse entre jugadores.";
  } else if (coloresSet.size !== coloresSeleccionados.length) {
    mensaje = "Los colores no deben repetirse entre jugadores.";
  }

  // Si hay error, alerta y cancela envío
  if (mensaje) {
    alert(mensaje);
    e.preventDefault();
    return false;
  }

  // Agrega los colores elegidos al formulario como inputs ocultos
  const form = document.getElementById('form-jugadores');
  coloresSeleccionados.forEach(color => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'color[]';
    input.value = color;
    form.appendChild(input);
  });
});
</script>

</body>
</html>
