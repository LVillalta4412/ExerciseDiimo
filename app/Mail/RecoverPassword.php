<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecoverPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $subject = '¡Bienvenido! Se le ha generado un enlace para realizar el cambio de su contraseña ';
    public $email;
    public $token;

    public function __construct($email, $token)
    { 
        $this->email = $email;
        $this->token = $token;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.recover_password');
    }
}
