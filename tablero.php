<?php
session_start();

// Si viene información del formulario:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombres = $_POST['nombre'] ?? [];
  $colores = $_POST['color'] ?? [];
  $cantidadJugadores = $_POST['cantidadJugadores'] ?? 0;

  // Guardamos en sesión para que el JS o el juego puedan usarlos
  $_SESSION['jugadores'] = [];
  for ($i = 0; $i < $cantidadJugadores; $i++) {
    $_SESSION['jugadores'][] = [
      'nombre' => $nombres[$i],
      'color' => $colores[$i]
    ];
  }
}

// Recuperamos si ya está guardado
$jugadores = $_SESSION['jugadores'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablero de Ludo - Corregido</title>
  <style>
    /* ----------------------
   BODY GENERAL
----------------------- */
    body {
      margin: 0;
      padding: 0;
      background: #222;
      font-family: sans-serif;
    }

    /* ----------------------
   MENU PRINCIPAL
----------------------- */
    .menu-principal {
      background: #444;
      height: 60px;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .menu-principal ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    .menu-principal li a {
      text-decoration: none;
      color: #fff;
      font-weight: bold;
      transition: 0.3s;
    }

    .menu-principal li a:hover {
      color: #00ff88;
    }

    /* ----------------------
   CONTENEDOR DEL TABLERO Y DADO
----------------------- */
    .contenedor-tablero {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 40px;
      margin-top: 20px;
    }

    /* ----------------------
   TABLERO
----------------------- */
    .tablero-container {
      position: relative;
      width: 70vw;
      max-width: 600px;
    }

    .imagen-tablero {
      width: 100%;
      height: auto;
      display: block;
    }

    #canvas-ludo {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    /* ----------------------
   DADO
----------------------- */
    .dado-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .titulo-dado {
      color: #fff;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .dado-container img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      cursor: pointer;
      transition: 0.2s;
    }

    .dado-container img:hover {
      transform: scale(1.1);
    }

    /* ----------------------
   CONTROLES
----------------------- */
    .controles {
      text-align: center;
      margin: 20px;
    }

    .controles button {
      background: #444;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 5px;
      cursor: pointer;
      border-radius: 5px;
    }

    .controles button:hover {
      background: #666;
    }

    #turno {
      color: white;
      font-size: 20px;
      font-weight: bold;
      margin: 10px;
      text-align: center;
    }

    #opciones-jugador {
      background: #333;
      padding: 15px;
      border-radius: 10px;
      margin-top: 20px;
    }

    #opciones-jugador p {
      color: white;
      margin: 0 0 10px 0;
    }
  </style>
</head>

 <!-- Musica de Fondo -->
  <audio id="musicaFondo" src="Sonidos/Her-Hair-Was-Golden.wav" loop></audio>
  <script>
const musica = document.getElementById("musicaFondo");
musica.volume = 0.2;  
if (localStorage.getItem("musicaActiva") === "true") {
  musica.play().catch(() => {});
}
</script> 

<audio id="sonido-dado" src="Sonidos/dado.wav"></audio>
<body>
  <!-- ENCABEZADO Y MENÚ -->
  <header>
    <nav class="menu-principal">
      <ul>
        <li><a href="index.php">Inicio</a></li>
        <li><a href="reglas.php">Reglas</a></li>
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="index.php">Salir</a></li>
      </ul>
    </nav>
  </header>

  <div class="controles">
      
    <div id="turno"></div>
  </div>

  <!-- CONTENEDOR DEL TABLERO Y DADO -->
  <div class="contenedor-tablero">
    <!-- TABLERO -->
    <div class="tablero-container">
      <img src="imagenes/tablero.png" alt="Tablero de Ludo" class="imagen-tablero" />
      <canvas id="canvas-ludo"></canvas>
    </div>

    <!-- DADO -->
    <div class="dado-container">
      <p class="titulo-dado">Tirar Dado</p>
      <img id="dado" src="imagenes/dado1.png" alt="Dado" />

      <!-- opciones cuando saca 6 -->
      <div id="opciones-jugador" style="display: none;">
        <p>Elegí una opción:</p>
        <button onclick="sacarFicha()">Sacar ficha</button>
        <button onclick="moverFichaExistente()">Mover ficha existente</button>
      </div>
    </div>
  </div>

  <script>
    // =====================
    // VARIABLES GLOBALES
    // =====================
    const colores = ['red', 'green', 'yellow', 'blue'];
    const nombresColores = ['rojo', 'verde', 'amarillo', 'azul'];

    const canvas = document.getElementById('canvas-ludo');
    const ctx = canvas.getContext('2d');
    const tablero = document.querySelector('.imagen-tablero');
    const dado = document.getElementById('dado');
    const turnoTexto = document.getElementById('turno');
    const jugadores = <?= json_encode($jugadores) ?>;
    // Estado del juego
    let salio6 = false;
    let dadoTirado = false;
    let numeroDado = 0;
    let turnoActual = 0;
    let esperandoMovimiento = false;

    let gfichas = [3, 3, 3, 3]; // fichas en garage
    let tfichas = [-1, -1, -1, -1]; // fichas en tablero
    let win = false;
    let fichaSeleccionada = null;

    // Configuración del juego
    const urlParams = new URLSearchParams(window.location.search);
    const cantidadJugadores = parseInt(urlParams.get('jugadores')) || 4;
    const radioFicha = 17;

    const entradaJugadores = {
      rojo: 0,
      verde: 13,
      amarillo: 26,
      azul: 39
    };

    // =====================
    // POSICIONES Y RECORRIDO
    // POSICIONES Y RECORRIDOo
    // =====================
    const recorrido = [{
        x: 0.4545,
        y: 0.836
      }, // Casilla 0
      {
        x: 0.4545,
        y: 0.78
      }, // Casilla 1
      {
        x: 0.4545,
        y: 0.724
      }, // Casilla 2
      {
        x: 0.4545,
        y: 0.668
      }, // Casilla 3
      {
        x: 0.4545,
        y: 0.616
      }, // Casilla 4
      {
        x: 0.398,
        y: 0.564
      }, // Casilla 5
      {
        x: 0.337,
        y: 0.564
      }, // Casilla 6
      {
        x: 0.2795,
        y: 0.564
      }, // Casilla 7
      {
        x: 0.2218,
        y: 0.564
      }, // Casilla 8
      {
        x: 0.163,
        y: 0.564
      }, // Casilla 9
      {
        x: 0.103,
        y: 0.564
      }, // Casilla 10
      {
        x: 0.103,
        y: 0.509
      }, // Casilla 11
      {
        x: 0.103,
        y: 0.452
      }, // Casilla 12
      {
        x: 0.163,
        y: 0.452
      }, // Casilla 13 (inicio VERDE)
      {
        x: 0.220,
        y: 0.452
      }, // Casilla 14
      {
        x: 0.28,
        y: 0.452
      }, // Casilla 15
      {
        x: 0.338,
        y: 0.452
      }, // Casilla 16
      {
        x: 0.398,
        y: 0.452
      }, // Casilla 17
      {
        x: 0.4545,
        y: 0.398
      }, // Casilla 18
      {
        x: 0.4545,
        y: 0.346
      }, // Casilla 19
      {
        x: 0.4545,
        y: 0.289
      }, // Casilla 20
      {
        x: 0.4545,
        y: 0.235
      }, // Casilla 21
      {
        x: 0.4545,
        y: 0.18
      }, // Casilla 22
      {
        x: 0.4545,
        y: 0.128
      }, // Casilla 23
      {
        x: 0.514,
        y: 0.128
      }, // Casilla 24
      {
        x: 0.575,
        y: 0.128
      }, // Casilla 25
      {
        x: 0.575,
        y: 0.182
      }, // Casilla 26 (inicio AMARILLO)
      {
        x: 0.575,
        y: 0.235
      }, // Casilla 27
      {
        x: 0.575,
        y: 0.289
      }, // Casilla 28
      {
        x: 0.575,
        y: 0.346
      }, // Casilla 29
      {
        x: 0.575,
        y: 0.398
      }, // Casilla 30
      {
        x: 0.632,
        y: 0.452
      }, // Casilla 31
      {
        x: 0.692,
        y: 0.452
      }, // Casilla 32
      {
        x: 0.750,
        y: 0.452
      }, // Casilla 33
      {
        x: 0.808,
        y: 0.452
      }, // Casilla 34
      {
        x: 0.865,
        y: 0.452
      }, // Casilla 35
      {
        x: 0.926,
        y: 0.452
      }, // Casilla 36
      {
        x: 0.926,
        y: 0.509
      }, // Casilla 37
      {
        x: 0.926,
        y: 0.564
      }, // Casilla 38
      {
        x: 0.865,
        y: 0.564
      }, // Casilla 39 (inicio AZUL)
      {
        x: 0.808,
        y: 0.564
      }, // Casilla 40
      {
        x: 0.750,
        y: 0.564
      }, // Casilla 41
      {
        x: 0.692,
        y: 0.564
      }, // Casilla 42
      {
        x: 0.632,
        y: 0.564
      }, // Casilla 43
      {
        x: 0.575,
        y: 0.616
      }, // Casilla 44
      {
        x: 0.575,
        y: 0.668
      }, // Casilla 45
      {
        x: 0.575,
        y: 0.724
      }, // Casilla 46
      {
        x: 0.575,
        y: 0.78
      }, // Casilla 47
      {
        x: 0.575,
        y: 0.836
      }, // Casilla 48
      {
        x: 0.575,
        y: 0.89
      }, // Casilla 49
      {
        x: 0.514,
        y: 0.89
      }, // Casilla 50
      {
        x: 0.4545,
        y: 0.89
      }, // Casilla 51
      {
        x: 0.510,
        y: 0.836
      }, // Casilla 52
      {
        x: 0.510,
        y: 0.78
      }, // Casilla 53
      {
        x: 0.510,
        y: 0.724
      }, // Casilla 54
    ];
const rectaFinal = {
  rojo: [
    { x: 0.510, y: 0.836 },
    { x: 0.510, y: 0.78 },
    { x: 0.510, y: 0.724 },
    { x: 0.510, y: 0.668 },
    { x: 0.510, y: 0.612 },
    { x: 0.510, y: 0.556 }
  ],
  verde: [
    { x: 0.163, y: 0.452 },
    { x: 0.22, y: 0.452 },
    { x: 0.279, y: 0.452 },
    { x: 0.338, y: 0.452 },
    { x: 0.398, y: 0.452 },
    { x: 0.4545, y: 0.452 }
  ],
  amarillo: [
    { x: 0.575, y: 0.182 },
    { x: 0.575, y: 0.235 },
    { x: 0.575, y: 0.289 },
    { x: 0.575, y: 0.346 },
    { x: 0.575, y: 0.398 },
    { x: 0.575, y: 0.452 }
  ],
  azul: [
    { x: 0.808, y: 0.564 },
    { x: 0.750, y: 0.564 },
    { x: 0.692, y: 0.564 },
    { x: 0.632, y: 0.564 },
    { x: 0.575, y: 0.564 },
    { x: 0.514, y: 0.564 }
  ]
};
    let posicionesRecorrido = {
      rojo: [null, null, null, null],
      azul: [null, null, null, null],
      verde: [null, null, null, null],
      amarillo: [null, null, null, null]
    };
      let fichasacada = {
      rojo: [false, false, false, false],
      azul: [false, false, false, false],
      verde: [false, false, false, false],
      amarillo: [false, false, false, false]
    };

    let posiciones = {
      verde: [{
        x: 0.20,
        y: 0.22
      }, {
        x: 0.30,
        y: 0.22
      }, {
        x: 0.20,
        y: 0.31
      }, {
        x: 0.30,
        y: 0.31
      }],
      azul: [{
        x: 0.73,
        y: 0.71
      }, {
        x: 0.83,
        y: 0.71
      }, {
        x: 0.73,
        y: 0.80
      }, {
        x: 0.83,
        y: 0.80
      }],
      rojo: [{
        x: 0.20,
        y: 0.71
      }, {
        x: 0.30,
        y: 0.71
      }, {
        x: 0.20,
        y: 0.80
      }, {
        x: 0.30,
        y: 0.80
      }],
      amarillo: [{
        x: 0.73,
        y: 0.22
      }, {
        x: 0.83,
        y: 0.22
      }, {
        x: 0.73,
        y: 0.31
      }, {
        x: 0.83,
        y: 0.31
      }]
    };
   const entradaCasa = {
   rojo: 53,
   verde: 11,
   amarillo: 24,
   azul: 37
   };
    // =====================
    // FUNCIONES DE CANVAS
    // =====================
    function ajustarCanvas() {
      canvas.width = tablero.clientWidth;
      canvas.height = tablero.clientHeight;
      console.log("Canvas ajustado:", canvas.width, "x", canvas.height);
    }

    function dibujarFichas() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      for (let i = 0; i < cantidadJugadores; i++) {
        const colorNombre = nombresColores[i];
        const colorHex = colores[i];

        posiciones[colorNombre].forEach((pos, j) => {
          const cx = pos.x * canvas.width;
          const cy = pos.y * canvas.height;

          // Dibujar ficha
          ctx.beginPath();
          ctx.arc(cx, cy, radioFicha, 0, Math.PI * 2);
          ctx.fillStyle = colorHex;
          ctx.fill();
          ctx.strokeStyle = '#000';
          ctx.lineWidth = 2;
          ctx.stroke();

          // Resaltar ficha seleccionada
          if (fichaSeleccionada &&
            fichaSeleccionada.jugador === i &&
            fichaSeleccionada.indice === j) {
            ctx.lineWidth = 5;
            ctx.strokeStyle = 'yellow';
            ctx.beginPath();
            ctx.arc(cx, cy, radioFicha + 5, 0, Math.PI * 2);
            ctx.stroke();
          }
        });
      }
    }

    // =====================
    // MANEJO DE EVENTOS
    // =====================
    function manejarClickCanvas(e) {
      console.log("🎯 Click detectado en canvas");

      const rect = canvas.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      console.log("Coordenadas del click:", x, y);

      let encontrada = null;

      // Buscar ficha clickeada
      for (let i = 0; i < cantidadJugadores; i++) {
        const fichas = posiciones[nombresColores[i]];
        for (let j = 0; j < fichas.length; j++) {
          const cx = fichas[j].x * canvas.width;
          const cy = fichas[j].y * canvas.height;
          const dx = x - cx;
          const dy = y - cy;
          const distancia = Math.sqrt(dx * dx + dy * dy);

          if (distancia <= radioFicha) {
            encontrada = {
              jugador: i,
              indice: j
            };
            console.log("🎯 Ficha encontrada:", encontrada);
            break;
          }
        }
        if (encontrada) break;
      }

      if (encontrada) {
        // Verificar si es el turno correcto
        if (encontrada.jugador !== turnoActual) {

          turnoTexto.innerText = `No es tu turno. Le toca a: ${nombresColores[turnoActual]}`;
          return;
        }

        fichaSeleccionada = encontrada;
        console.log("✅ Ficha seleccionada:", fichaSeleccionada);

        // Si estamos esperando un movimiento, mover la ficha
        if (esperandoMovimiento) {
          moverFichaSeleccionada();
        } else {
          turnoTexto.innerText = `Ficha seleccionada: ${nombresColores[fichaSeleccionada.jugador]}`;
        }

        dibujarFichas();
      } else {
        console.log("❌ No se encontró ninguna ficha");
        fichaSeleccionada = null;
        dibujarFichas();
      }
    }

    // =====================
    // LÓGICA DEL JUEGO
    // =====================
      function moverFichaSeleccionada() {
      if (!fichaSeleccionada) {
        turnoTexto.innerText = "Primero selecciona una ficha.";
        return;
      }

      const jugador = nombresColores[fichaSeleccionada.jugador];
      const idx = fichaSeleccionada.indice;
      let posicionActual = posicionesRecorrido[jugador][idx];

      // Si la ficha no está en el tablero
      if (posicionActual === null || posicionActual === undefined) {
        turnoTexto.innerText = "Esa ficha aún no está en juego.";
        return;
      }

      let nuevaPosicion = posicionActual + numeroDado;

      /*for (h = 0; h > 3; h++) {
         if (nuevaPosicion == posicionesRecorrido[jugador][h]) {
           nuevaPosicion--;
           turnoTexto.innerText = "No puedes poner una ficha de tu color sobre otra.";
           return;
         }

       }


       // Verificar si se pasa del final
       /*if (nuevaPosicion >= recorrido.length) {
         turnoTexto.innerText = "No puedes avanzar, necesitas el número exacto.";
         fichaSeleccionada = null;
         esperandoMovimiento = false;
         return;
       }*/

      // Mover la ficha
      posiciones[jugador][idx] = recorrido[nuevaPosicion];
      posicionesRecorrido[jugador][idx] = nuevaPosicion;

      turnoTexto.innerText = `${jugador} movió ${numeroDado} casillas`;

      fichaSeleccionada = null;
      esperandoMovimiento = false;
      dibujarFichas();

      // Pasar turno si no salió 6
      if (!salio6) {
        setTimeout(pasarTurno, 1000);
      } else {
        turnoTexto.innerText = `${jugador} puede tirar de nuevo (salió 6)`;

        salio6 = false;
      }
    }


    // =====================
    // FUNCIONES DEL DADO
    // =====================
    const carasDado = [
      'imagenes/dado1.png',
      'imagenes/dado2.png',
      'imagenes/dado3.png',
      'imagenes/dado4.png',
      'imagenes/dado5.png',
      'imagenes/dado6.png',
    ];

   function tirarDado() {
  if (dadoTirado) return;

  console.log("🎲 Tirando dado...");
  dadoTirado = true;
  esperandoMovimiento = false;
  dado.removeEventListener('click', tirarDado);

  const sonidoDado = document.getElementById("sonido-dado"); 

  let contador = 0;
  const animacion = setInterval(() => {
    const randomIndex = Math.floor(Math.random() * 6);
    dado.src = carasDado[randomIndex];

    // Se reproduce el pitido del dado cada vez que cambia de imagen
    sonidoDado.currentTime = 0;
    sonidoDado.play();

    contador++;

    if (contador >= 15) {
      clearInterval(animacion);

      numeroDado = randomIndex + 1;
      console.log("🎲 Resultado del dado:", numeroDado);
      procesarTirada(numeroDado);
      dadoTirado = false;
    }
  }, 160); 
}



    function procesarTirada(numero) {
      const colorJugador = nombresColores[turnoActual];

      if (numero === 6) {
        salio6 = true;
        turnoTexto.innerText = `🎉 ${colorJugador} sacó 6 — puedes mover una ficha o sacar una nueva.`;
        document.getElementById('opciones-jugador').style.display = 'block';

        return;
      }

      // Si no salió 6
      salio6 = false;
      document.getElementById('opciones-jugador').style.display = 'none';

      // Verificar si tiene fichas en el tablero
      const tieneEnTablero = tfichas[turnoActual] > -1;

      if (!tieneEnTablero) {
        turnoTexto.innerText = `${colorJugador} no tiene fichas en el tablero. Turno perdido.`;
        setTimeout(pasarTurno, 2000);

        return;
      }

      // Esperar que elija una ficha
      turnoTexto.innerText = `${colorJugador}, elige una ficha para mover ${numero} casillas.`;
      esperandoMovimiento = true;
    }

    function sacarFicha() {
      if (gfichas[turnoActual] <= -1) {
        turnoTexto.innerText = "No tienes fichas para sacar.";

        return;
      }

      const colorUsando = nombresColores[turnoActual];
      const entrada = entradaJugadores[colorUsando];
      const indiceFicha = gfichas[turnoActual] - 1;
      fichasacada[colorUsando][indiceFicha]=true;
      // Mover ficha del garage al tablero
      posiciones[colorUsando][indiceFicha] = recorrido[entrada];
      posicionesRecorrido[colorUsando][indiceFicha] = entrada;

      gfichas[turnoActual]--;
      tfichas[turnoActual]++;

      document.getElementById('opciones-jugador').style.display = 'none';
      turnoTexto.innerText = `${colorUsando} sacó una ficha. Puede tirar de nuevo.`;
      dado.addEventListener('click', tirarDado);

      dibujarFichas();
      salio6 = false; // Reset para el próximo turno
    }

    function moverFichaExistente() {
      document.getElementById('opciones-jugador').style.display = 'none';
      turnoTexto.innerText = `${nombresColores[turnoActual]}, elige una ficha para mover ${numeroDado} casillas.`;
      esperandoMovimiento = true;
      dado.addEventListener('click', tirarDado);
    }

    function mostrarTurno() {
      const jugador = nombresColores[turnoActual];
      dado.addEventListener('click', tirarDado);
      turnoTexto.innerText = `Le toca a: ${jugador}`;
      console.log("🎮 Turno actual:", jugador);
    }

    function pasarTurno() {
      turnoActual = (turnoActual + 1) % cantidadJugadores;
      esperandoMovimiento = false;
      fichaSeleccionada = null;
      document.getElementById('opciones-jugador').style.display = 'none';
      mostrarTurno();
      dibujarFichas();
    }

    // =====================
    // INICIALIZACIÓN
    // =====================
    function inicializar() {
      console.log("🚀 Inicializando juego...");
      console.log("👥 Cantidad de jugadores:", cantidadJugadores);

      ajustarCanvas();
      dibujarFichas();
      mostrarTurno();

      // Event listeners
      canvas.addEventListener('click', manejarClickCanvas);
      dado.addEventListener('click', tirarDado);
      window.addEventListener('resize', () => {
        ajustarCanvas();
        dibujarFichas();
      });

      console.log("✅ Juego inicializado correctamente");
    }

    // Inicializar cuando la imagen del tablero se cargue
    tablero.onload = inicializar;

    // Si la imagen ya está cargada
    if (tablero.complete) {
      inicializar();
    }
  </script>

</body>
<html>