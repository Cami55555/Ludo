<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombres = $_POST['nombre'] ?? [];
  $colores = $_POST['color'] ?? [];
  $cantidadJugadores = $_POST['cantidadJugadores'] ?? 0;

  $_SESSION['jugadores'] = [];
  for ($i = 0; $i < $cantidadJugadores; $i++) {
    $_SESSION['jugadores'][] = [
      'nombre' => $nombres[$i],
      'color' => $colores[$i]
    ];
  }


  $ordenTablero = ['red', 'green', 'yellow', 'blue'];

  $_SESSION['jugadores'] = array_values(
    array_filter(
      array_map(
        fn ($color) => current(array_filter($_SESSION['jugadores'], fn ($j) => $j['color'] === $color)),
        $ordenTablero
      )
    )
  );
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
   ESTILO GENERAL
----------------------- */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
      overflow: hidden;
      position: relative;
      color: white;
      width: 100vw;
      height: 100vh;
    }

    /* ----------------------
   FONDO LIGERAMENTE BORROSO
----------------------- */
    .fondo {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(6px);
      background: rgba(30, 30, 50, 0.5);
      z-index: 0;
    }

    .fondo::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05), transparent 70%);
      z-index: 1;
    }

    /* ----------------------
   MENÚ PRINCIPAL
----------------------- */
    .menu-principal {
      position: relative;
      z-index: 20;
      background: rgba(25, 25, 40, 0.9);
      height: 8vh;
      display: flex;
      align-items: center;
      justify-content: space-between;
      /* logo a la izquierda, botones a la derecha */
      padding: 2vh 4vw;
      border-bottom: 0.3vh solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 0 1vh rgba(0, 0, 0, 0.7);
      border-radius: 1vh;
    }

    .menu-principal ul {
      list-style: none;
      display: flex;
      gap: 3vw;
      margin: 0;
      padding: 0;
    }

    .menu-principal li a {
      text-decoration: none;
      color: white;
      font-weight: bold;
      font-size: 2vh;
      transition: 0.3s;
      white-space: nowrap;
    }

    .menu-principal li a:hover {
      color: #00ffff;
    }

    .logo img {
      height: 4vh;
      width: auto;
    }

    /* ----------------------
   TITULOS
----------------------- */
    .titulos {
      position: relative;
      z-index: 5;
      text-align: center;
      margin-top: 10vh;
    }

    .bienvenidos {
      font-size: 4vh;
      color: white;
      text-shadow: 0 0 0.5vh white;
      margin-bottom: 1vh;
    }

    .titulo-ludo {
      font-size: 8vh;
      font-weight: 900;
      background: linear-gradient(90deg, red, blue, green, yellow, red, blue, green, yellow);
      background-size: 400% 100%;

      -webkit-text-fill-color: transparent;
      position: relative;
      text-transform: uppercase;
      letter-spacing: 0.5vw;
      animation: animar-gradiente 5s linear infinite;
    }

    .titulo-ludo::after {
      content: 'LUDO-PATIA';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      color: transparent;
      -webkit-text-stroke: 0.2vh white;
      pointer-events: none;
    }

    @keyframes animar-gradiente {
      0% {
        background-position: 0% 0%;
      }

      100% {
        background-position: 100% 0%;
      }
    }

    /* ----------------------
   FIGURAS ANIMADAS
----------------------- */
    .figuras {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 1;
    }

    .figura {
      position: absolute;
      top: -5vh;
      width: 4vw;
      height: 4vw;
      opacity: 0.9;
      animation: caer 10s linear infinite;
      border-radius: 0;
    }

    .rojo {
      background: #ff0000;
    }

    .azul {
      background: #0066ff;
      border-radius: 50%;
    }

    .verde {
      background: #00cc00;
      border-radius: 0%;
    }

    .amarillo {
      background: #ffff33;
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
    }

    @keyframes caer {
      0% {
        transform: translateY(-5vh) rotate(0deg);
        opacity: 0.9;
      }

      100% {
        transform: translateY(110vh) rotate(360deg);
        opacity: 0.3;
      }
    }

    /* ----------------------
   TABLERO Y CANVAS
----------------------- */
    .tablero-container {
      position: relative;
      width: 80vw;
      max-width: 90vh;
      height: 80vw;
      max-height: 90vh;
      margin: 5vh auto 0 auto;
      z-index: 5;
      transform-origin: top left;
    }

    .imagen-tablero {
      width: 100%;
      height: 100%;
      display: block;
    }

    #canvas-ludo {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10;
      cursor: pointer;
    }

    /* ----------------------
   DADO
----------------------- */
    .dado-container {
      position: absolute;
      top: 50%;
      right: 10%;
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 15;
    }

    .titulo-dado {
      color: #fff;
      margin-bottom: 1vh;
      font-weight: bold;
      font-size: 2vh;
    }

    .dado-container img {
      width: 100%;
      /* relativo al tablero */
      max-width: 80px;
      /* tamaño máximo */
      aspect-ratio: 1;
      object-fit: cover;
      cursor: pointer;
      transition: 0.2s;
    }

    .dado-container img:hover {
      transform: scale(1.1);
    }

    #turno {
      position: absolute;
      /* lo posicionamos sobre el tablero */
      top: 13vh;
      /* un poco debajo del menú (ajusta según tu menú) */
      left: 50%;
      /* centrado horizontal */
      transform: translateX(-50%);
      /* realmente lo centra */
      background: rgba(0, 0, 0, 0.6);
      /* fondo semitransparente */
      padding: 1vh 2vw;
      border-radius: 1vh;
      font-size: 4vh;
      font-weight: bold;
      text-align: center;
      z-index: 30;
      /* por encima de todo */
      max-width: 80vw;
      /* opcional, para que no sea demasiado ancho */
      color: #fff;
    }

    #turno {
      transition: opacity 0.5s ease;
      opacity: 1;
    }

    #turno.oculto {
      opacity: 0;
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
    <header>
      <nav class="menu-principal">
        <!-- Logo a la izquierda -->
        <div class="logo">
          <a href="index.php"><img src="imagenes/logo.png" alt="Home" /></a>
        </div>

        <!-- Botones del menú a la derecha -->
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="reglas.php">Reglas</a></li>
          <li><a href="perfil.php">Perfil</a></li>
          <li><a href="index.php">Salir</a></li>
        </ul>
      </nav>
    </header>

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

    const colores = ['red', 'green', 'yellow', 'azul'];


    const canvas = document.getElementById('canvas-ludo');
    const ctx = canvas.getContext('2d');
    const tablero = document.querySelector('.imagen-tablero');
    const dado = document.getElementById('dado');
    const turnoTexto = document.getElementById('turno');
    const jugadores = <?= json_encode($_SESSION['jugadores']) ?>;
    // Mapeo del color del formulario a la clave interna de posiciones
    const mapaColorPosicion = {
      red: 'rojo',
      green: 'verde',
      yellow: 'amarillo',
      blue: 'azul'
    };
    const nombresJugadores = jugadores.map(j => j.nombre);
    const nombresColores = jugadores.map(j => mapaColorPosicion[j.color]);
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
    const cantidadJugadores = <?= count($jugadores) ?>;
    const radioFicha = 17;

    const entradaJugadores = {
      rojo: 0,
      verde: 13,
      amarillo: 26,
      azul: 39
    };
    const casillasSeguras = [{
        x: 0.2218,
        y: 0.564
      }, // primera casilla segura (casilla 8)
      {
        x: 0.4545,
        y: 0.235
      }, // segunda casilla segura (casilla 21)
      {
        x: 0.808,
        y: 0.452
      }, // tercera casilla segura (casilla 34)
      {
        x: 0.575,
        y: 0.78
      } // cuarta casilla segura (casilla 47)
    ];


    // =====================
    // POSICIONES DEL GARAGE (para volver fichas al garage cuando son comidas)
    // =====================
    const garagePos = {
      verde: [{
          x: 0.20,
          y: 0.22
        },
        {
          x: 0.30,
          y: 0.22
        },
        {
          x: 0.20,
          y: 0.31
        },
        {
          x: 0.30,
          y: 0.31
        }
      ],
      azul: [{
          x: 0.73,
          y: 0.71
        },
        {
          x: 0.83,
          y: 0.71
        },
        {
          x: 0.73,
          y: 0.80
        },
        {
          x: 0.83,
          y: 0.80
        }
      ],
      rojo: [{
          x: 0.20,
          y: 0.71
        },
        {
          x: 0.30,
          y: 0.71
        },
        {
          x: 0.20,
          y: 0.80
        },
        {
          x: 0.30,
          y: 0.80
        }
      ],
      amarillo: [{
          x: 0.73,
          y: 0.22
        },
        {
          x: 0.83,
          y: 0.22
        },
        {
          x: 0.73,
          y: 0.31
        },
        {
          x: 0.83,
          y: 0.31
        }
      ]
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
    ];
    const rectaFinal = {
      rojo: [{
          x: 0.510,
          y: 0.836
        },
        {
          x: 0.510,
          y: 0.78
        },
        {
          x: 0.510,
          y: 0.724
        },
        {
          x: 0.510,
          y: 0.668
        },
        {
          x: 0.510,
          y: 0.612
        },
        {
          x: 0.510,
          y: 0.556
        }
      ],
      verde: [{
          x: 0.163,
          y: 0.452
        },
        {
          x: 0.22,
          y: 0.452
        },
        {
          x: 0.279,
          y: 0.452
        },
        {
          x: 0.338,
          y: 0.452
        },
        {
          x: 0.398,
          y: 0.452
        },
        {
          x: 0.4545,
          y: 0.452
        }
      ],
      amarillo: [{
          x: 0.575,
          y: 0.182
        },
        {
          x: 0.575,
          y: 0.235
        },
        {
          x: 0.575,
          y: 0.289
        },
        {
          x: 0.575,
          y: 0.346
        },
        {
          x: 0.575,
          y: 0.398
        },
        {
          x: 0.575,
          y: 0.452
        }
      ],
      azul: [{
          x: 0.808,
          y: 0.564
        },
        {
          x: 0.750,
          y: 0.564
        },
        {
          x: 0.692,
          y: 0.564
        },
        {
          x: 0.632,
          y: 0.564
        },
        {
          x: 0.575,
          y: 0.564
        },
        {
          x: 0.514,
          y: 0.564
        }
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

    function nombreConColor(jugadorIndex) {
      const jugador = jugadores[jugadorIndex];
      return `<span style="color:${jugador.color}; font-weight:bold;">${jugador.nombre}</span>`;
    }

    function dibujarFichas() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      for (let i = 0; i < jugadores.length; i++) {
        const jugador = jugadores[i];

        // Traducir color del jugador al color interno del tablero
        const colorTablero = mapaColorPosicion[jugador.color]; // 'rojo', 'verde', etc.

        // Iterar las fichas del jugador
        posiciones[colorTablero].forEach((pos, j) => {
          const cx = pos.x * canvas.width;
          const cy = pos.y * canvas.height;

          // Dibujar ficha
          ctx.beginPath();
          ctx.arc(cx, cy, radioFicha, 0, Math.PI * 2);
          ctx.fillStyle = jugador.color; // el color que eligió el jugador
          ctx.fill();
          ctx.strokeStyle = '#000';
          ctx.lineWidth = 2;
          ctx.stroke();

          // Resaltar ficha seleccionada
          if (
            fichaSeleccionada &&
            fichaSeleccionada.jugador === i &&
            fichaSeleccionada.indice === j
          ) {
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

      // Intentar mover ficha
      const turnoExtra = comerFichas(jugador, idx, numeroDado);

      fichaSeleccionada = null;
      esperandoMovimiento = false;

      if (turnoExtra) {
        salio6 = false; // comer ficha da turno extra
        turnoTexto.innerText += " Tira de nuevo.";
        dado.addEventListener('click', tirarDado);
      } else {
        // Pasar turno solo si no salió 6 ni comió ficha
        if (!salio6) setTimeout(pasarTurno, 1000);
        else {
          turnoTexto.innerText = `${jugador} puede tirar de nuevo (salió 6)`;
          salio6 = false;
        }
      }
    }




    // =====================
    // COMER FICHAS
    // =====================
    // =====================
    // COMER FICHAS CON CASILLAS SEGURAS
    // =====================
    function comerFichas(jugador, indiceFicha, pasos) {
      let posActual = posicionesRecorrido[jugador][indiceFicha];
      if (posActual === null) {
        turnoTexto.innerText = "Esta ficha todavía no está en juego.";
        return false; // no movió ficha
      }

      let nuevaPos = posActual + pasos;
      // Si se pasa del final, volver al inicio (bucle)
      if (nuevaPos >= recorrido.length) {
        nuevaPos = nuevaPos % recorrido.length;
      }

      let turnoExtra = false; // para saber si el jugador puede tirar de nuevo
      let conflict = true;

      while (conflict) {
        conflict = false;

        for (let i = 0; i < nombresColores.length; i++) {
          let colorOcupante = nombresColores[i];

          for (let j = 0; j < posicionesRecorrido[colorOcupante].length; j++) {
            if (posicionesRecorrido[colorOcupante][j] === nuevaPos) {

              // Verificar si es casilla segura
              const isCasillaSegura = casillasSeguras.some(casilla => {
                const posRecorrido = recorrido[nuevaPos];
                return Math.abs(posRecorrido.x - casilla.x) < 0.01 && Math.abs(posRecorrido.y - casilla.y) < 0.01;
              });

              if (isCasillaSegura) {
                // Retroceder 1 casilla si ficha intenta entrar en casilla segura
                nuevaPos--;
                conflict = true;
                break;
              }

              if (colorOcupante === jugador) {
                // Ficha propia → retroceder 1 casilla
                nuevaPos--;
                conflict = true;
                break;
              } else {
                // Verificar si ficha enemiga está en casilla segura
                const fichaOcupantePos = posicionesRecorrido[colorOcupante][j];
                const enCasillaSegura = casillasSeguras.some(casilla => {
                  const posRecorrido = recorrido[fichaOcupantePos];
                  return Math.abs(posRecorrido.x - casilla.x) < 0.01 && Math.abs(posRecorrido.y - casilla.y) < 0.01;
                });

                if (enCasillaSegura) {
                  // Retroceder 1 casilla si intenta comer ficha en casilla segura
                  nuevaPos--;
                  conflict = true;
                  break;
                } else {
                  // Comer ficha enemiga → volver al garage
                  posicionesRecorrido[colorOcupante][j] = null;
                  posiciones[colorOcupante][j] = garagePos[colorOcupante][j]; // vuelve al garage
                  gfichas[i]++;
                  tfichas[i]--;
                  turnoExtra = true;
                  turnoTexto.innerText = `${jugador} comió una ficha de ${colorOcupante}! Puede tirar de nuevo.`;
                  conflict = false;
                  break;
                }
              }
            }
          }
          if (conflict) break;
        }
      }

      // Mover ficha actual
      posicionesRecorrido[jugador][indiceFicha] = nuevaPos;
      posiciones[jugador][indiceFicha] = recorrido[nuevaPos];
      dibujarFichas();

      return turnoExtra; // true si comió ficha enemiga
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
      turnoTexto.innerText = `${jugadores[turnoActual].nombre}, elige una ficha para mover ${numeroDado} casillas.`;
      esperandoMovimiento = true;
    }

    function sacarFicha() {
      const colorUsando = nombresColores[turnoActual]; // color del jugador que saca
      const entrada = entradaJugadores[colorUsando]; // casilla de salida

      // Buscar primer índice de ficha disponible en el garage
      let indiceFicha = fichasacada[colorUsando].findIndex(f => !f);
      if (indiceFicha === -1) {
        turnoTexto.innerText = "No tienes fichas para sacar.";
        return;
      }

      // REVISAR SI LA CASILLA DE SALIDA ESTÁ OCUPADA
      let ocupadaPor = null;
      for (let i = 0; i < nombresColores.length; i++) {
        const jugadorColor = nombresColores[i];
        for (let j = 0; j < posicionesRecorrido[jugadorColor].length; j++) {
          if (posicionesRecorrido[jugadorColor][j] === entrada) {
            ocupadaPor = jugadorColor; // encontramos ficha en la casilla
          }
        }
      }

      // SI HAY FICHA DEL MISMO COLOR, NO PODEMOS SACAR
      if (ocupadaPor === colorUsando) {
        turnoTexto.innerText = "No puedes sacar, la casilla de salida está ocupada por tu ficha.";
        return;
      }

      // SI HAY FICHA DE OTRO COLOR, LA COMEMOS Y LA MANDAMOS AL GARAGE
      if (ocupadaPor !== null && ocupadaPor !== colorUsando) {
        for (let j = 0; j < posicionesRecorrido[ocupadaPor].length; j++) {
          if (posicionesRecorrido[ocupadaPor][j] === entrada) {
            posicionesRecorrido[ocupadaPor][j] = null; // volver al garage
            posiciones[ocupadaPor][j] = garagePos[ocupadaPor][j]; // posición original en garage
            gfichas[nombresColores.indexOf(ocupadaPor)]++;
            tfichas[nombresColores.indexOf(ocupadaPor)]--;
          }
        }
        turnoTexto.innerText = `${colorUsando} comió una ficha de ${ocupadaPor} y puede salir.`;
      }

      // MOVER FICHA DEL GARAGE AL TABLERO
      fichasacada[colorUsando][indiceFicha] = true;
      posiciones[colorUsando][indiceFicha] = recorrido[entrada];
      posicionesRecorrido[colorUsando][indiceFicha] = entrada;

      // ACTUALIZAR CONTADORES
      gfichas[turnoActual]--;
      tfichas[turnoActual]++;

      document.getElementById('opciones-jugador').style.display = 'none';
      turnoTexto.innerText += ` ${colorUsando} sacó una ficha. Puede tirar de nuevo.`;

      dibujarFichas();
      salio6 = false; // reset para el próximo turno
      dado.addEventListener('click', tirarDado);
    }




    function moverFichaExistente() {
      document.getElementById('opciones-jugador').style.display = 'none';
      turnoTexto.innerText = `${ jugadores(turnoActual)}, elige una ficha para mover ${numeroDado} casillas.`;
      esperandoMovimiento = true;
      dado.addEventListener('click', tirarDado);
    }

    function mostrarTurno() {
      const jugador = nombresColores[turnoActual];
      dado.addEventListener('click', tirarDado);
      turnoTexto.innerHTML = `Le toca a ${nombreConColor(turnoActual)}`;
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