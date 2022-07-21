<?php 
    require '../includes/funciones.php';
    $auth = estaAutenticado();

    if (!$auth) {
        header('Location: /');
    }
    // Importar la conexión
    require '../includes/config/database.php';
    $db = conectarBD();

    // Escribir el query
    $query = "SELECT propiedades.id, titulo, precio, imagen, vendedores.nombre, vendedores.apellido  FROM PROPIEDADES LEFT JOIN VENDEDORES ON propiedades.vendedores_id = vendedores.id;";

    // Consultar la BD
    $resultadoConsulta = mysqli_query($db, $query);

    // Muestra mensaje condicional
    // Obtenemos con $_GET el valor de resultado (el cual aparece arriba en la barra de navegacion)
    // con ?? null, si no se obtiene ningún resultado, no se muestra nada en la barra de arriba del navegador 
    $resultado = $_GET['resultado'] ?? null;
    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id) {

            // Eliminar el archivo
            $query = "SELECT imagen FROM propiedades WHERE id = ${id}";
            
            $resultado = mysqli_query($db, $query);
            $propiedad = mysqli_fetch_assoc($resultado);
            
            unlink('../imagenes/' . $propiedad['imagen']);  //Eliminamos la imágen

            // Eliminar la propiedad
            $query = "DELETE FROM propiedades WHERE id = ${id}";
            $resultado = mysqli_query($db, $query);
            
            if($resultado) {
                header('Location: /admin?resultado=3');
            }
        }
    }

    // Incluye el template
    incluirTemplate('header');
?>


    <main class="contenedor">
        <h1>Administrador de Bienes Raíces</h1>
        <?php if(intval( $resultado ) === 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif(intval( $resultado ) === 2): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php elseif(intval( $resultado ) === 3): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php endif; ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
    
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Vendedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- Mostrar los Resultados -->
                <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"></td>
                    <td><?php echo $propiedad['precio']; ?></td>
                    <td><?php echo $propiedad['nombre'] . " " . $propiedad['apellido']; ?></td>
                    <td>
                        <form method="POST" class="w-100">

                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>" > 

                            <input type="submit" class="boton-rojo-block boton-redondo" value="Eliminar">
                        </form>
                        <a href="/admin/propiedades/actualizar.php?id=<?php  echo $propiedad['id']; ?>" class="boton-amarillo-block boton-redondo">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

<?php 

    // Cerrar la conexión
    mysqli_close($db);

    incluirTemplate('footer');
?>