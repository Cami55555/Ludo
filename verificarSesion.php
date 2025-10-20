<?php
//verifica que la sesión esté iniciada
if (!isset($_SESSION['clave']) || !isset($_SESSION['mail'])) {
    echo("Primero inicie sesión. <a href='iniciosesion.html'>Login</a> <br>");
    die("¿No tiene cuenta? <a href='registrarse.html'>Registrarse</a>");
}