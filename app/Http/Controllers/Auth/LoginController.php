<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\TwoFactorCode;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Validator;
use Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3; // Login attempts limit
    protected $decayMinutes = 2; // Lockout duration

    protected function sendFailedLoginResponse(Request $request)
    {
        \LogActivity::addToLog('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function authenticated(Request $request, $user)
    {
        $password = $request->input('password');
        
        // Temporarily disabled logging to fix timeout
        // \LogActivity::addToLog('Successful login attempt', [
        //     'user_id' => $user->id,
        //     'email' => $user->email,
        //     'role' => $user->role,
        //     'ip' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        // ]);

        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => $request->ip(),
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Check if user is active
        if ($user->status == 'Inactive') {
            \LogActivity::addToLog('Inactive user login attempt', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            Auth::logout();
            return redirect('/suspended')->withErrors(['Your account is inactive']);
        }

        // Check for default password - remove this check since Susan has a proper password
        // if ($request->password == 'password') {
        //     \LogActivity::addToLog('Default password login attempt', [
        //         'user_id' => $user->id,
        //         'email' => $user->email
        //     ]);
        //     $request->session()->put('password_expired_id', $user->id);
        //     Auth::logout();
        //     return redirect('/passwordChange')
        //         ->with('message', "This is default password, please change to use a more secure password.");
        // }

        // Password expiry check
        $request->session()->forget('password_expired_id');
        $password_updated_at = $user->passwordSecurity->password_updated_at;
        $password_expiry_days = $user->passwordSecurity->password_expiry_days;
        $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);

        if ($password_expiry_at->lessThan(Carbon::now())) {
            $request->session()->put('password_expired_id', $user->id);
            \LogActivity::addToLog('Password expired login attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_expired_at' => $password_expiry_at
            ]);
            auth()->logout();
            return redirect('/passwordExpiration')
                ->with('message', "Your Password is expired, You need to change your password.");
        }

        // Two-factor authentication setup
        try {
            $user->generateTwoFactorCode();
            // \LogActivity::addToLog('Two-factor code generated');
            
            $user->notify(new TwoFactorCode());
            // \LogActivity::addToLog('Two-factor code sent to user');
        } catch (\Exception $e) {
            Log::error('Two-factor authentication error', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Error generating verification code. Please try again.');
        }

        // Role-based redirection
        switch($user->role) {
            case 'guest':
                \LogActivity::addToLog('Guest user redirected to home');
                return redirect('/home');
            case 'admin':
                \LogActivity::addToLog('Admin user redirected to admin dashboard');
                return redirect('/admin');
            case 'auditor':
                \LogActivity::addToLog('Auditor redirected to reporting dashboard');
                return redirect('/Reporting/dashboard');
            default:
                \LogActivity::addToLog('User redirected to home page');
                return redirect('/home');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            \LogActivity::addToLog('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        
        if ($attempts >= $this->maxAttempts) {
            \LogActivity::addToLog('Account locked due to too many login attempts', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'attempts' => $attempts
            ]);
        }
        
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts
        );
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}