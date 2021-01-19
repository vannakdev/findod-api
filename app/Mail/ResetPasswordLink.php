<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordLink extends Mailable {

    use Queueable,SerializesModels;

    //protected $link;
    //protected $name;

    public $param= array();

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $param) {
        $this->param = $param;
        //$this->link = $link;
        //$this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $email = $this->from(env('MAIL_FROM_ADDRESS'), "Ocean Property")
                        ->subject('Password Reset Instruction - Ocean Property')
                        ->view('mail.password.resetlink')->with($this->param);
        return $email;
    }

}
