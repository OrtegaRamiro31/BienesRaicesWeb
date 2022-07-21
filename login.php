<?php 

    require 'includes/config/database.php';
    $db = conectarBD();

    $errores = [];

    // Autenticar el usuario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        // Sanitizamos con mysqli_real_escape_string. Filtramos el email con el Filter correspondiente.
        $email = mysqli_real_escape_string($db, filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) );
        $password = mysqli_real_escape_string($db, $_POST['password']);
        
        if(!$email) {
            $errores[] = "El email es obligatorio o no es válido";
        }

        if(!$password) {
            $errores[] = "El password es obligatorio ";
        }

        if(empty($errores)) {

            // Revisar si el usuario existe
            $query = "SELECT * FROM usuarios WHERE email='${email}'";
            $resultado = mysqli_query($db, $query);                     // Extraemos información de la consulta

            // var_dump($resultado);                                           // Con esto nos damos cuenta de muchas propiedades que nos pueden servir, 
                                                                            // como num_rows en donde si tiene un 0 es que no existe un resultado (fila) con el email colocado.
                                                                            // Si tiene un 1 quiere decir que si existe una fila con el correo

            if( $resultado -> num_rows) {                                   // Como $resultado es un objeto, accedemos a num_rows con la sintáxis de flecha 
                // Revisar si el password es correcto
                $usuario = mysqli_fetch_assoc($resultado);

                // Verificar si el password es correcto o no. password_verify toma dos parametros
                // El password colocado en el form y el password con el hash (se encuentra en la BD)
                // Retorna true si el password es correcto. False en caso contrario.
                $auth = password_verify($password, $usuario['password']);

                if($auth) {
                    // El usuario está autenticado
                    // Con session_start se comienza a crear una sesión. Es necesario para después usar la superglobal $_SESSION
                    session_start();

                    // Llenar el arreglo de la sesión
                    // Podemos agregar propiedades al arreglo. En este caso le agregamos un campo de usuario que almacenará el email
                    // También agregamos el campo login con el valor true.
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /admin');

                } else {
                    $errores[] = "El password es incorrecto";
                }
            } else {
                $errores[] = "El usuario no existe";
            }
        }
    }

    // Incluye el header
    require 'includes/funciones.php';
    incluirTemplate('header');
?>


    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php  foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <form method="POST" class="formulario">
            <fieldset>
                    <legend>Email y Password</legend>

                    <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="Tu Email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Tu password" id="password" required>
                </fieldset>

                <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>