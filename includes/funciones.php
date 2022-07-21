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

function estaAutenticado() : bool {
    session_start();

    $auth = $_SESSION['login'];

    if($auth) {
        return true;
    }

    return false;
    
}