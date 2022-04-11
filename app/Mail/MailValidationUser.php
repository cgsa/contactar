<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailValidationUser extends Mailable
{
    use Queueable, SerializesModels;


    public $link;

    public $vars;

    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vars)
    {
        //var_dump($vars);die;
        $frontend_url = "http://contactar.com.ar";//config('onehouser.frontend_url');

        $this->link =  "{$frontend_url}/validate-account?token=" . $vars['validation_token'];
        $this->subject(__('Validación de cuenta'));
        $this->vars = $vars;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.users.validar_cuenta');
    }
}
