<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auth\User;
use App\Models\DemoRequest;
/**
 * Class DemoRequestEmail.
 */
class AdminFeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $user;
  

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        
                
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('frontend.mail.admin-feedback')
            ->subject('Received feedback from  '.$this->user->name)
            ->with('user',$this->user);
            
        // return $this->to($this->user->email,  $this->user->first_name)
        //     ->view('frontend.mail.feedback')
        //     ->text('frontend.mail.feedback-text')
        //     ->subject("Request for Feedback")
        //     ->from( config('mail.from.address'), config('mail.from.name'));
    }
}
