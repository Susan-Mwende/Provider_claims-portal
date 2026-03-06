<?php

namespace App\Http\Controllers;
use App\Models\PasswordSecurity;
use App\Models\Provider;
use \Carbon\Carbon;
use App\Models\Claim;
use App\Models\User;
use App\cr;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DataTables;

class AdminUsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function index()
    {
        $claims = Claim::orderBy('created_at','DESC')->limit(6)->get();
        return view('admin.home', ['myClaims' => $claims,]);
    }
    public function search(Request $request){
        $search = $request->get('search');
        $users = User::where('pname','like','%'.$search.'%') ->orwhere('email','like','%'.$search.'%')->orwhere('pcode','like','%'.$search.'%') ->toBase()->paginate(25);
        return view('users.index',compact('users'));
    }

    public function add_users_form()
    {
        $providers = DB::table('providers')->pluck("PROVIDER", "id");
        
        try {
            return view('users.create', compact('providers'));
        } catch (\Exception $e) {
            // If the create view doesn't exist, return the index view
            return view('users.index', compact('providers'));
        }
    }

    public function submit_users_data(Request $request)
    {
        // Debug logging
        Log::info('User creation attempt started', [
            'request_data' => $request->all(),
            'user_authenticated' => auth()->check(),
            'current_user' => auth()->check() ? auth()->user()->email : 'none'
        ]);

        $errorMessage = [
            'name.required'=>'Please enter provider Name',
            'email.required' => 'Please enter provider email address',
            //'password.required' => 'Please enter password',
            //'password.confirmed' => 'Your passwords do not match',
            //'password.regex' => 'Password must contain at least one number, special character and both uppercase and lowercase letters.',
            // 'pid.required'=>'Please enter Provider ID',
            'pcode.required'=>'Please enter Provider Code',
            'ptype.required'=>'Please enter Provider Type',

        ];
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
           // 'password' => ['required', 'string', 'min:4', 'confirmed'],
            'pcode' => 'required',
            // 'pid' => 'required|unique:users',
            'ptype' => 'required',
        ];


        $this->validate($request, $rules, $errorMessage);
        
        Log::info('Validation passed, creating user');
        
        $claimtime = Carbon::now();
        $user = User::create([
            'name' => $request->name,
            'pname' =>$request->name,
            'email' => strtolower($request->email),
            'pcode' => $request->pcode,
            'password'=>Hash::make('password'),
            'ptype' => $request->ptype,
            'role' => $request->role,
            'status' => $request->status,
            'slug' => Str::slug($request->name."_".$claimtime->toDateTimeString()),
            'email_verified_at' => Carbon::now(),
        ]);
        
        Log::info('User created successfully', ['user_id' => $user->id, 'user_email' => $user->email]);
        
        // Temporarily disabled logging to fix timeout
        // \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully created a new user' .$request->name.' '.$request->email);
        $passwordSecurity = PasswordSecurity::create([
            'user_id' => $user->id,
            'password_expiry_days' => 30,
            'password_updated_at' => Carbon::now(),
        ]);
        
        Log::info('Password security record created');
        
        //Send Email Notification to the user
        Mail::send('/emails/NewUser', array(
            'name' => $request->name,
            'pname' =>$request->name,
            'email' => strtolower($request->email),
            'pcode' => $request->pcode,
            'ptype' => $request->ptype,
            'Date' => time(),
        ), function($message) use ($request){
            $message->from('info-alerts@aar.co.ke');
            $message->to($request->email)->subject('WELCOME TO AAR CLAIMS PORTAL');
            // Temporarily disabled logging to fix timeout
            // \LogActivity::addToLog('Account details sent successfully to the user' .$request->name.' '.$request->email);
        });

        Log::info('Email sent, preparing to redirect');

        $this->message('message','New provider created successfully');
        return redirect()->route('users.list');

    }

    public function fetch_all_users()
    {
        $users = User::where('id', '!=', auth()->id())->toBase()->paginate(25);
        return view('users.index',compact('users'));
    }

    public function edit_users_form(User $user)
    {
        return view('users.edit',compact('user'));
    }

    public function edit_users_form_submit(Request $request, $slug)
{
    // Validation rules
    $rules = [
        'pname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'role' => ['required', 'string'],
        'status' => ['required', 'string'],
        'sendalert' => ['required', 'boolean'],
    ];

    // Validate the request
    $this->validate($request, $rules);

    // Find the user by the slug
    $user = User::where('slug', $slug)->first();

    // Check if the user exists
    if ($user !== null) {
        // Update user details
        $user->update([
            'pname' => $request->pname,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'sendalert' => $request->sendalert,
        ]);

        // Log the activity
        \LogActivity::addToLog('User ' . auth()->user()->name . ' updated provider ' . $request->pname);

        // Flash a success message
        $request->session()->flash('message', 'Provider updated successfully!');
    } else {
        $request->session()->flash('error', 'Provider not found!');
    }

    // Redirect back
    return redirect()->back();
}



    public function view_single_users(User $user)
    {
        return view('users.view',compact('user'));
    }

    public function delete_users(User $user)
    {
        $user->delete();
        $this->message('message','Provider deleted successfully!');
        return redirect()->back();
    }

    public function message(string $name = null, string $message = null)
    {
        return session()->flash($name,$message);
    }
    public function autocomplete(Request $request)
    {
        $data = DB::table("providers")->select('PROVIDER')
            ->where("PROVIDER","LIKE","%{$request->input('query')}%")
            ->get();
        return response()->json($data);
    }


    public function getallOtherProviderDetails(Request $request)
    {
        $providercode = DB::table("providers")
            ->where("id", $request->name)
            ->pluck("PROVIDER_CODE", "id");
        return response()->json($providercode);
    }
    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
