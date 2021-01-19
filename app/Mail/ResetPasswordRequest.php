<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordRequest extends Mailable
{
    use Queueable, SerializesModels;

    //protected $token;
    //protected $name;

    public $param = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $param)
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
        $email = $this->from(env('MAIL_FROM_ADDRESS'), 'Ocean Property')
                       ->subject('Password Reset Instruction - Ocean Property')
                       ->view('mail.password.reset')->with($this->param);

        return $email;
    }
}
