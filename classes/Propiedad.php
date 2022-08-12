<?php

namespace App;

class Propiedad extends ActiveRecord {
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id'];

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
        $this->vendedores_id = $args['vendedores_id'] ?? '';
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
            self::$errores[] = "La imagen de la propiedad es Obligatoria";
        }
        // elseif (!($this->imagen['type'] == "image/jpeg" || $this->imagen['type'] == "image/png")) {
        //     $errores[] = "Solo puedes seleccionar imágenes con extensión .png o .jpg";
        // }

        return self::$errores;
    }
}
