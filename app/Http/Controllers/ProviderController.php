<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Mail\WelcomeMail;
use App\Models\PasswordSecurity;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\View\View
    {
        $providers = Provider::whereNull('approved_at')->paginate(1000);
        return view('Providers.index', compact('providers'));
    }

    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public function approve(int $user_id): \Illuminate\Http\RedirectResponse
    {
        $provider = Provider::findOrFail($user_id);

        $user = new user;
        ////////////////////////////////////////
        $user->name = auth()->user()->name;
        $user->pname = $provider->PROVIDER;
        $user->pcode = $provider->PROVIDER_CODE;
        $user->ptype = $provider->PROVIDER_TYPE;
        $user->pid = $provider->PROVIDER_ID;
        $user->email = $provider->PROVIDER_EMAIL;
        $secure_Password = Str::random(8);
        $user->password = bcrypt($secure_Password);
        $Usertime = Carbon::now();
        $user->slug = \Str::slug($provider->PROVIDER."_".$Usertime->toDateTimeString());
        $user->save();
        $passwordSecurity = PasswordSecurity::create([
            'user_id' => $user->id,
            'password_expiry_days' => 1,
            'password_updated_at' => Carbon::now(),
        ]);
//Send Email Notification to the user
        \Mail::send('/emails/NewUser', array(
            'name' =>$provider->PROVIDER,
            'pname' =>$provider->PROVIDER,
            'email' => $provider->PROVIDER_EMAIL,
            'pcode' => $provider->PROVIDER_CODE,
            'ptype' => $provider->PROVIDER_TYPE,
            'password' => $secure_Password,
            'Date' => time(),
        ), function($message) use ($provider) {
            $message->from('info-alerts@aar.co.ke');
            $message->to($provider->PROVIDER_EMAIL)->subject('WELCOME TO AAR CLAIMS PORTAL');

        });
        $provider->update(['approved_at' => now()]);
        return redirect()->route('providers')->withMessage('User approved successfully');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider): void
    {
        //
    }
}
