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
class TeacherBatchEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $user;
    public $batch;
    public $course;

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct(User $user,$batch,$course )
    {
        $this->user = $user;
        $this->batch = $batch;
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('frontend.mail.teacher-batch')
            ->subject('New batch assigned on '.env('APP_NAME'))
            ->with('user',$this->user);

        // return $this->to($this->user->email,  $this->user->first_name)
        //     ->view('frontend.mail.demo_schedule_student')
        //     ->text('frontend.mail.demo_schedule_student-text')
        //     ->subject("Demo Scheduled")
        //     ->from( config('mail.from.address'), config('mail.from.name'));
    }
}
