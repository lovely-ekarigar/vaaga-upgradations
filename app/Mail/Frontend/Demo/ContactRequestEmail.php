<?php

namespace App\Mail\Frontend\Demo;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auth\User;
use App\Models\DemoRequest;
/**
 * Class DemoRequestEmail.
 */
class ContactRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $name;
    public $email;
    public $phone;
    public $message;

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct($name,$email,$phone,$message )
    {
        $this->name = $name;
           $this->email = $email;
              $this->phone = $phone;
              $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('frontend.mail.contact')
            ->subject('Contact Request Received on '.env('APP_NAME'))
            ->with('name',$this->name);

        // return $this->to($this->user->email,  $this->user->first_name)
        //     ->view('frontend.mail.demo_schedule_student')
        //     ->text('frontend.mail.demo_schedule_student-text')
        //     ->subject("Demo Scheduled")
        //     ->from( config('mail.from.address'), config('mail.from.name'));
    } 
}
