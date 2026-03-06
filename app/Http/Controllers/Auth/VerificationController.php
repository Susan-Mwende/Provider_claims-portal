<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    public function authenticated(Request $request, $user)
    {


            if ($user->role == 'guest') {
                \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. 'Successfully Logged in under provider profile');
                return redirect('/home');
            } else if (($user->role == 'admin') OR ($user->role == 'staff')) {
                \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. 'Successfully Logged in under staff profile');
                return redirect('/admin');
            } else {
                \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. 'Successfully Logged in under administrator profile');
                return redirect('/home');

            }


    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
