<?php
    require '../../includes/funciones.php';
    $auth = estaAutenticado();
    if (!$auth) {
        header('Location: /');
    }

    // Validar la ULR por ID Válido
    $id = $_GET['id'];                          // Obtememos el id mediante la barra de navegación
    $id = filter_var($id, FILTER_VALIDATE_INT); // hacemos uso de filter_var para hacer una validación. El primer parámetro es la variable que queremos validar, el segundo es el tipo de validación (en este caso es para validar que sea entero)

    // Redirigimos en caso de que no sea correcta la validación
    if (!$id) {
        header('Location: /admin');
    }

    // Base de datos
    require '../../includes/config/database.php';
    $db = conectarBD();

    // Consultar para obtener los datos de la propiedad
    $consulta = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = mysqli_query($db, $consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

    // echo "<pre>";
    // var_dump($propiedad);
    // echo "</pre>";


    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    // Arreglo con mensajes de errores
    $errores = [];

    // Asignamos los valores de la BD
    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamiento = $propiedad['estacionamiento'];
    $vendedorId = $propiedad['vendedores_id'];
    $imagenPropiedad = $propiedad['imagen'];
    //  Ejecutar el código después de que el usuario envía el formulario
    // Con este if comprobamos cuando estemos usando el método POST. 
    // $_SERVER es una superglobal para obtener detalles acerca del servidor
    // $REQUEST_METHOD brinda información acerca de si se usa POST o GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";

        // Asignamos a nuestras variables los valores de los campos del formulario. Sanitizamos con Mysqli_real_escape_string. $db es para indicar la base de datos. $_POST['titulo'] indica el name del elemento del formulario 
        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string($db, $_POST['wc']);
        $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
        $vendedorId = mysqli_real_escape_string($db, $_POST['vendedor']);
        $creado = date('y/m/d');

        // Asignar files hacia una variable. Esto creará un arreglo, se hace con los inputs del tipo type según lo que experimenté.
        $imagen = $_FILES['imagen'];
        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";
        // exit;


        // Con las sentencias if validamos los campos del formulario y otros aspectos de validación.
        if (!$titulo) {                              //En este caso poner !$titulo, indica que el título está vacío. Es como si pusieramos $titulo = '';
            $errores[] = "Debes añadir un título";
        }

        if (!$precio) {
            $errores[] = "El precio es Obligatorio";
        }

        if (strlen($descripcion) < 50) {
            $errores[] = "La descripción es Obligatoria y debe tener al menos 50 caracteres";
        }

        if (!$habitaciones) {
            $errores[] = "El número de habitaciones es Obligatorio";
        }

        if (!$wc) {
            $errores[] = "El número de wc es Obligatorio";
        }

        if (!$estacionamiento) {
            $errores[] = "El número de lugares de estacionamiento es Obligatorio";
        }

        if (!$vendedorId) {
            $errores[] = "Elige un vendedor";
        }


        // Validar por tamaño (1 Mb máximo)
        $medida = 1000 * 1000;

        if ($imagen['size'] > $medida) {
            $errores[] = 'La imagen no debe ser mayor a 1Mb';
        }

        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

        // Revisar que el arreglo de errores esté vacío
        if (empty($errores)) {

            // Ruta para después crear una carpeta
            $carpetaImagenes = '../../imagenes/';

            // Verificamos si no existe la carpeta de imagenes
            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);        // Si no existe, la crea
            }

            $nombreImagen = '';
            /** SUBIDA DE ARCHIVOS **/
            // Si la imágen existe puede comprobarse con imagen['name'];
            if ($imagen['name']) {
                // Eliminar imagen previa. unlink permitirá eliminar elementos, en este caso, una imágen correspondiente.
                unlink($carpetaImagenes . $propiedad['imagen']);

                // Generar un nombre único para la imágen. md5 calcula el hash(genera como caracteres al azar), 
                // pero por si solo siempre genera el mismo nombre. 
                // Agregando uniqid a md5 si se genera un id único diferente siempre
                // rand() genera números enteros aleatorios y con true indicamos más entropía
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

                // Subir la imagen. Mueve de memoria temporal para guardarlo en el servidor completamente
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
            } else {
                $nombreImagen = $propiedad['imagen'];
            }

            // Insertar en la base de datos
            $query = " UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamiento = ${estacionamiento}, vendedores_id = $vendedorId WHERE id = ${id}";

            // echo $query;

            $resultado = mysqli_query($db, $query);


            // En caso de que todo sea correcto regresamos al usuario a la página admin.php.
            if ($resultado) {
                // Redireccionar al usuario. Solo se debe hacer si no hay código HTML previo
                // /admin hace referencia a la carpeta. ? significa que usaremos un query string
                // El resto son elementos Clave-Valor
                header('Location: /admin?resultado=2');
            }
        }
    }

    incluirTemplate('header');
?>


<main class="contenedor">
    <h1>Actualizar Propiedad</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <img src="/imagenes/<?php echo $imagenPropiedad; ?>" alt="Imagen de propiedad" class="imagen-small">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedor">
                <option value="">-- Seleccione Vendedor --</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
        </fieldset>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>