<?php
//Retorna una conexión de mysqli
function conectarBD() : mysqli {
    $db = new mysqli('localhost', 'root', 'root', 'bienesraices_crud');

    if(!$db) {
        echo "No se pudo conectar";
        exit;
    } 

    return $db;

}