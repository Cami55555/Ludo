<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" /> <!-- Codificación -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Adaptable -->
  <link rel="stylesheet" href="tablero.css" /> <!-- Enlace al CSS -->
  <title>Tablero de Ludo</title>
  
</head>
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
  <button onclick="pasarTurno()">Pasar Turno</button>
<div id="turno" style="font-size: 20px; font-weight: bold; margin: 10px;"></div>

  <!-- CONTENEDOR DEL TABLERO Y DADO -->
  <div class="contenedor-tablero">
    <!-- TABLERO -->
    <div class="tablero-container">
      <img src="imagenes/tablero.png" alt="Tablero de Ludo" class="imagen-tablero" /> <!-- Imagen del tablero -->
      <canvas id="canvas-ludo"></canvas> <!-- Canvas para dibujar fichas -->
    </div>

    <!-- DADO -->
    <div class="dado-container">
      <p class="titulo-dado">Tirar Dado</p>
      <img id="dado" src="imagenes/dado1.png" alt="Dado" /> <!-- Tamaño fijo -->
    </div>
      <!-- opciones cuando saca 6 -->
    <div id="opciones-jugador" style="display: none; margin-top: 20px;">
  <p>Elegí una opción:</p>
  <button onclick="sacarFicha()">Sacar ficha</button>
  <button onclick="moverFichaExistente()">Mover ficha existente</button>
</div>
  </div>

<script>
  const colores = ['red', 'blue', 'green', 'yellow'];
  const nombresColores = ['rojo', 'azul', 'verde', 'amarillo'];

  const canvas = document.getElementById('canvas-ludo');
  const ctx = canvas.getContext('2d');
  const tablero = document.querySelector('.imagen-tablero');
  const dado = document.getElementById('dado');
  const turnoTexto = document.getElementById('turno');

  let salio6 = false;
  let dadoTirado = false;
  let numeroDado = 0;
  let turnoActual = 0;

  let gfichas = [3, 3, 3, 3];
  let tfichas = [-1, -1, -1, -1];
  let win = false;

const entradaJugadores = {
  rojo: 0,
  verde: 13,
  amarillo: 26,
  azul: 39
};

  let fichaSeleccionada = null;
  const radioFicha = 17;
  let posiciones = 
         {
              // Mientras más aumentes la Y, la ficha se mueve hacia abajo
            verde: [{x:0.20,y:0.22},{x:0.30,y:0.22},{x:0.20,y:0.31},{x:0.30,y:0.31}],
            azul: [{x:0.73,y:0.71},{x:0.83,y:0.71},{x:0.73,y:0.80},{x:0.83,y:0.80}],
            rojo: [{x:0.20,y:0.71},{x:0.30,y:0.71},{x:0.20,y:0.80},{x:0.30,y:0.80}],
            amarillo: [{x:0.73 , y: 0.22},{x:0.83,y:0.22},{x:0.73,y:0.31},{x:0.83,y:0.31}]
        };
         //
 const recorrido = [
  { x: 0.4545, y: 0.836 }, // Casilla 0
  { x: 0.4545, y: 0.78 },  // Casilla 1
  { x: 0.4545, y: 0.724 }, // Casilla 2
  { x: 0.4545, y: 0.668 }, // Casilla 3
  { x:  0.4545, y: 0.616  }, // Casilla 4
  // izquierda
  { x: 0.398, y: 0.564  },  // Casilla 5
  { x: 0.337, y: 0.564  },  // Casilla 6
  { x: 0.2795, y: 0.564 },  // Casilla 7+
  { x: 0.2218, y: 0.564},  // Casilla 8+
  { x: 0.163, y: 0.564 },  // Casilla 9
    // subiendo
  { x: 0.103, y: 0.564 },  // Casilla 10
  {  x: 0.103, y: 0.509},    // Casilla 11
  { x: 0.103, y: 0.452},    // Casilla 12
  //derecha
  { x:0.163,y:0.452 },    // Casilla 13 (inicio VERDE)+++
  { x: 0.220, y: 0.452 },    // Casilla 14
  { x: 0.28, y: 0.452 },    // Casilla 15++++++
  { x: 0.338, y: 0.452 },    // Casilla 16
  { x: 0.398, y: 0.452 },    // Casilla 17+++

  { x: 0.4545, y: 0.398 }, // Casilla 18
  { x: 0.4545, y: 0.346 }, // Casilla 19
  { x: 0.4545, y: 0.289}, // Casilla 20++++
  {x: 0.4545, y: 0.235}, // Casilla 21
  { x: 0.4545, y: 0.18 },  // Casilla 22

  {  x: 0.4545, y: 0.128  },  // Casilla 23
  { x: 0.514, y: 0.128 },   // Casilla 24
  { x: 0.575, y: 0.128},   // Casilla 25

  {   x: 0.575, y: 0.182  },  // Casilla 26 (inicio AMARILLO)
  { x: 0.575, y: 0.235 },  // Casilla 27
  { x: 0.575, y: 0.289 },  // Casilla 28
  { x: 0.575, y: 0.346 },  // Casilla 29
  { x: 0.575, y: 0.398 },    // Casilla 30++++++++++

  {x: 0.632, y: 0.452 },    // Casilla 31+++++++
  {x: 0.692, y: 0.452 },    // Casilla 32
  { x:0.750, y: 0.452 },    // Casilla 33
  {x: 0.808, y: 0.452 },    // Casilla 34++++++++
  {x: 0.865, y: 0.452 },    // Casilla 35++++++++

  { x: 0.926, y: 0.452 },    // Casilla36
  {  x: 0.926, y: 0.509},    // Casilla 17
  { x: 0.926, y: 0.564 },  // Casilla 38

  { x:0.865,y:0.564 },  // Casilla 39 (inicio AZUL)
  { x: 0.808, y:0.564 },  
  { x: 0.750, y: 0.564},  // Casilla 40
  { x: 0.692, y: 0.564 },  // Casilla 41
  { x: 0.632, y: 0.564 },  // Casilla 42


  { x: 0.575, y: 0.616 },  // Casilla 43
  { x: 0.575, y: 0.668 },  // Casilla 44
  { x: 0.575, y: 0.724 },  // Casilla 45
  { x: 0.575, y: 0.78 },   // Casilla 46
  { x: 0.575, y: 0.836 },  // Casilla 47

  { x: 0.575, y: 0.89 },  // Casilla 48 0.510
  { x: 0.514, y: 0.89 },  // Casilla 49
   { x: 0.4545, y: 0.89 },  // Casilla 50

  { x: 0.510, y: 0.836 },  // Casilla 51
  { x: 0.510, y: 0.78 },   // Casilla 52
  { x: 0.510, y: 0.724 },  // Casilla 53
];////////////////////////recorrido
let posicionesRecorrido = {
  rojo: [null, null, null, null],
  azul: [null, null, null, null],
  verde: [null, null, null, null],
  amarillo: [null, null, null, null]
};
  // --- AJUSTE DEL CANVAS ---
  function ajustarCanvas() {
    canvas.width = tablero.clientWidth;
    canvas.height = tablero.clientHeight;
  }

  window.onload = () => {
  ajustarCanvas();
  dibujarFichas();
};

window.addEventListener('resize', () => {
  ajustarCanvas();
  dibujarFichas();
});

  // --- DIBUJAR FICHAS ---
  function dibujarFichas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < colores.length; i++) {
      const colorNombre = nombresColores[i];
      const colorHex = colores[i];
      posiciones[colorNombre].forEach((pos, j) => {
        const cx = pos.x * canvas.width;
        const cy = pos.y * canvas.height;
        ctx.beginPath();
        ctx.arc(cx, cy, radioFicha, 0, Math.PI * 2);
        ctx.fillStyle = colorHex;
        ctx.fill();
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.stroke();

        // 🔥 Resaltar si está seleccionada
        if (
          fichaSeleccionada &&
          fichaSeleccionada.jugador === i &&
          fichaSeleccionada.indice === j
        ) {
          ctx.lineWidth = 4;
          ctx.strokeStyle = 'white';
          ctx.stroke();
        }
      });
    }
  }

  // --- DETECTAR CLIC EN UNA FICHA ---
  canvas.addEventListener('click', (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    let encontrada = null;
    for (let i = 0; i < colores.length; i++) {
      const fichas = posiciones[nombresColores[i]];
      for (let j = 0; j < fichas.length; j++) {
        const cx = fichas[j].x * canvas.width;
        const cy = fichas[j].y * canvas.height;
        const dx = x - cx;
        const dy = y - cy;
        const distancia = Math.sqrt(dx * dx + dy * dy);
        if (distancia <= radioFicha) {
          encontrada = { jugador: i, indice: j };
          break;
        }
      }
      if (encontrada) break;
    }

    if (encontrada) {
      fichaSeleccionada = encontrada;
      dibujarFichas();
      turnoTexto.innerText =
        "Ficha seleccionada: " + nombresColores[fichaSeleccionada.jugador];
      console.log("Ficha seleccionada:", fichaSeleccionada);
    }
  });

  // --- MOVER LA FICHA SELECCIONADA (EJEMPLO BÁSICO) ---
  function moverFichaSeleccionada() {
  if (!fichaSeleccionada) {
    turnoTexto.innerText = "Primero selecciona una ficha.";
    return;
  }

  const jugador = nombresColores[fichaSeleccionada.jugador]; // rojo, azul, etc.
  const idx = fichaSeleccionada.indice;

  let posicionActual = posicionesRecorrido[jugador][idx];

  // Si la ficha no está en el tablero, no la movemos
  if (posicionActual === null || posicionActual === undefined) {
    turnoTexto.innerText = "Esa ficha aún no está en juego.";
    return;
  }

  let nuevaPosicion = posicionActual + numeroDado;

  // Si se pasa del final del recorrido, se queda quieta
  if (nuevaPosicion >= recorrido.length) {
    turnoTexto.innerText = "No puedes avanzar, necesitas el número exacto.";
    fichaSeleccionada = null;
    return;
  }
  // Actualizamos la posición en el array de posiciones
  posiciones[jugador][idx] = recorrido[nuevaPosicion];
  // Actualizamos la posición lógica
  posicionesRecorrido[jugador][idx] = nuevaPosicion;
  fichaSeleccionada = null;
  dibujarFichas();
}


  // --- FUNCIONES DE TURNO Y DADO ---
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
    dadoTirado = true;
    dado.removeEventListener('click', tirarDado);
    let contador = 0;

    const animacion = setInterval(() => {
      const randomIndex = Math.floor(Math.random() * 6);
      dado.src = carasDado[randomIndex];
      contador++;

      if (contador >= 10) {
        clearInterval(animacion);
        numeroDado = randomIndex + 1;
        console.log("Número del dado:", numeroDado);
        movimientopieza(numeroDado);
        dado.addEventListener('click', tirarDado);
        dadoTirado = false;
      }
    }, 100);
  }
let accion=false;
  function movimientopieza(numeroDado) {
    if (numeroDado === 6) {
      salio6 = true;
      turnoTexto.innerText = "🎉 Sacaste 6 " + nombresColores[turnoActual] + ", puedes mover o sacar ficha";
      document.getElementById('opciones-jugador').style.display = 'block';
    } else {
      salio6 = false;
       document.getElementById('opciones-jugador').style.display = 'none';
      moverFichaSeleccionada(); // mover la ficha seleccionada
      pasarTurno();
    }
  }
   
  
  function sacarFicha() {
    accion=true;
    if (gfichas[turnoActual] >= 0) {
      
      let t=gfichas[turnoActual];
      let colorusando= nombresColores[turnoActual];
      let xd=entradaJugadores[colorusando];
      posiciones[colorusando][t]=recorrido[xd];
      posicionesRecorrido[colorusando][t] = xd
      document.getElementById('opciones-jugador').style.display = 'none';
      gfichas[turnoActual]--;
      tfichas[turnoActual]++;
      turnoTexto.innerText = "Sacaste una ficha al tablero" ;
      turnoTexto.innerText = "Repite turno";

    } else {
      turnoTexto.innerText = "No tienes fichas para sacar.";
    }
    dibujarFichas();
  }

  function moverFichaExistente() {
    accion=true;
    moverFichaSeleccionada();
    
  }

  function mostrarTurno() {
    const jugador = nombresColores[turnoActual];
    turnoTexto.innerText = "Le toca a: " + jugador;
  }

  function pasarTurno() {
    turnoActual = (turnoActual + 1) % colores.length;
    accion=false;
    mostrarTurno();
  }

  mostrarTurno();
  dado.addEventListener('click', tirarDado);
</script>

</body>
</html>

