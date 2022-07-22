<?php

    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    require '../../includes/app.php';

    estaAutenticado();
    // Validar la ULR por ID Válido
    $id = $_GET['id'];                          // Obtememos el id mediante la barra de navegación
    $id = filter_var($id, FILTER_VALIDATE_INT); // hacemos uso de filter_var para hacer una validación. El primer parámetro es la variable que queremos validar, el segundo es el tipo de validación (en este caso es para validar que sea entero)

    // Redirigimos en caso de que no sea correcta la validación
    if (!$id) {
        header('Location: /admin');
    }

    // Consultar para obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);
    
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();

    // Asignamos los valores de la BD

    //  Ejecutar el código después de que el usuario envía el formulario
    // Con este if comprobamos cuando estemos usando el método POST. 
    // $_SERVER es una superglobal para obtener detalles acerca del servidor
    // $REQUEST_METHOD brinda información acerca de si se usa POST o GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Asignar los atributos
        $args = $_POST['propiedad'];

        $propiedad->sincronizar($args);
        
        // Validación
        $errores = $propiedad->validar();
     
        // Subida de archivos
        // Genera nombre único
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
            $propiedad->setImagen($nombreImagen);
        }

        // Revisar que el arreglo de errores esté vacío
        if (empty($errores)) { 
            // Almacenar la imágen
            $image->save(CARPETA_IMAGENES . $nombreImagen);
            $resultado = $propiedad->guardar();
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
        <?php include '../../includes/templates/formulario_propiedades.php'; ?>
        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>