<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class UserRegisteredListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegisteredEvent  $event
     * @return void
     */
    public function handle(UserRegisteredEvent $event)
    {
        Mail::queue('emails.activate', ['user' => $event->user, 'activation_link' => route('auth.activate', ['token' => array_get($event->user, 'activation_token')])], function ($msg) use ($event) {
            $msg->to(array_get($event->user, 'email'));
            $msg->subject('Aктивируйте Ваш аккаунт для полноценного использования employment.kg');
        });
    }
}
