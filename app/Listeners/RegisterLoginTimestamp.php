<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterLoginTimestamp
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserLoginEvent $event)
    {
        $event->user->update(['last_login_at' => Carbon::now()]);
    }
}
