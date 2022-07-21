<?php 

// Importar la conexion
require 'includes/config/database.php';
$db = conectarBD();

// Crear un email y password
$email = "correo@correo.com";
$password = "123456";

// Hasheamos la contraseña con la función password_hash.
// El primer parámetro será la contraseña, el segundo el tipo de Hash.
// Podemos usar PASSWORD_DEFAULT o PASSWORD_BCRYPT
// Siempre vamos a tener la extensión de 60. Por eso en la BD debemos crear el campo con char(60)
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";


// Agregarlo a la base de datos
mysqli_query($db, $query);

