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
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Reglas</a></li>
        <li><a href="#">Perfil</a></li>
        <li><a href="#">Salir</a></li>
      </ul>
    </nav>
  </header>

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
  </div>

  <script>
    // -----------------------
    // CANVAS DEL TABLERO
    // -----------------------


           // Busca el elemento HTML que tiene el id "canvas-ludo con la funcion getElementById"
           // Este canvas es donde se dibujan las fichas del juego
           // "canvas" ahora es una referencia a ese elemento, podemos usarlo en JS
         const canvas = document.getElementById('canvas-ludo');  

         // Obtenemos el "contexto 2D" del canvas
        // El contexto 2D es como un "lápiz virtual",y gracias a la funcion "getContext" nos da las herramientas para poder dibujar 
       // "ctx" será nuestra herramienta principal para dibujar las fichas y cualquier otra cosa en el tablero
        const ctx = canvas.getContext('2d');                    

      // Busca la imagen del tablero en la página usando su clase "imagen-tablero" con la funcion "querySelector"
      // Esto nos permite acceder a sus propiedades, como ancho y alto, para ajustar el canvas correctamente
        const tablero = document.querySelector('.imagen-tablero'); 

        // Ajusta el canvas para que tenga el mismo ancho y alto que la imagen del tablero
        function ajustarCanvas() 
        {
          canvas.width = tablero.clientWidth;   // ancho igual al de la imagen
          canvas.height = tablero.clientHeight; // alto igual al de la imagen
        }

      // Llama a la función para ajustar el canvas al cargar la página
      ajustarCanvas();

     // Escucha cuando se cambia el tamaño de la ventana y vuelve a ajustar el canvas
       window.addEventListener('resize', ajustarCanvas);


    // -----------------------
    // CANTIDAD DE JUGADORES
    // -----------------------

        // Función para obtener un valor de parámetro de la URL
        function obtenerParametro(nombre) 
        {
          // URLSearchParams toma la parte de la URL después del "?" 
          // y permite acceder a cada parámetro como un par clave-valor
          const parametros = new URLSearchParams(window.location.search);

          // Devuelve el valor del parámetro que coincida con el nombre dado
         // Por ejemplo: si la URL es "tablero.php?jugadores=3"
         // y llamamos obtenerParametro('jugadores'), nos devuelve "3"
            return parametros.get(nombre);
        }


        // Obtenemos la cantidad de jugadores desde la URL
        // 1. obtenerParametro('jugadores') -> busca el valor del parámetro "jugadores" en la URL
        // 2. parseInt(...) -> convierte el valor (que es un string) a número entero
        // 3. || 4 -> si no se encuentra ningún valor, se asigna 4 como valor por defecto
         let cantidadJugadores = parseInt(obtenerParametro('jugadores')) || 4; // cantidad de jugadores a usar en el juego

          // Array donde guardamos el color de cada jugador
          const colores = ['red','blue','green','yellow'];

          // Posiciones iniciales de las fichas
         const posiciones = 
         {
              // Mientras más aumentes la X, la ficha se mueve a la derecha
              // Mientras más aumentes la Y, la ficha se mueve hacia abajo
            verde: [{x:0.20,y:0.22},{x:0.30,y:0.22},{x:0.20,y:0.31},{x:0.30,y:0.31}],
            azul: [{x:0.73,y:0.80},{x:0.83,y:0.80},{x:0.73,y:0.71},{x:0.83,y:0.71}],
            rojo: [{x:0.20,y:0.80},{x:0.30,y:0.80},{x:0.20,y:0.71},{x:0.30,y:0.71}],
            amarillo: [{x:0.73,y:0.22},{x:0.83,y:0.22},{x:0.73,y:0.31},{x:0.83,y:0.31}]
        };

    // Dibujar fichas según la cantidad de jugadores seleccionados
     function dibujarFichas() 
     {
          // Limpia todo el canvas antes de dibujar las fichas nuevamente.
          // Esto evita que se queden dibujos viejos o se superpongan.
           ctx.clearRect(0,0,canvas.width,canvas.height); 

          // Array que indica el orden de los colores según los jugadores.
           const nombresColores = ['rojo','azul','verde','amarillo']; 

          // Recorre cada jugador según la cantidad seleccionada
          for(let i=0;i<cantidadJugadores;i++)
          {
               let colorNombre = nombresColores[i]; // Obtiene el nombre del color del jugador actual
               let colorHex = colores[i];           // Obtiene el color real que se usará para dibujar la ficha

               // Recorre todas las posiciones de las fichas de ese color
                  posiciones[colorNombre].forEach(pos=>
                  {
                    ctx.beginPath(); // Inicio un nuevo dibujo (Esto es para dibujar las fichas)

                    // Dibuja un círculo:
                    // pos.x * canvas.width → posición horizontal relativa al tamaño del canvas
                    // pos.y * canvas.height → posición vertical relativa al tamaño del canvas
                    // 17 → radio del círculo
                    // 0 a Math.PI*2 → ángulo completo para un círculo entero
                    ctx.arc(pos.x*canvas.width,pos.y*canvas.height,17,0,Math.PI*2); 

                    ctx.fillStyle = colorHex; // Define el color de relleno del círculo
                    ctx.fill();               // Rellena el círculo con el color seleccionado
                    ctx.strokeStyle='#000';   // Define el color del borde del círculo (negro)
                    ctx.stroke();             // Dibuja el borde del círculo
                    });
           }
      }

       dibujarFichas(); // Llamar al iniciar

    // -----------------------
    // DADO
    // -----------------------
    const dado = document.getElementById('dado');       // Imagen del dado
    let numeroDado = 0;                                // Guardar número del dado
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
  
         let contador = 0; // Contador para controlar cuántas veces cambia la cara del dado

        // setInterval crea una animación que se repite cada cierto tiempo (100ms)
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
      
                // Creo que a partir de aca podemos llamar a la funcion de los movimietnos de las piezas
                // y a la funcion de los turnos
             }

           }, 100); // Intervalo de 100ms entre cada cambio de imagen (0.1 segundos)
      }


    // Evento click sobre el dado
    dado.addEventListener('click',tirarDado);
  </script>
</body>
</html>
