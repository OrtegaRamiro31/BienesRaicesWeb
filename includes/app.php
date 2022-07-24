<?php 

require 'funciones.php';
require 'config/database.php';
require __DIR__.'/../vendor/autoload.php';

// Conectarnos a la BD
$db = conectarBD();

use App\ActiveRecord;

ActiveRecord::setDB($db);
// var_dump($propiedad);
// echo "<br>";
// var_dump(__DIR__.'/../vendor/autoload.php');