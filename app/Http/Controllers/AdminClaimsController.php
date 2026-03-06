<?php

namespace App\Http\Controllers;
use \Carbon\Carbon;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AdminClaimsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index(): \Illuminate\View\View
    {
        $claims = Claim::orderBy('created_at','DESC')->get();
        return view('admin.home', ['myClaims' => $claims,]);
    }
    public function pdfview(Request $request)
    {
        $bookings = DB::table("bookings")->get();
        view()->share('bookings',$bookings);
        if($request->has('download')){
            $pdf = PDF::loadView('pdfview');
            return $pdf->download('pdfview.pdf');
        }
        return view('pdfview');
    }
    public function search(Request $request): \Illuminate\View\View
    {
        $search = $request->get('search');
        $claims = Claim::where('Invoice','like','%'.$search.'%')->orwhere('Invoice','like','%'.$search.'%')->orwhere('invoice_date','like','%'.$search.'%')->orwhere('amount','like','%'.$search.'%') ->toBase()->paginate(25);
        return view('AdminClaims.index',compact('claims'));
    }

    public function searchInClaims(Request $request)
    {
        $model = Claim::with('users')->select('claims.*');
        if ($request->ajax()) {
            return Datatables::of($model)
                ->addIndexColumn()
                ->addColumn('pname', function (Claim $claim){
                    return $claim->users->pname;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function create(Claim $model)
    {
        return view('admin.edit', ['claims' => $model->get(['id', 'name'])]);
    }

    public function add_AdminClaims_form(): \Illuminate\View\View|null
    {
        $providers = DB::table('users')->where('role', 'guest')->orderBy('pname')->pluck("pname", "id");
        if( View::exists('AdminClaims.create') ){
            return view('AdminClaims.create',compact('providers'));
        }
    }

    public function submit_AdminClaims_data(Request $request): \Illuminate\Http\RedirectResponse
    {

        $errorMessage = [
            'Amount.required'=>'Please enter Amount',
            'claimraisedby.required'=>'Please Enter your Name',
            'Amount.numeric'=>'Amount value must be a numeric',
            'Invoice.required' => 'Please enter Invoice Number',
            'serviceType.required' => 'Please enter service type',
            'providerType.required' => 'Please enter provider type',
            'attachment.required' => 'Please attach the file.',

        ];
        $rules = [
            'Amount' => 'required|numeric|gt:0',
            'Invoice' => 'required | unique:Claim',
            'serviceType' => 'required',
            'providerType' => 'required',
            'attachment' => 'required',
            'invoice_date' => 'required',
            'attachment.*' => 'mimes:pdf,docx,doc,zip|max:100048'
        ];
        //$this->validate($request, $rules, $errorMessage);

        if($request->hasfile('attachment'))
        {
            foreach($request->file('attachment') as $file)
            {
                $name=$file->getClientOriginalName();
                $file->move(public_path().'/files/', $name);
                //File::copy('C:\xampp\htdocs\CURRENT\public\files\\'.$name, 'C:\portal_claims_backup\single_claims\\'.$name);
                //copy('C:\xampp\htdocs\CURRENT\public\files\\'.$name, 'C:\portal_claims_backup\single_claims\\'.$name);
                $data[] = $name;
            }
        }
        //defining a common path for the files
        $claimtime = Carbon::now();

        Claim::create([
            'user_id' => $request->user_id,
            'raiser_id' => auth()->user()->id,
            'Invoice' => $request->Invoice,
            'Amount' => $request->Amount,
            'serviceType' => $request->serviceType,
            'providerType' => $request->providerType,
            'invoice_date' => $request->invoice_date,
            'attachment' => json_encode($data),
            'slug' => Str::slug($request->Invoice."_".$claimtime->toDateTimeString()),
        ]);
        \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully raised single claim.');
       $user = $request->user_id;
        $alertstobesend  = DB::table('users')->where('id', $user)->value('sendalert');
       //Send Email Notification to the user
        if ($alertstobesend === '1') {
            \Mail::send('/emails/adminmail', array(
                'providername' => DB::table('users')->where('id', $request->user_id)->value('pname'),
                'providercode' => DB::table('users')->where('id', $request->user_id)->value('pcode'),
                'provideremail' => DB::table('users')->where('id', $request->user_id)->value('email'),
                'Invoice' => $request->input('Invoice'),
                'Amount' => $request->get('Amount'),
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ), function ($message) use ($request) {
                $message->from('info-alerts@aar.co.ke');
                $message->to(DB::table('users')->where('id', $request->user_id)->value('email'))->subject('A NEW CLAIM FROM PORTAL');

            });


            //Send Email Notification to the Admin
            \Mail::send('/emails/adminmail1', array(
                'Invoice' => $request->input('Invoice'),
                'Amount' => $request->get('Amount'),
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ), function ($message) use ($request) {
                $message->from('info-alerts@aar.co.ke');
                $message->to(auth()->user()->email)->subject('A NEW CLAIM FROM PORTAL');

            });
        }
        $this->meesage('message','New claim submitted successfully');
        return redirect()->back();

    }

    public function fetch_all_AdminClaims(): \Illuminate\View\View
    {
        $claims = Claim::toBase()->orderBy('id', 'desc')->paginate(25);
        return view('AdminClaims.index',compact('claims'));
    }

    public function edit_AdminClaims_form(Claim $claim): \Illuminate\View\View
    {
        return view('AdminClaims.edit',compact('claim'));
    }

    public function edit_AdminClaims_form_submit(Request $request, Claim $claim): \Illuminate\Http\RedirectResponse
    {
        $errorMessage = [
            'Amount.required'=>'Please enter Amount',
            'Amount.numeric'=>'Amount value must be a numeric',
            'Invoice.required' => 'Please enter Invoice Number',
            'serviceType.required' => 'Please enter service type',
            'providerType.required' => 'Please enter provider type',
            'attachment.required' => 'Please attach the file.',

        ];
        $rules = [
            'Amount' => 'required|numeric|gt:0',
            'Invoice' => 'required | unique:Claim,Invoice,' . $claim->id,
            'serviceType' => 'required',
            'providerType' => 'required',
            'attachment' => 'required',
            'invoice_date' => 'required',
            'attachment.*' => 'mimes:pdf,docx,doc,zip|max:100048'
        ];
        $this->validate($request, $rules, $errorMessage);
        if($request->hasfile('attachment'))
        {
            foreach($request->file('attachment') as $file)
            {
                $name=$file->getClientOriginalName();
                $file->move(public_path().'/files/', $name);
                $data[] = $name;
            }
        }
        $claimtime = Carbon::now();
        $claim->update([
            'user_id' => auth()->user()->id,
            'Invoice' => $request->Invoice,
            'Amount' => $request->Amount,
            'serviceType' => $request->serviceType,
            'providerType' => $request->providerType,
            'invoice_date' => $request->invoice_date,
            'attachment' => json_encode($data),
            'slug' => Str::slug($request->Invoice."_".$claimtime->toDateTimeString()),
        ]);
        \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully updated a single claim.');
        $this->meesage('message','Claim Details updated successfully!');
        return redirect()->back();
    }

    public function view_single_AdminClaims(Claim $claim): \Illuminate\View\View
    {
        return view('AdminClaims.view',compact('claim'));
    }

    public function delete_AdminClaims(Claim $claim): \Illuminate\Http\RedirectResponse
    {
        $claim->delete();
        $this->meesage('message','Claim deleted successfully!');
        \LogActivity::addToLog('User  '.auth()->user()->name.' email, '.auth()->user()->email. ' successfully deleted a single claim.');
        return redirect()->back();
    }

    public function meesage(?string $name = null, ?string $message = null): void
    {
        session()->flash($name, $message);
    }
    public function gettheclaims(): \Illuminate\View\View
    {
        return view('claimslist1');
    }
    public function getClaims(Request $request)
    {
        $model = Claim::with('users')->select('claims.*')->latest()->get();
        if ($request->ajax()) {
            return Datatables::of($model)
                ->addIndexColumn()
                ->addColumn('pname', function (Claim $claim){
                    return $claim->users->pname;
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y/m/d'); // human readable format
                })
                ->addIndexColumn()
                ->make(true);
        }
    }
}
