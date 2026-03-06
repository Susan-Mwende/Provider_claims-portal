<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function edit(User $user)
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
        $email = $user->get('email');
        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));
        Mail::to($email)->send(new WelcomeMail($user));
        $user->save();

        return back();
    }
    public function index()
    {
        $users = Provider::whereNull('approved_at')->get();
        return view('Providers.index', compact('users'));
    }

    public function approve($id)
    {
        $user = Provider::findOrFail($id);
        $user->update(['approved_at' => now()]);
        return redirect()->route('Providers.index')->withMessage('User Created successfully');
    }
    public function userslist(UsersDataTable $dataTable)
    {
        return $dataTable->render('userslist');
    }
}
