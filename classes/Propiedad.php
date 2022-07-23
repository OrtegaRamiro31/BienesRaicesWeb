<?php

namespace App;

class Propiedad
{

    // Base de datos
    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id'];

    // Errores/Validacion
    protected static $errores = [];


    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;
    // Definir la conexion a la BD
    public static function setDB($database)
    {
        self::$db = $database;                              // Todo lo que sea static se accede con self:: y se agrega el simobolo $
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';              // Todo lo que sea public se accede con $this y no se agrega el simobolo $
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? 1;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            // Actualizar
            $this->actualizar();
        } else {
            // Creando nuevo registro
            $this->crear();
        }
    }

    public function crear()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();


        // debuguear($string);
        // Insertar en la base de datos
        $query = " INSERT INTO propiedades (";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ( '";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        // En caso de que todo sea correcto regresamos al usuario a la página admin.php.
        if ($resultado) {
            // Redireccionar al usuario. Solo se debe hacer si no hay código HTML previo
            // /admin hace referencia a la carpeta. ? significa que usaremos un query string
            // El resto son elementos Clave-Valor
            header('Location: /admin?resultado=1');
        }

        // return $resultado;
    }

    public function actualizar()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = " UPDATE propiedades SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);

        // En caso de que todo sea correcto regresamos al usuario a la página admin.php.
        if ($resultado) {
            // Redireccionar al usuario. Solo se debe hacer si no hay código HTML previo
            // /admin hace referencia a la carpeta. ? significa que usaremos un query string
            // El resto son elementos Clave-Valor
            header('Location: /admin?resultado=2');
        }

        // return $resultado;
    }

    // Eliminar un registro
    public function eliminar()
    {
        // Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
            header('Location: /admin?resultado=3');
        }
        // debuguear($query);
    }

    // Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];

        foreach (self::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Subida de archivos
    public function setImagen($imagen)
    {
        // Elimina la imágen previa

        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Eliminar el archivo
    public function borrarImagen()
    {
        // Comprobar si existe el archvio
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validación
    public static function getErrores()
    {
        return self::$errores;
    }

    public function validar()
    {
        // Con las sentencias if validamos los campos del formulario y otros aspectos de validación.
        if (!$this->titulo) {                              //En este caso poner !$titulo, indica que el título está vacío. Es como si pusieramos $titulo = '';
            self::$errores[] = "Debes añadir un título";
        }

        if (!$this->precio) {
            self::$errores[] = "El precio es Obligatorio";
        } else if (strlen(strval($this->precio)) > 8) {
            self::$errores[] = "El precio debe tener menos de 8 dígitos";
        }

        if (strlen($this->descripcion) < 50) {
            self::$errores[] = "La descripción es Obligatoria y debe tener al menos 50 caracteres";
        }

        if (!$this->habitaciones) {
            self::$errores[] = "El número de habitaciones es Obligatorio";
        }

        if (!$this->wc) {
            self::$errores[] = "El número de wc es Obligatorio";
        }

        if (!$this->estacionamiento) {
            self::$errores[] = "El número de lugares de estacionamiento es Obligatorio";
        }

        // if (!$this->vendedores_id) {
        //     self::$errores[] = "Elige un vendedor";
        // }

        if (!$this->imagen) {
            self::$errores[] = "La imagen es Obligatoria";
        }
        // elseif (!($this->imagen['type'] == "image/jpeg" || $this->imagen['type'] == "image/png")) {
        //     $errores[] = "Solo puedes seleccionar imágenes con extensión .png o .jpg";
        // }

        return self::$errores;
    }

    // Lista todas los registros
    public static function all()
    {
        $query = "SELECT * FROM propiedades";

        $resultado =  self::consultarSQL($query);

        return $resultado;
    }

    // Busca un registro por su ID
    public static function find($id)
    {
        $query = "SELECT * FROM propiedades WHERE id = ${id}";

        $resultado = self::consultarSQL($query);

        return  array_shift($resultado);              // Retorna el primer resultado del arreglo
    }

    public static function consultarSQL($query)
    {
        // Consultar la BD
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new self;                     // Con esto hacemos referencia a crearemos objetos de la clase Propiedad

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
