<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)){
                // Validar si el usuario existe
                $usuario = Usuario::where('email',$auth->email);
                if ($usuario){
                    // Validar pw y si está confirmada la cuenta
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)){ 
                        // Autenticar el usuario
                        if (!isset($_SESSION)){
                            session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //Redireccionar
                        if ($usuario->addmin === "1"){
                            $_SESSION['addmin'] = $usuario->addmin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                        // debug($_SESSION);
                    }
                    
                } else {
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(Router $router){
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if (empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                if ($usuario && $usuario->confirmado === "1"){
                    // Generar un token
                    $usuario->crearToken();
                    // Guardar en DB
                    $usuario->guardar();

                    // TODO: Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    // ENviar instrucciones
                    $email->enviarInstrucciones();

                    $alertas = Usuario::setAlerta('exito', 'Revisa tu email para recuperar tu contraseña');
                    // header('Location: /mensaje');
                } else {
                    $alertas = Usuario::setAlerta('error','Cuenta incorrecta o Usuario no confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);
        // debug($usuario);
        if (empty($usuario)){
            $alertas = Usuario::setAlerta('error','Token no válido');
            $error = true;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                $password = new Usuario($_POST);
                if ($_POST['password'] != $_POST['password_r']) {
                    Usuario::setAlerta('error','Las contraseñas no Coinciden');
                }
                // leer el nuevo password y guardarlo
                $alertas = $password->validarPassword();
                if(empty($alertas)){
                    $usuario->password = null;
                    $usuario->password = $password->password;
                    $usuario->hashPassword();
                    $usuario->token = null;
                    $resultado = $usuario->guardar();
                    if ($resultado){
                        header('Location: /');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar_password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if ($_POST['password'] != $_POST['password_v']) {
                Usuario::setAlerta('error','La confirmación de contraseña no Coincide');
            }
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if (empty($alertas)){
                // Verificar que el usuario no está registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();
                    // Generar un token único 
                    $usuario->crearToken();
                    // Enviar e-mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    // Crea el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token',$token);
        if (empty($usuario)){
            // Muestra mensahe de error
            Usuario::setAlerta('error','Token no válido');
        } else {
            // Modifica a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta Comprobada con éxito!');
        }
        // Obtener alertas antes de renderizar la página
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }
}