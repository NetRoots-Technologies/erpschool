<?php

namespace App\Listeners;

use App\Events\SendMail;
use App\Models\Student\Students;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Mail;

class SendMailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\SendMail $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        $data = $event->data;
        $page_Data = null;


        try {

            $page_Data['data'] = $data;
            Mail::send('emails.mailEvent', $page_Data, function ($message) use ($page_Data) {

                $message->to($page_Data['data']['email']);
                $message->subject($page_Data['data']['subject']);
            });
        } catch (\Exception $e) {
            dd($e);
        }


    }
}
