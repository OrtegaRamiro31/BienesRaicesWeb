<?php

require '../../includes/app.php';

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();

$propiedad = new Propiedad;

// Consulta para obtener todos los vendedores
$vendedores = Vendedor::all();

// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();

//  Ejecutar el código después de que el usuario envía el formulario
// Con este if comprobamos cuando estemos usando el método POST. 
// $_SERVER es una superglobal para obtener detalles acerca del servidor
// $REQUEST_METHOD brinda información acerca de si se usa POST o GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** Crea una nueva instancia **/
    $propiedad = new Propiedad($_POST['propiedad']);

    /** SUBIDA DE ARCHIVOS **/

    // Generar un nombre único para la imágen. md5 calcula el hash(genera como caracteres al azar), 
    // pero por si solo siempre genera el mismo nombre. 
    // Agregando uniqid a md5 si se genera un id único diferente siempre
    // rand() genera números enteros aleatorios y con true indicamos más entropía
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    // Setear la imagen
    // Realiza un resize a la imagen con intervention
    if($_FILES['propiedad']['tmp_name']['imagen']){
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }


    // Validar
    $errores = $propiedad->validar();

    // debuguear($propiedad);
    // Revisar que el arreglo de errores esté vacío
    if (empty($errores)) {

        // Crear la carpeta para subir imágenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        }

        // Guarda la imagen en el servidor.
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        
        // Guarda en la base de datos
        $propiedad->guardar();

        // Mensaje de éxito o error


    }
}

incluirTemplate('header');
?>


<main class="contenedor">
    <h1>Crear</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php'?>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
    incluirTemplate('footer');
?>