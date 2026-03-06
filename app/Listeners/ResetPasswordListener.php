<?php

namespace App\Listeners;

use App\PasswordHistory;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordListener
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
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(PasswordReset $passwordReset)
    {

    }
}
