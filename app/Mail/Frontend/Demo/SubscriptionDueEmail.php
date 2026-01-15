<?php

namespace App\Mail\Frontend\Demo;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auth\User;
use App\Models\DemoRequest;
use App\Models\Order;
/**
 * Class DemoRequestEmail.
 */
class SubscriptionDueEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $order;
    public $items;

    /**
     * DemoRequestEmail constructor.
     *
     * @param Request $request
     */
    public function __construct(Order $order,$items )
    {
        $this->order = $order;
        $this->items = $items;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('frontend.mail.student-subscription-due')
            ->subject('Payment Reminder on '.env('APP_NAME'))
            ->with('order',$this->order);

        // return $this->to($this->user->email,  $this->user->first_name)
        //     ->view('frontend.mail.demo_schedule_student')
        //     ->text('frontend.mail.demo_schedule_student-text')
        //     ->subject("Demo Scheduled")
        //     ->from( config('mail.from.address'), config('mail.from.name'));
    }
}
