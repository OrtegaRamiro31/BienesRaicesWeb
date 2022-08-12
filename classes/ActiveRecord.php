<?php

namespace App;

class ActiveRecord
{

    // Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    // Errores/Validacion
    protected static $errores = [];



    // Definir la conexion a la BD
    public static function setDB($database)
    {
        self::$db = $database;                              // Todo lo que sea static se accede con self:: y se agrega el simobolo $
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
        $query = " INSERT INTO " . static::$tabla . " (";
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

        $query = " UPDATE " . static::$tabla . " SET ";
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
        // Eliminar el registro
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
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

        foreach (static::$columnasDB as $columna) {
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
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    // Lista todas los registros
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado =  self::consultarSQL($query);

        return $resultado;
    }

    // Obtiene determinado número de registros
    // Lista todas los registros
    public static function get($cantidad)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ".$cantidad;

        $resultado =  self::consultarSQL($query);

        return $resultado;
    }

    // Busca un registro por su ID
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";

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
            $array[] = static::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;                     // Con esto hacemos referencia a crearemos objetos de la clase Propiedad o Vendedor

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
