<?php

namespace Modules\Users\App\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Users\App\Emails\ResetPasswordMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Users\App\Events\ResetCodeRequested;

class SendResetCode implements ShouldQueue
{
    use  InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param ResetCodeRequested $event
     * 
     * @return void
     */
    public function handle(ResetCodeRequested $event)
    {
        Mail::to($event->user->email)
            ->send(new ResetPasswordMail($event->user));
    }
}
