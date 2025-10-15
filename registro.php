<?php
$usuarios = 'usuarios.json';   //se guarda en una variable el nombre del json
if (file_exists($usuarios)) {    //revisa que exista ese archivo
    $cont = file_get_contents($usuarios);      //guarda los datos del json
    $users = json_decode($cont, true);         //decodifica los datos del json
}
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$usuario = $_POST['usuario'];
$mail = $_POST['mail'];
$clave = $_POST['clave'];
$edad = $_POST['edad'];

$aux = 0;     //para contar cuántos datos coinciden con los ya ingresados de modo en que si todos coinciden no se agrega un nuevo usuario
$auxMail = 'true';
$auxU = 'true';
foreach ($users as $k => $u) {  // revisa todos los datos del json y los compara con los recién ingresados y si coinciden suma números a $aux y si coinciden ya sea mail o nombre de usuario entonces directamente hace que no le permita registrarse con esos datos
    foreach ($users[$k] as $dato => $d) {
        if ($dato != 'wins') {
            if ($users[$k][$dato] == $_POST[$dato]) {
                $aux++;
                if ($dato === 'mail') {
                    $auxMail = 'false';
                } else if ($dato === 'usuario') {
                    $auxU = 'false';
                }
            }
        }
    }
}
if ($aux < 6 && $auxMail === 'true' && $auxU === 'true') {  // si no hay datos repetidos, guarda la nueva cuenta en el json de usuarios
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
    header('Location:iniciosesion.html');
} else if ($aux == 6) {                          // con el mero hecho de que el mail o el nombre de usuario ya estén en uso debe volver a registrarse pero cambiando esos datos
    die("El usuario ya está registrado, debe <a href='iniciosesion.html'>logearse</a>");
} else if ($auxMail === 'false' && $auxU === 'true') {
    die("El mail ya está en uso. Vuelva al <a href='registrarse.html'>registro</a>");
} else if ($auxU === 'false' && $auxMail === 'true') {
    die("El nombre de usuario ya está en uso. Vuelva al <a href='registrarse.html'>registro</a>");
}