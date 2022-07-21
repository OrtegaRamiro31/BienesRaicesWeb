<?php

// Con estas instrucciones hacemos búsquedas de las carpetas necesarias y las definimos.
// __DIR__ es una super global, nos traerá una ruta muy específica hacia la carpeta que queremos usar.
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
function incluirTemplate(string $nombre, bool $inicio = false)
{

    //echo TEMPLATES_URL . "/${nombre}.php" //Con esta instruccion puede verse la ruta generada con __DIR__
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado() {
    session_start();

    if(!$_SESSION['login']) {
        header('Location: /');
    }
}

function debuguear($variable) {
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}