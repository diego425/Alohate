<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class codigoLogin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     *
     */
    public $codeVer;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($codeVer,$user){
        $this->codeVer = $codeVer;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sistema@cuartosags.com', null)
                    ->view('Login.Mail.codigoVerificacion');
    }
}