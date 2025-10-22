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

  // --- NUEVAS VARIABLES ---
  let fichaSeleccionada = null;
  const radioFicha = 17;
     let posiciones = 
         {
              // Mientras más aumentes la Y, la ficha se mueve hacia abajo
            verde: [{x:0.20,y:0.22},{x:0.30,y:0.22},{x:0.20,y:0.31},{x:0.30,y:0.31}],
            azul: [{x:0.73,y:0.71},{x:0.83,y:0.71},{x:0.73,y:0.80},{x:0.83,y:0.80}],
            rojo: [{x:0.20,y:0.71},{x:0.30,y:0.71},{x:0.20,y:0.80},{x:0.30,y:0.80}],
            amarillo: [{x:0.73,y:0.22},{x:0.83,y:0.22},{x:0.73,y:0.31},{x:0.83,y:0.31}]
        };
         const seguimiento = 
         {
              // Mientras más aumentes la Y, la ficha se mueve hacia abajo
            verde: {x:0.163,y:0.455},
            azul: {x:0.865,y:0.564},
            rojo: {x:0.4545,y:0.836},
            amarillo: {x:0.574,y:0.182}
        }; //y==0,55 x==0,060

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

    const jugador = nombresColores[fichaSeleccionada.jugador];
    const idx = fichaSeleccionada.indice;

    // 🔹 Movimiento de ejemplo: avanzar en X según el dado
    if(posiciones[jugador][y])
    {
       
    }
    posiciones[jugador][idx].x += numeroDado * 0.02;

    dibujarFichas();
    fichaSeleccionada = null; // deseleccionamos
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
      posiciones[colorusando][t]=seguimiento[colorusando];
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

