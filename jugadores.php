
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" /> <!-- Codificación estándar -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Adaptable a pantallas -->
  <link rel="stylesheet" href="jugadores.css" /> <!-- Enlace al estilo -->
  <title>Elegir Jugadores</title>
</head>
<body>
  <!-- CONTENEDOR PRINCIPAL DEL FORMULARIO -->
  <div class="contenedor-formulario">
    <p class="titulo">Elegir cantidad de jugadores</p>

    <!-- OPCIONES DE CANTIDAD -->
    <div class="grupo-opciones">
      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="2" required />
        2 jugadores
      </label>

      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="3" />
        3 jugadores
      </label>

      <label class="boton-opcion">
        <input type="radio" name="jugadores" value="4" />
        4 jugadores
      </label>
    </div>

    <br />

    <!-- BOTÓN PARA EMPEZAR EL JUEGO -->
    <button type="button" class="boton-jugar" onclick="iniciarJuego()">Jugar</button>
  </div>

  <script>
    // ----------------------------
    // Función: redirige al tablero
    // ----------------------------
    function iniciarJuego() {
      const seleccion = document.querySelector('input[name="jugadores"]:checked'); // obtiene selección
      //
      if (!seleccion) { // si no se seleccionó nada
        alert("Por favor elegí la cantidad de jugadores");
        return;
      }
      const jugadores = seleccion.value; // guarda el valor elegido
      window.location.href = `tablero.php?jugadores=${jugadores}`; // redirige con el dato
      //window representa la ventana actual del navegador
      //location es un objeto dentro del window que guarda la url de la pagina
      //href es la propiedad que contiene la url completa
    }

    // ----------------------------
    // Efecto visual que cambia el color
    // ----------------------------

// Selecciona todos los elementos HTML que tengan la clase 'boton-opcion'.
// bascimanete busca todos los rectángulos (labels) que representan las opciones
// de cantidad de jugadores (2, 3 o 4 personas).
const opciones = document.querySelectorAll('.boton-opcion');

// Este forEach recorre cada una de las opciones encontradas.
opciones.forEach(opcion => {

  // A cada opción le agrega un "escuchador" de eventos con la funcion "addEventListener".
  // Este escuchador espera un click del usuario.
  opcion.addEventListener('click', () => {

    // Primero recorre todas las opciones y les quita la clase 'seleccionado'.
    // Esto sirve para que solo una opción pueda estar resaltada a la vez.
    // (Si antes habías elegido otra, se desmarca automáticamente.)
    opciones.forEach(o => o.classList.remove('seleccionado'));

    // Luego, a la opción que fue clickeada, le agrega la clase 'seleccionado'.
    // En el CSS, esta clase cambia el color y el estilo del botón,
    // haciendo que se vea cuál está actualmente seleccionada.
    opcion.classList.add('seleccionado');
  });
});

  </script>
</body>
</html>
