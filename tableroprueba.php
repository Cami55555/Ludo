<?php
// Inicia la sesión para acceder a las variables de sesión
session_start();

// Incluye un archivo que verifica si hay sesión activa
require 'verificarSesion.php'; 

// Arrays para almacenar nombres y colores de los jugadores
$jugadores = [];
$colores = [];

// Verifica si se enviaron datos vía POST desde color_y_nombres.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['color'])) {
        $jugadores = $_POST['nombre']; // array con nombres de los jugadores
        $colores = $_POST['color'];    // array con colores elegidos
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Enlaza el CSS para estilos y animaciones -->
  <link rel="stylesheet" href="tableroprueba.css">
  <title>Tablero de Ludo</title>
</head>
<body>
  <!-- =======================
       FONDO BORROSO Y FIGURAS
       ======================= -->
  <div class="fondo"></div> <!-- Fondo borroso -->
  <div class="figuras">
    <!-- Cada span representa una figura animada -->
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

  <!-- =======================
       NAV / MENÚ
       ======================= -->
  <header class="main-header">
    <div class="logo">
      <a href="/index.php"><img src="/imagenes/logo.png" alt="Home" /></a>
    </div>
    <nav class="nav-bar">
      <a href="index.php">Inicio</a>
      <a href="jugar.php">Jugar</a>
      <a href="reglas.php">Reglas</a>
      <a href="perfil.php">Perfil</a>
    </nav>
  </header>

  <!-- =======================
       TURNO DEL JUGADOR
       ======================= -->
  <div class="turno-container">
    <span class="texto-turno">Le toca a:</span>
    <span id="nombre-turno"></span> <!-- Se llenará dinámicamente -->
  </div>

  <!-- =======================
       TABLERO
       ======================= -->
  <div class="contenedor-tablero">
    <div class="tablero-container">
      <img src="imagenes/tablero.png" alt="Tablero de Ludo" class="imagen-tablero" />
      <canvas id="canvas-ludo"></canvas> <!-- Canvas donde se dibujan las fichas -->
    </div>

    <!-- DADO -->
    <div class="dado-container">
      <p class="titulo-dado">Tirar Dado</p>
      <img id="dado" src="imagenes/dado1.png" alt="Dado" /> <!-- Imagen del dado -->
    </div>

    <!-- OPCIONES DEL JUGADOR (sacar o mover ficha) -->
    <div id="opciones-jugador" style="display: none;">
      <p>Elegí una opción:</p>
      <button onclick="sacarFicha()">Sacar ficha</button>
      <button onclick="moverFichaExistente()">Mover ficha existente</button>
    </div>
  </div>

<script>
  // Arrays traídos desde PHP (jugadores y sus colores)
  const nombresColores = <?php echo json_encode($jugadores); ?>;
  const colores = <?php echo json_encode($colores); ?>;
  const cantidadJugadores = nombresColores.length;

  // Canvas para dibujar fichas
  const canvas = document.getElementById('canvas-ludo');
  const ctx = canvas.getContext('2d');
  const tablero = document.querySelector('.imagen-tablero');
  const dado = document.getElementById('dado');
  const nombreTurno = document.getElementById('nombre-turno');

  // Variables de control del juego
  let salio6 = false;           // indica si salió un 6
  let dadoTirado = false;       // evita tirar el dado dos veces seguidas
  let numeroDado = 0;           // valor del dado
  let turnoActual = 0;          // índice del jugador actual
  let gfichas = [3, 3, 3, 3];  // fichas en "casa" de cada jugador
  let tfichas = [-1, -1, -1, -1]; // posición de fichas en el tablero
  let fichaSeleccionada = false;
  const radioFicha = 17;        // radio de las fichas para dibujarlas

  // Posiciones iniciales de fichas de cada color (por porcentaje del canvas)
  const posiciones = {
      green: [{x:0.20,y:0.22},{x:0.30,y:0.22},{x:0.20,y:0.31},{x:0.30,y:0.31}],
      blue: [{x:0.73,y:0.71},{x:0.83,y:0.71},{x:0.73,y:0.80},{x:0.83,y:0.80}],
      red: [{x:0.20,y:0.71},{x:0.30,y:0.71},{x:0.20,y:0.80},{x:0.30,y:0.80}],
      yellow: [{x:0.73,y:0.22},{x:0.83,y:0.22},{x:0.73,y:0.31},{x:0.83,y:0.31}]
  };

  // Puntos de seguimiento para sacar ficha de "casa"
  const seguimiento = {
      green: {x:0.163,y:0.455},
      blue: {x:0.865,y:0.564},
      red: {x:0.4545,y:0.836},
      yellow: {x:0.574,y:0.182}
  };

  // Ajusta tamaño del canvas según la imagen del tablero
  function ajustarCanvas() {
    canvas.width = tablero.clientWidth;
    canvas.height = tablero.clientHeight;
  }

  // Inicialización al cargar página
  window.onload = () => {
    ajustarCanvas();
    dibujarFichas(); // dibuja las fichas iniciales
    mostrarTurno();  // muestra nombre del jugador actual
  };

  // Ajusta canvas al cambiar tamaño de ventana
  window.addEventListener('resize', () => {
    ajustarCanvas();
    dibujarFichas();
  });

  // Función que dibuja todas las fichas
  function dibujarFichas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height); // limpia canvas
    for (let i = 0; i < cantidadJugadores; i++) {
      const colorJugador = colores[i];
      if (!posiciones[colorJugador]) continue;

      posiciones[colorJugador].forEach((pos, j) => {
        const cx = pos.x * canvas.width;
        const cy = pos.y * canvas.height;

        // dibuja la ficha
        ctx.beginPath();
        ctx.arc(cx, cy, radioFicha, 0, Math.PI * 2);
        ctx.fillStyle = colorJugador;
        ctx.fill();
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.stroke();

        // resalta ficha seleccionada
        if (fichaSeleccionada && fichaSeleccionada.jugador === i && fichaSeleccionada.indice === j) {
          ctx.lineWidth = 5;
          ctx.strokeStyle = 'gold';
          ctx.beginPath();
          ctx.arc(cx, cy, radioFicha + 5, 0, Math.PI * 2);
          ctx.stroke();
        }
      });
    }
  }

  // Detecta clicks sobre las fichas
  canvas.addEventListener('click', (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    let encontrada = null;
    for (let i = 0; i < cantidadJugadores; i++) {
      const colorJugador = colores[i];
      const fichas = posiciones[colorJugador];
      if (!fichas) continue;
      for (let j = 0; j < fichas.length; j++) {
        const cx = fichas[j].x * canvas.width;
        const cy = fichas[j].y * canvas.height;
        if (Math.sqrt((x-cx)**2 + (y-cy)**2) <= radioFicha) {
          encontrada = { jugador: i, indice: j };
          break;
        }
      }
      if (encontrada) break;
    }

    // Si se hizo click sobre ficha, seleccionarla
    if (encontrada) {
      fichaSeleccionada = encontrada;
      dibujarFichas();
    }
  });

  // Imágenes del dado
  const carasDado = [
    'imagenes/dado1.png','imagenes/dado2.png','imagenes/dado3.png',
    'imagenes/dado4.png','imagenes/dado5.png','imagenes/dado6.png'
  ];

  // Función para tirar dado
  function tirarDado() {
    if (dadoTirado) return; // evita tirar dos veces
    dadoTirado = true;
    let contador = 0;

    const animacion = setInterval(() => {
      const randomIndex = Math.floor(Math.random() * 6);
      dado.src = carasDado[randomIndex]; // cambia imagen del dado
      contador++;
      if (contador >= 10) {
        clearInterval(animacion);
        numeroDado = randomIndex + 1;
        movimientopieza(numeroDado);
        dadoTirado = false;
      }
    }, 100);
  }

  // Control del turno después de tirar dado
  function movimientopieza() {
    if (numeroDado === 6) {
      salio6 = true;
      document.getElementById('opciones-jugador').style.display = 'block';
    } else {
      salio6 = false;
      document.getElementById('opciones-jugador').style.display = 'none';
      pasarTurno();
    }
  }

  // Función para sacar ficha de la "casa"
  function sacarFicha() {
    if (gfichas[turnoActual] >= 0) {
      let colorActual = colores[turnoActual];
      posiciones[colorActual][gfichas[turnoActual]] = seguimiento[colorActual]; // coloca ficha en inicio
      document.getElementById('opciones-jugador').style.display = 'none';
      gfichas[turnoActual]--; // reduce fichas en casa
      tfichas[turnoActual]++; // aumenta fichas en el tablero
    }
    dibujarFichas();
  }

  // Función para mover ficha ya existente (pendiente de implementar)
  function moverFichaExistente() {
    dibujarFichas();
  }

  // Muestra el jugador actual
  function mostrarTurno() {
    const nombre = nombresColores[turnoActual];
    const color = colores[turnoActual];
    nombreTurno.textContent = nombre;
    nombreTurno.style.color = color; // nombre en color del jugador
  }

  // Pasa al siguiente turno
  function pasarTurno() {
    turnoActual = (turnoActual + 1) % cantidadJugadores;
    mostrarTurno();
  }

  // Evento al hacer click sobre dado
  dado.addEventListener('click', tirarDado);
</script>

</body>
</html>
