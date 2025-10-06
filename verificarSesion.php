<?php
//verifica que la sesión esté iniciada
if (!isset($_SESSION['dni']) || !isset($_SESSION['clave'])) {
    die("Primero inicie sesión. <a href='login.php'>Login</a>");
}