<?php
//verifica que la sesión esté iniciada
if (!isset($_SESSION['dni']) || !isset($_SESSION['clave'])) {
    echo("Primero inicie sesión. <a href='iniciosesion.html'>Login</a> <br>");
    die("¿No tiene cuenta? <a href='registrarse.html'>Registrarse</a>");
}