<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Carbon;

class LogLastLoginTime
{

    public function handle(Login $event)
    {
        $event->user->last_login = Carbon::now();
        $event->user->save();
    }

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

}


