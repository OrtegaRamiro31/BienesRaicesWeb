<?php

namespace App;

class Vendedor extends ActiveRecord {
    
    protected static $tabla = "vendedores";
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';              // Todo lo que sea public se accede con $this y no se agrega el simobolo $
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
    }
    public function validar()
    {
        if (!$this->nombre) {                              
            self::$errores[] = "El Nombre es obligatorio";
        }
        if (!$this->apellido) {                              
            self::$errores[] = "El Apellido es obligatorio";
        }
        if (!$this->telefono) {                              
            self::$errores[] = "El Teléfono es obligatorio";
        }
        if (!preg_match('/[0-9]{10}/', $this->telefono)) {
            self::$errores[] = "Formato de Teléfono no válido";
        }
        return self::$errores;
    }

    public static function obtenerNombreVendedor($id) {
        $args = [];

        $query = "SELECT nombre, apellido FROM vendedores WHERE id=${id}";
        $nombres = static::$db->query($query)->fetch_assoc();

        foreach($nombres as $value) {
            $args[] = $value;
        }

        $args = join(" ",$args);
        return $args;
    }
}