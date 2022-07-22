<?php

require '../../includes/app.php';

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();

// Base de datos
$db = conectarBD();

// Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();

// debuguear($errores);

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';
//  Ejecutar el código después de que el usuario envía el formulario
// Con este if comprobamos cuando estemos usando el método POST. 
// $_SERVER es una superglobal para obtener detalles acerca del servidor
// $REQUEST_METHOD brinda información acerca de si se usa POST o GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** Crea una nueva instancia **/
    $propiedad = new Propiedad($_POST);

    /** SUBIDA DE ARCHIVOS **/

    // Generar un nombre único para la imágen. md5 calcula el hash(genera como caracteres al azar), 
    // pero por si solo siempre genera el mismo nombre. 
    // Agregando uniqid a md5 si se genera un id único diferente siempre
    // rand() genera números enteros aleatorios y con true indicamos más entropía
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    // Setear la imagen
    // Realiza un resize a la imagen con intervention
    if($_FILES['imagen']['tmp_name']){
        $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }


    // Validar
    $errores = $propiedad->validar();


    // Revisar que el arreglo de errores esté vacío
    if (empty($errores)) {

        // Crear la carpeta para subir imágenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        }

        // Guarda la imagen en el servidor.
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        // Guarda en la base de datos
        $resultado = $propiedad->guardar();

        // Mensaje de éxito o error

        // En caso de que todo sea correcto regresamos al usuario a la página admin.php.
        if ($resultado) {
            // Redireccionar al usuario. Solo se debe hacer si no hay código HTML previo
            // /admin hace referencia a la carpeta. ? significa que usaremos un query string
            // El resto son elementos Clave-Valor
            header('Location: /admin?resultado=1');
        }
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
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

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

            <select name="vendedores_id">
                <option value="">-- Seleccione Vendedor --</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>