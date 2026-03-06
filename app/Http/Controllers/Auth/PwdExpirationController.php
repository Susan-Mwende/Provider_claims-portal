<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\LogActivity;

class PwdExpirationController extends Controller
{

    public function showPasswordExpirationForm(Request $request){
        $password_expired_id = $request->session()->get('password_expired_id');
        Log::info('showPasswordExpirationForm', ['password_expired_id' => $password_expired_id]);
        if(!isset($password_expired_id)){
            LogActivity::addToLog('Expired password reset successfully');
            return redirect('/login');
        }
        return view('auth.passwordExpiration');
        LogActivity::addToLog('Failed attempt to reset password.');
    }

    public function postPasswordExpiration(Request $request){
        Log::info('=== postPasswordExpiration ENTRY ===', ['all_input' => $request->all()]);
        $password_expired_id = $request->session()->get('password_expired_id');
        Log::info('postPasswordExpiration called', ['password_expired_id' => $password_expired_id]);
        if(!isset($password_expired_id)){
            Log::info('No password_expired_id in session, redirecting to login');
            LogActivity::addToLog('Expired password reset successfully');
            return redirect('/login');
        }

        $user = User::find($password_expired_id);
        Log::info('User loaded', ['user_id' => $user?->id, 'email' => $user?->email]);
        
        if (!$user) {
            Log::error('User not found', ['password_expired_id' => $password_expired_id]);
            return redirect('/login')->with('error', 'User not found');
        }
        
        // Simple validation check first
        if (!$request->get('current-password') || !$request->get('new-password')) {
            Log::warning('Missing required fields');
            return redirect()->back()->with("error","Please fill in all required fields.");
        }
        
        if (!(Hash::check($request->get('current-password'), $user->password))) {
            LogActivity::addToLog('Failed attempt to reset password message: Your current password does not matches with the password you provided. Please try again.');
            Log::warning('Current password check failed');
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            LogActivity::addToLog('Failed attempt to reset password message: New Password cannot be same as your current password. Please choose a different password.');
            Log::warning('New password same as current');
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        try {
            $validatedData = $request->validate([
                'current-password' => 'required',
                'new-password' => 'required|string|min:6|confirmed',
            ]);
            Log::info('Validation passed', ['validated' => $validatedData]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors());
        }

        //Change Password
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        LogActivity::addToLog('Password reset successfully for the user.');
        Log::info('User password saved');
        //Update password updation timestamp
        if ($user->passwordSecurity) {
            $user->passwordSecurity->password_updated_at = Carbon::now();
            $user->passwordSecurity->save();
            Log::info('PasswordSecurity updated', ['password_updated_at' => $user->passwordSecurity->password_updated_at]);
        } else {
            Log::warning('User has no passwordSecurity relation');
        }
        LogActivity::addToLog('Password security row updated to time of change of password.');
        $request->session()->forget('password_expired_id');
        $request->session()->regenerate();
        Log::info('Session cleared, redirecting to login');
        return redirect('/login')->with("status","Password changed successfully, You can now login !");
    }
}
