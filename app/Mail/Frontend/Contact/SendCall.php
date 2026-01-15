<?php

namespace App\Mail\Frontend\Contact;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendContact.
 */
class SendCall extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $request;

    /**
     * SendContact constructor.
     *
     * @param Request $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(env("CONTACT_EMAIL"), config('mail.from.name'))
            ->view('frontend.mail.contact')
            ->text('frontend.mail.contact-text')
            ->subject("New call request")
            ->from($this->request->email, $this->request->first_name." ".$this->request->last_name)
            ->replyTo($this->request->email, $this->request->first_name." ".$this->request->last_name);
    }
}
