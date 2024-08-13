<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\UserRegistered;
use App\Mail\WelcomeMail;

class SendWelcomeEmailListener
{
    public function __construct()
    {
    }

    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeMail($event->user));
    }
}
