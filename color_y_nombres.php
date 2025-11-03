<?php
session_start(); 
require 'verificarSesion.php'; 

$cantidadJugadores = isset($_GET['jugadores']) ? intval($_GET['jugadores']) : 2;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="color_y_nombres.css?v=1.0">
  <title>Elegir Nombres y Colores</title>
</head>
<body>


<!-- MENÚ NAV IGUAL QUE ME INDICASTE -->
<header>

</header>

<!-- Musica de fondo -->
<audio id="musicaFondo" src="Sonidos/Her-Hair-Was-Golden.wav" loop></audio>
<script>
const musica = document.getElementById("musicaFondo");
musica.volume = 0.2;  
if (localStorage.getItem("musicaActiva") === "true") {
  musica.play().catch(() => {});
}
</script>

<!-- Contenedor formulario -->
<div class="form-container">
  <h1 class="title">Configurar Jugadores</h1>

  <form action="tablero.php?jugadores=<?= $cantidadJugadores ?>" method="POST" id="form-jugadores">
    <input type="hidden" name="cantidadJugadores" value="<?= $cantidadJugadores ?>">

    <?php for ($i = 1; $i <= $cantidadJugadores; $i++): ?>
    <div class="jugador-container" id="jugador-<?= $i ?>">
      <h2>Jugador <?= $i ?></h2>

      <div class="input-group">
        <label for="nombre<?= $i ?>">Nombre:</label>
        <input type="text" name="nombre[]" id="nombre<?= $i ?>" required>
      </div>

      <div class="colores-container">
        <div class="circulo-color" data-color="blue"></div>
        <div class="circulo-color" data-color="red"></div>
        <div class="circulo-color" data-color="green"></div>
        <div class="circulo-color" data-color="yellow"></div>
      </div>
    </div>
    <?php endfor; ?>

    <button type="submit" class="sign">Enviar</button>
  </form>
</div>

<script>
// JS selección de colores y validación igual que antes
const cantidadJugadores = <?= $cantidadJugadores ?>;
const coloresSeleccionados = Array(cantidadJugadores).fill(null);

document.querySelectorAll('.jugador-container').forEach((jugadorContainer, idxJugador) => {
  const circulos = jugadorContainer.querySelectorAll('.circulo-color');
  circulos.forEach(circulo => {
    circulo.addEventListener('click', () => {
      circulos.forEach(c => {
        c.style.backgroundColor = '#fff';
        c.style.border = '2px solid #fff';
      });
      circulo.style.backgroundColor = circulo.dataset.color;
      circulo.style.border = '3px solid gold';
      coloresSeleccionados[idxJugador] = circulo.dataset.color;
    });
  });
});

document.getElementById('form-jugadores').addEventListener('submit', (e) => {
  const nombres = Array.from(document.querySelectorAll('input[name="nombre[]"]')).map(inp => inp.value.trim());
  const nombresSet = new Set(nombres);
  const coloresSet = new Set(coloresSeleccionados);

  let mensaje = '';
  if (nombresSet.size !== nombres.length && coloresSet.size !== coloresSeleccionados.length) {
    mensaje = "Los nombres y colores no deben repetirse entre jugadores.";
  } else if (nombresSet.size !== nombres.length) {
    mensaje = "Los nombres no deben repetirse entre jugadores.";
  } else if (coloresSet.size !== coloresSeleccionados.length) {
    mensaje = "Los colores no deben repetirse entre jugadores.";
  }

  if (mensaje) {
    alert(mensaje);
    e.preventDefault();
    return false;
  }

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
