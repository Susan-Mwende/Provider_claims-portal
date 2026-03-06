<?php

namespace App\Http\Controllers\Auth;
use \Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordSecurity;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function authenticated(Request $request, $user)
    {
        if ($user->role == 'guest') {
            return redirect('/home');
        } else if (($user->role == 'admin') OR ($user->role == 'staff')) {
            return redirect('/admin');
        } else {
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {

        $messages = [
            'name.required'=>'Please enter provider Name',
            'email.required' => 'Please enter provider email address',
            'password.required' => 'Please enter password',
            'password.confirmed' => 'Your passwords do not match',
            'password.regex' => 'Password must contain at least one number, special character and both uppercase and lowercase letters.',
           // 'pid.required'=>'Please enter Provider ID',
            'pcode.required'=>'Please enter Provider Code',
            'ptype.required'=>'Please enter Provider Type',

        ];
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}\S+$/'],
            'pcode' => 'required',
            'ptype' => 'required',
        ];

        return Validator::make($data, $rules, $messages);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */


        protected function create(array $data)
    {
        $claimtime = Carbon::now();
        $user = User::create([
            'name' => $data['name'],
            'pname' => $data['name'],
            'slug' => \Str::slug($data['name']."_".$claimtime->toDateTimeString()),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'pcode' => $data['pcode'],
            'ptype' => $data['ptype'],
        ]);
        \LogActivity::addToLog('User '.$data['name'].' email, '.$data['email']. ' created successfully into the database');
        $passwordSecurity = PasswordSecurity::create([
            'user_id' => $user->id,
            'password_expiry_days' => 30,
            'password_updated_at' => Carbon::now(),
        ]);
        \LogActivity::addToLog('User '.$data['name'].' email, '.$data['email']. ' security details updated.');
        return $user;
    }

}
