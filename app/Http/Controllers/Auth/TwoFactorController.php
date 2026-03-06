<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'twofactor']);
    }

    public function index()
    {
        return view('auth.twoFactor');
    }
    public function store(Request $request)
    {
        // Debug logging
        Log::info('=== TwoFactorController::store ENTRY ===', [
            'all_input' => $request->all(),
            'auth_check_before' => auth()->check(),
            'session_data' => session()->all()
        ]);
        
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();
        
        Log::info('=== User Data ===', [
            'user_found' => $user ? 'YES' : 'NO',
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'stored_code' => $user?->two_factor_code,
            'input_code' => $request->input('two_factor_code'),
            'code_match' => $user ? ($request->input('two_factor_code') == (string)$user->two_factor_code) : 'NO_USER'
        ]);

        if($user && $request->input('two_factor_code') == (string)$user->two_factor_code)
        {
            $user->resetTwoFactorCode();
            \LogActivity::addToLog('Verification code successfully send to user '.auth()->user()->name.' email, '.auth()->user()->email);
            if ($user->role == 'guest') {
                \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully logged into the Portal under provider Profile');
                Log::info('Redirecting to /home');
                return redirect('/home');
            } else if ($user->role == 'admin') {
                \LogActivity::addToLog('User  ' . auth()->user()->name . ' email, ' . auth()->user()->email . ' successfully logged into the Portal under Admin Profile');
                Log::info('Redirecting to /admin');
                return redirect('/admin');
            }
            else if ($user->role == 'staff'){
                \LogActivity::addToLog('User  ' . auth()->user()->name . ' email, ' . auth()->user()->email . ' successfully logged into the Portal under Staff Profile');
                Log::info('Redirecting to /admin');
                return redirect('/admin');
            }
            else if($user->role == 'auditor') {
                \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully logged into the Portal under Reporting Profile');
                Log::info('Redirecting to /Reporting/dashboard');
                return redirect('/Reporting/dashboard');
            }
        }
        
        Log::info('Verification failed - redirecting back with error');
        return redirect()->back()->withErrors(['two_factor_code' => 'The code you have entered is invalid']);
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('Code sent again. Check your email');
    }
}
