<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * The email title for view to use for the message.
     *
     * @var string
     */
    public $subject;

    /**
     * The view data for the email parameter.
     *
     * @var array
     */
    public $param = [];

    /**
     * The email address view to use email.
     *
     * @var string
     */
    public $emailFrom;

    /**
     * The HTML template for view to use email.
     *
     * @var string
     */
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, array $param, $template)
    {
        $this->subject = $subject;
        $this->param = $param;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(env('MAIL_FROM_ADDRESS'), 'Ocean Property')
                        ->subject($this->subject)
                        ->view('mail.template.'.$this->template)->with($this->param);

        return $email;
    }
}
