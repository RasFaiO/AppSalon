<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;    
    }

    public function enviarConfirmacion() {
        // Crear el objeto de email
        $email = new PHPMailer();
        $email->isSMTP();

        $email->Host = $_ENV['EMAIL_HOST'];
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];
        $email->Password = $_ENV['EMAIL_PASS'];

        // Acá va el dominio una vez subamos el proyecto 
        $email->setFrom('admin@appsalon.com');
        $email->addAddress('admin@appsalon.com','AppSalon.com');
        $email->Subject = 'Confirma tu Cuenta!';

        // Set HTML
        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>". $this->nombre . "</strong> has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href= '".$_ENV['APP_URL']."/confirmar-cuenta?token=" . $this->token . "'> ->Confirmar Cuenta<- </a></p>";
        $contenido .= "<p>Si tú no solicitaste este cambio, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $email->Body = $contenido;

        // Envia email
        $email->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $email = new PHPMailer();
        $email->isSMTP();

        $email->Host = $_ENV['EMAIL_HOST'];
        $email->SMTPAuth = true;
        $email->Port = $_ENV['EMAIL_PORT'];
        $email->Username = $_ENV['EMAIL_USER'];
        $email->Password = $_ENV['EMAIL_PASS'];

        // Acá va el dominio una vez subamos el proyecto 
        $email->setFrom('admin@appsalon.com');
        $email->addAddress('admin@appsalon.com','AppSalon.com');
        $email->Subject = 'Reestablece tu contraseña!';

        // Set HTML
        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>". $this->nombre . "</strong> has solicitado reestablecer tu contraseña en AppSalon, solo debes seguir el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href= '".$_ENV['APP_URL']."/recuperar?token=" . $this->token . "'> -> Reestablecer Contraseña <- </a></p>";
        $contenido .= "<p>Si tú no solicitaste este cambio, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $email->Body = $contenido;

        // Envia email
        $email->send();
    }
}