<?php

namespace App\Mail\Frontend\Contact;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class DemoRequestEmail.
 */
class DemoRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $request;

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
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
        return $this->markdown('frontend.mail.demo')
            ->subject('Demo request received on '.env('APP_NAME'))
            ->with('request',$this->request);
            
        // return $this->to(config('mail.from.address'), config('mail.from.name'))
        //     ->view('frontend.mail.demo')
        //     ->text('frontend.mail.demo-text')
        //     ->subject("Demo request received")
        //     ->from($this->request->email, $this->request->name)
        //     ->replyTo($this->request->email, $this->request->name);
    }
}
