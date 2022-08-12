<?php

// Con estas instrucciones hacemos búsquedas de las carpetas necesarias y las definimos.
// __DIR__ es una super global, nos traerá una ruta muy específica hacia la carpeta que queremos usar.
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

function incluirTemplate(string $nombre, bool $inicio = false)
{

    //echo TEMPLATES_URL . "/${nombre}.php" //Con esta instruccion puede verse la ruta generada con __DIR__
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado()
{
    session_start();

    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

function debuguear($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}

// Escapa el HTML. SANITIZAR
function sanitizar($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// Validar tipo de Contenido
function validarTipoContenido($tipo)
{
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos);
}

// Muestra los mensajes
function mostrarNotificacion($codigo)
{
    $mensaje = '';

    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}
