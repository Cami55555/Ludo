<?php
session_start();
$usuarios = 'usuarios.json';   //se guarda en una variable el nombre del json
if (file_exists($usuarios)) {    //revisa que exista ese archivo
    $cont = file_get_contents($usuarios);      //guarda los datos del json
    $users = json_decode($cont, true);         //decodifica los datos del json
}
// se guardan los datos ingresados en variables
$usuario = $_POST['usuario'];
$mail = $_POST['mail'];
$clave = $_POST['clave'];
$rep_clave = $_POST['clave2'];
// se comparan los datos ingresados con los del json y de coincidir los guarda en variables de sesión lleva al usuario de nuevo al index
foreach ($users as $k => $u) {
    if ($users[$k]['usuario'] === $usuario && $users[$k]['mail'] === $mail && $users[$k]['clave'] === $clave && $clave === $rep_clave) {
        $_SESSION['nombre'] = $users[$k]['nombre'];
        $_SESSION['apellido'] = $users[$k]['apellido'];
        $_SESSION['usuario'] = $usuario;
        $_SESSION['mail'] = $mail;
        $_SESSION['clave'] = $users[$k]['clave'];
        $_SESSION['edad'] = $users[$k]['edad'];
        $_SESSION['wins'] = $users[$k]['wins'];
        header('Location:index.php');
    } else {
        die("El usuario no se encontró. Verifique que los datos ingresados sean correctos en el <a href='login.html'>login</a> o si no está registrado <a href='registrarse.php'>registrese</a>");
    }
}