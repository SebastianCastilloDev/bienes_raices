<?php

namespace App;

class ActiveRecord{

    //base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Validacion y manejo de errores
    protected static $errores = [];

    //definir la conexion a la bd
    public static function setDB($database){
        self::$db = $database;
    }


    public function guardar()
    {
        if(!is_null($this->id)){
            //actualizar
            $this->actualizar();
        }else{
            //crear
            $this->crear();
        }
    }

    public function crear(){
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        //Insertar en la base de datos
        $query = "INSERT INTO ". static::$tabla .  "( ";
        $query .= join(", ",array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '",array_values($atributos));
        $query .= " ') ";

        
        $resultado = self::$db->query($query);

        //mensaje de exito o error
        if($resultado){

            //redirecionar al usuario
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar()
    {
        $atributos = $this->sanitizarAtributos();

        $valores = [];

        foreach($atributos as $key=>$value){
            $valores[] = "{$key}='${value}'";
        }

        $query = "UPDATE " . static::$tabla. " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);
        if($resultado) {
            header('Location: /admin?resultado=2');
        }

    }

    public function eliminar()
    {
        // debuguear(static::$tabla);
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado){
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    //identificar y unir las columnas de la bd
    function atributos()
    {
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if($columna === 'id' ) continue;
            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
        
    }

    //subida de archivos

    public function setImagen($imagen)
    {

        //elimina la imagen previa
        if(!is_null($this->id)){
            $this->borrarImagen();
        }

        //Asignar al atributo de imagen el nombre de la imagen. a la nueva iamgen
        if($imagen){
            $this->imagen = $imagen;
        }
    }

    public function borrarImagen()
    {
        //comprobar si existe archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo){
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    //Validacion
    public static function getErrores()
    {
        return static::$errores;
    }
    
    public function validar(){
        
        static::$errores = [];
        return static::$errores;
    }

    
    public static function all()
    {
        $query = "SELECT * FROM ". static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //obtienen determinado numero de registrops
    public static function get($cantidad)
    {
        $query = "SELECT * FROM ". static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    //busca un regitro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    public static function consultarSQL($query)
    {
        //consultar ala bd
        $resultado = self::$db->query($query);

        //iterar los resultados
        $array = [];

        while($registro = $resultado->fetch_assoc()){
            $array[] = static::crearObjeto($registro);
        }

        //liberar memoria
        $resultado -> free();
        //retornar los resultados
        return $array;
    }
    
    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach($registro as $key=>$value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //sincroniza el objeto en memoria con los cambios realizados por el usuario

    public function sincronizar($args = [])
    {
        foreach($args as $key=>$value){
            if(property_exists($this,$key) && !is_null($value)){
                $this -> $key = $value;
            }
        }
    }
}