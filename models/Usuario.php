<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','addmin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $addmin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->addmin = $args['addmin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta(){
        if (!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->apellido){
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if (!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function validarLogin() {
        if (!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        return self::$alertas;
    }

    public function validarEmail() {
        if (!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }
    
    public function validarPassword() {
        if (!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }
    
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya existe';
        }
        return $resultado;
    }

    public function hashPassword(){
        // Password_hash es una función de php que nos ayuda a encriptar la contraseña, recibe dos parámetros; el valor a haschear y el método que utilizará
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        // Haremos un token con ayuda de la funcion uniqueid lo cual retorna un valor único de 13 dígitos
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if (!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Contraseña incorrecta o la cuenta no está confirmada';
        } else {
            // debug('Confirmado');
            return true;
        }
    }
}