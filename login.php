<?php
session_start();

$usuarios = 'usuarios.json'; // Se guarda en una variable el nombre del json

if (file_exists($usuarios)) { // Revisa que exista ese archivo
    $cont = file_get_contents($usuarios); // Guarda los datos del json
    $users = json_decode($cont, true); // Decodifica los datos del json
}

// Se guardan los datos ingresados en variables
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

$aux = 0;

// se comparan los datos ingresados con los del json y de coincidir los guarda en variables de sesión lleva al usuario de nuevo al index
foreach ($users as $k => $u) {

    // Verifica que coincidan mail, clave y repetición de clave
    if (($users[$k]['mail'] === $usuario || $users[$k]['usuario'] === $usuario) && $users[$k]['clave'] === $clave) {
        

        $_SESSION['nombre'] = $users[$k]['nombre'];
        $_SESSION['apellido'] = $users[$k]['apellido'];
        $_SESSION['usuario'] = $users[$k]['usuario']; // 
        $_SESSION['mail'] = $users[$k]['mail'];
        $_SESSION['clave'] = $users[$k]['clave'];
        $_SESSION['edad'] = $users[$k]['edad'];
        $_SESSION['wins'] = $users[$k]['wins'];

        header('Location:index.php');
        exit;
    } else {
        $aux++;
    }
}

// Si no encontró coincidencias, muestra error
if ($aux == count($users)) {
    die("El usuario no se encontró. Verifique que los datos ingresados sean correctos en el 
    <a href='iniciarsesion.html'>login</a> o si no está registrado 
    <a href='registrarse.html'>registrese</a>");
}
?>
