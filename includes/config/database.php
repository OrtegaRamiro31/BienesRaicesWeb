<?php
//Retorna una conexión de mysqli
function conectarBD() : mysqli {
    $db = mysqli_connect('localhost', 'root', 'root', 'bienesraices_crud');

    if(!$db) {
        echo "No se pudo conectar";
        exit;
    } 

    return $db;

}