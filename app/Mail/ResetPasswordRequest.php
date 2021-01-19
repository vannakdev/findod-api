<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordRequest extends Mailable
{
    use Queueable, SerializesModels;

    //protected $token;
    //protected $name;

    public $param= array();

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Array $param)
    {
        //$this->token = $token;
        //$this->name =$name;
        $this->param = $param;
    }   

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        $email  = $this->from( env('MAIL_FROM_ADDRESS') , "Ocean Property"  )
                       ->subject('Password Reset Instruction - Ocean Property')                       
                       ->view('mail.password.reset')->with($this->param);        
        return $email;
        
    }
}
