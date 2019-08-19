<?php

namespace App\Listeners;

use App\User;
use App\Notifications\SomeoneRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSomeoneRegisteredNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $me = new User(['email' => env('MAIL_ADDRESS_INFO')]);

        $me->notify(new SomeoneRegistered($event->user));
    }
}
