<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a91969de70f46f';
        $mail->Password = '41be879d3c1a7a';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirmación de Cuenta UpTask';

        $mail->isHTML(TRUE); 
        $mail->CharSet = 'UTF-8';

        $contendio = '<html>' ; 
        
       $contendio .= "<p><strong>Hola" . $this->nombre . "</strong>Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente
        enlace</p>" ; 
       $contendio .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>" ; 
       $contendio .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje </p>";
       $contendio .= '</html>' ; 

       $mail->Body = $contendio ; 

       //Enviar el email
       $mail->send(); 
    }

    public function enviarInstrucciones()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a91969de70f46f';
        $mail->Password = '41be879d3c1a7a';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(TRUE); 
        $mail->CharSet = 'UTF-8';

        $contendio = '<html>' ; 
        
       $contendio .= "<p><strong>Hola" . $this->nombre . "</strong>Parece que has olvidado tu password,sigue el siguiente enlace
       para recuperarlo</p>" ; 
       $contendio .= "<p>Presiona aqui: <a href='http://localhost:3000/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>" ; 
       $contendio .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje </p>";
       $contendio .= '</html>' ; 

       $mail->Body = $contendio ; 

       //Enviar el email
       $mail->send(); 
    }


}
