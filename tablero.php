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
      const colores = ['red','blue','green','yellow'];
      const nombresColores = ['rojo','azul','verde','amarillo'];

      const canvas = document.getElementById('canvas-ludo'); 
      const ctx = canvas.getContext('2d');                    
      const tablero = document.querySelector('.imagen-tablero'); 
      const dado = document.getElementById('dado');
      const turnoTexto = document.getElementById('turno');
      const sacarficha = document.getElementById('sacarficha');

      let salio6=false;
      let dadoTirado = false;
      let win=false;
      let gfichas = [4,4,4,4]; //cantidad de fichas guardadas*jugador
      let tfichas = [0,0,0,0];//cantidad de fichas en el tablero*jugador
      let numeroDado = 0;  
      let turnoActual = 0;

      let posicionesFichasJugadores = [
      [-1, -1, -1, -1], // rojo
      [-1, -1, -1, -1], // azul
      [-1, -1, -1, -1], // verde
      [-1, -1, -1, -1], // amarillo
      ];

        // Ajusta el canvas para que tenga el mismo ancho y alto que la imagen del tablero
        function ajustarCanvas() 
        {
          canvas.width = tablero.clientWidth;   // ancho igual al de la imagen
          canvas.height = tablero.clientHeight; // alto igual al de la imagen
        }

     tablero.onload = () => { //espera q   eu e tableto carge antes de llamar a las funciones
     ajustarCanvas();
     dibujarFichas();
       };

     // Escucha cuando se cambia el tamaño de la ventana y vuelve a ajustar el canvas
       window.addEventListener('resize', ajustarCanvas);
  
        function obtenerParametro(nombre) 
        {
          const parametros = new URLSearchParams(window.location.search);
            return parametros.get(nombre);
        }
         let cantidadJugadores = parseInt(obtenerParametro('jugadores')) || 4; 
          // Array donde guardamos el color de cada jugador
          // Posiciones iniciales de las fichas
         const posiciones = 
         {
              // Mientras más aumentes la Y, la ficha se mueve hacia abajo
            verde: [{x:0.20,y:0.22},{x:0.30,y:0.22},{x:0.20,y:0.31},{x:0.30,y:0.31}],
            azul: [{x:0.73,y:0.71},{x:0.83,y:0.71},{x:0.73,y:0.80},{x:0.83,y:0.80}],
            rojo: [{x:0.20,y:0.71},{x:0.30,y:0.71},{x:0.20,y:0.80},{x:0.30,y:0.80}],
            amarillo: [{x:0.73,y:0.22},{x:0.83,y:0.22},{x:0.73,y:0.31},{x:0.83,y:0.31}]
        };
         

    // Dibujar fichas según la cantidad de jugadores seleccionados
     function dibujarFichas() 
     {
          
           ctx.clearRect(0,0,canvas.width,canvas.height); 
            
          // Recorre cada jugador según la cantidad seleccionada
          for(let i=0;i<cantidadJugadores;i++)
          {
               let colorNombre = nombresColores[i]; // Obtiene el nombre del color del jugador actual
               let colorHex = colores[i];           // Obtiene el color real que se usará para dibujar la ficha

               // Recorre todas las posiciones de las fichas de ese color
                  posiciones[colorNombre].forEach(pos=>
                  {
                    ctx.beginPath(); // Inicio un nuevo dibujo (Esto es para dibujar las fichas)
                    ctx.arc(pos.x*canvas.width,pos.y*canvas.height,17,0,Math.PI*2); 

                    ctx.fillStyle = colorHex; // Define el color de relleno del círculo
                    ctx.fill();               // Rellena el círculo con el color seleccionado
                    ctx.strokeStyle='#000';   // Define el color del borde del círculo (negro)
                    ctx.stroke();             // Dibuja el borde del círculo
                    });
           }
      }

                              
    const carasDado = [
      'imagenes/dado1.png',
      'imagenes/dado2.png',
      'imagenes/dado3.png',
      'imagenes/dado4.png',
      'imagenes/dado5.png',
      'imagenes/dado6.png'
    ];

   // Función del dado
     function tirarDado() 
     {
         if (dadoTirado) return;//evita que repita turno
         dadoTirado = true;
         dado.removeEventListener('click', tirarDado);
         let contador = 0; // Contador para controlar cuántas veces cambia la cara del dado
           const animacion = setInterval(() => 
           {

             // Elegir un índice aleatorio entre 0 y 5 (representa las 6 caras del dado)
              const randomIndex = Math.floor(Math.random() * 6); 

             // Cambiar la imagen del dado a la cara seleccionada aleatoriamente
              dado.src = carasDado[randomIndex]; 

              contador++; // Aumentar el contador en 1

             // if que verificar si ya se cambiaron suficientes veces las caras
             if (contador >= 10) 
             { 
                 clearInterval(animacion); // Detener la animación de cambiar imágenes
                 numeroDado = randomIndex + 1; // Guardar el número real del dado (1 a 6)
                 console.log("Número del dado:", numeroDado); // Mostrar en consola (para debug)
    
               movimientopieza(numeroDado);
              dado.addEventListener('click', tirarDado);
               if (!salio6) {//evita que repita turno
               pasarTurno();
               dadoTirado = false;
              }
             }
           }, 100); // Intervalo de 100ms entre cada cambio de imagen (0.1 segundos)
          
      }

    function movimientopieza(numeroDado) {
     if (numeroDado === 6) {
    salio6 = true;
    turnoTexto.innerText = "Felicidades, ahora repites el turno";
    document.getElementById('opciones-jugador').style.display = 'block';
    console.log("Salió 6, mostrar opciones al jugador");
    dadoTirado = false;
    } else {
    salio6 = false;
    pasarTurno();
    dadoTirado = false;
    }
   }//lol
     function sacarFicha()
     {
         if(gfichas[turnoActual]>=1)
         {
           gfichas[turnoActual]--;
           tfichas[turnoActual]++;

           
         } 
         else
         {
          nosepuede();
           
         }
     }
     function moverfichaexistente(){

     }
       
    function mostrarTurno() {
    const jugador = nombresColores[turnoActual];
    turnoTexto.innerText = "Le toca a: " + jugador;
    console.log("🎲 Turno del jugador: " + jugador);
  }
  function nosepuede()
  {
    turnoTexto.innerText = "no se puede realizar esa accion " ;
  } 
   function pasarTurno() {
    turnoActual = (turnoActual + 1) % cantidadJugadores;
    mostrarTurno();
  }
     mostrarTurno();

   dado.addEventListener('click',tirarDado);

  


  </script>
</body>
</html>
