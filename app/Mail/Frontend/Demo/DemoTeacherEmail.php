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
class DemoTeacherEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $user;
    public $demo;
    public $teacher;
    public $link;
    public $course;

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct(User $user, User $teacher,DemoRequest $demo, $link,$course )
    {
        $this->user = $user;
           $this->teacher = $teacher;
              $this->demo = $demo;
                 $this->link = $link;
                 $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

         return $this->markdown('frontend.mail.demo_schedule_teacher')
            ->subject('Your demo has been scheduled by '.env('APP_NAME'))
            ->with('user',$this->user);
            
        // return $this->to($this->teacher->email,  $this->teacher->first_name)
        //     ->view('frontend.mail.demo_schedule_teacher')
        //     ->text('frontend.mail.demo_schedule_teacher-text')
        //     ->subject("Demo Scheduled")
        //     ->from( config('mail.from.address'), config('mail.from.name'));
    }
}
