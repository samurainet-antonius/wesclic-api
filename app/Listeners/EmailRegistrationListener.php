<?php

namespace App\Listeners;

use App\Events\EmailRegistration;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;

class EmailRegistrationListener
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
     * @param  \App\Events\ExampleEvent  $event
     * @return void
     */
    public function handle(EmailRegistration $event)
    {
      $data = $event->request;
      $date = Carbon::now()->addSeconds(1);
      Queue::later($date, new SendEmail($data));
    }
}
