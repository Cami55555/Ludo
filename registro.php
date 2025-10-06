<?php
$usuarios = 'usuarios.json';   //se guarda en una variable el nombre del json
if (file_exists($usuarios)) {    //revisa que exista ese archivo
    $cont = file_get_contents($usuarios);      //guarda los datos del json
    $users = json_decode($cont, true);         //decodifica los datos del json
}
$aux = 0;     //para contar cuántos datos coinciden con los ya ingresados de modo en que si todos coinciden no se agrega un nuevo usuario
$auxMail = false;
$auxU = false;
foreach ($users as $k => $u) {
    if ($u[$k] == $_POST[$k]) {
        if ($k === 'mail') {
            $aux = 6;
            $auxMail = false;
        } else if ($k === 'usuario') {
            $aux++;
            $auxU = false;
        } else {
            $aux++;
            $auxMail = true;
            $auxU = true;
        }
    }
}
if ($aux < 6 && $auxMail === true && $auxU === true) {
    $nuevoDato = [
        "nombre" => $_POST['nombre'],
        "apellido" => $_POST['apellido'],
        "usuario" => $_POST['usuario'],
        "mail" => $_POST['mail'],
        "clave" => $_POST['clave'],
        "edad" => $_POST['edad'],
        "wins" => 0
    ];
    $users[] = $nuevoDato;
    file_put_contents($usuarios, json_encode($users, JSON_PRETTY_PRINT));       //se guardan los datos del nuevo usuario en el archivo usuarios.json
} else if ($aux == 6) {
    die("El usuario ya está registrado, debe <a href='login.php'>logearse</a>");
} else if ($auxMail === false) {
    die("El mail ya está en uso. <a href='registrarse.php'>Registro</a>");
} else if ($auxU === false) {
    die("El nombre de usuario ya está en uso. <a href='registrarse.php'>Registro</a>");
}
// $_SESSION['nombre'];
// $_SESSION['apellido'];
// $_SESSION['usuario'];
// $_SESSION['mail'];
// $_SESSION['clave'];
