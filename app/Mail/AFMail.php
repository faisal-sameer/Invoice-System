<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AFMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**  
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ezooag@gmail.com')->subject('دوام الموظفين')->markdown('emails.afmail');
    }
}
