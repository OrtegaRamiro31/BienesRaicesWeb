<?php

$id = $_GET['id'];      // Obtener el id con GET.
$id = filter_var($id, FILTER_VALIDATE_INT); //Filtrar el id

if (!$id) {
    header('Location: /');
}

// Importar instancia de BD
require 'includes/config/database.php';
$db = conectarBD();

// Escribir el query
$query = "SELECT * FROM propiedades WHERE id = ${id};";

// Consultar la BD
$consulta = mysqli_query($db, $query);


if ($consulta->num_rows === 0) {             // num_rows lo encontramos en $consulta. Si num_row tiene un 1 quiere decir que el id existe. Si tiene un 0 quiere decir que el id no existe.
    header('Location: /');
    /**
     * Asi puedo ver más detalles de $consulta y num_rows
     * echo "<pre>";
     * var_dump($consulta -> num_rows);        // Esta es una manera de acceder a un objeto
     * echo "</pre>";
     * **/
}
// Guardar información obtenida en un arreglo asociativo
$resultado = mysqli_fetch_assoc($consulta);


require 'includes/funciones.php';
incluirTemplate('header');
?>
<main class="contenedor seccion contenido-centrado">
    <h1><?php echo $resultado['titulo']; ?></h1>

    <img loading="lazy" src="/imagenes/<?php echo $resultado['imagen']; ?>" alt="imagen de la propiedad">

    <div class="resumen-propiedad">
        <p class="precio">$<?php echo $resultado['precio']; ?></p>
        <ul class="iconos-caracteristicas">
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                <p><?php echo $resultado['wc']; ?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                <p><?php echo $resultado['estacionamiento']; ?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                <p><?php echo $resultado['habitaciones']; ?></p>
            </li>
        </ul>
        <p><?php echo $resultado['descripcion']; ?>
        </p>
    </div>
</main>

<?php
incluirTemplate('footer');
mysqli_close($db);
?>