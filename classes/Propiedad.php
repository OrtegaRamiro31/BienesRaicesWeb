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
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';              // Todo lo que sea public se accede con $this y no se agrega el simobolo $
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? '';
    }

    public function guardar()
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
        // debuguear($query);

        $resultado = self::$db->query($query);

        return $resultado;
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
    public function setImagen($imagen) {
        // Asignar al atributo de imagen el nombre dela imagen
        if($imagen) {
            $this->imagen = $imagen;
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
        } else if (strlen(strval($this->precio)) > 10) {
            self::$errores[] = "El precio debe tener menos de 10 dígitos";
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

        if (!$this->vendedores_id) {
            self::$errores[] = "Elige un vendedor";
        }

        if (!$this->imagen) {
            self::$errores[] = "La imagen es Obligatoria";
        } 
        // elseif (!($this->imagen['type'] == "image/jpeg" || $this->imagen['type'] == "image/png")) {
        //     $errores[] = "Solo puedes seleccionar imágenes con extensión .png o .jpg";
        // }

        return self::$errores;
    }
}
