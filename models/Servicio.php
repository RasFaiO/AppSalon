<?php

namespace Model;

class Servicio extends ActiveRecord{
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id','nombre','precio'];

    // Registramos los atributos
    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if (!$this->precio) {
            self::$alertas['error'][] = 'El precio del servicio es obligatorio';
        }
        if (!is_numeric($this->precio)){
            self::$alertas['error'][] = 'El Precio no es valido';
        }
        if ($this->precio < 5000 || $this->precio > 500000){
            self::$alertas['error'][] = 'El precio debe estar entre $5.000 y $500.000';
        }
        return self::$alertas;
    }
}