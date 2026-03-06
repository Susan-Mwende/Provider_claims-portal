<?php

namespace App\Http\Controllers;
use \Carbon\Carbon;
use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class TestClaimsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function claimslist(ClaimsDataTable $dataTable)
    {
        return $dataTable->render('claimslist');
    }

    public function userindex(Request $request)
    {
        if ($request->ajax()) {
            $model = Claim::with('users');
            return DataTables::eloquent($model)
                ->addColumn('users', function (Claim $claim) {
                    return $claim->users->name;
                })
                ->toJson();
        }
        return view('users');
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $myClaims = Claim::toBase()->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(25);
        return view('Test/index',compact('myClaims'));
    }

    public function search(Request $request){
        $search = $request->get('search');
        $claims = Claim::toBase()->where('Invoice','like','%'.$search.'%')->orwhere('Invoice','like','%'.$search.'%')->orwhere('invoice_date','like','%'.$search.'%')->orwhere('amount','like','%'.$search.'%') ->toBase()->paginate(25);
        return view('Test/index',['myClaims' => $claims]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Claims/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Amount' => 'required|numeric|gt:0',
            'Invoice' => 'required | unique:Claims',
            'serviceType' => 'required',
            'providerType' => 'required',
            'attachment' => 'required',
            'invoice_date' => 'required',
            'claimraisedby' => 'required',
            'attachment.*' => 'mimes:pdf,docx,doc,zip|max:100048',
        ]);
        if($request->hasfile('attachment'))
        {
            foreach($request->file('attachment') as $file)
            {
                $name=$file->getClientOriginalName();
                $file->move(public_path().'/files/', $name);
                $data[] = $name;
            }
        }
        //CHeck if alerts are set to true
        $alertstobesend = auth()->user()->sendalert;
        //defining a common path for the files
        $claimtime = Carbon::now();
        $claim = new Claim();
        $claim->user_id = auth()->user()->id;
        $claim->raiser_id = auth()->user()->id;
        $claim->Invoice = str_replace("-", "",$request->input('Invoice'));
        $claim->Amount = $request->input('Amount');
        $claim->batchno = 'single';
        $claim->serviceType = $request->input('serviceType');
        $claim->providerType = $request->input('providerType');
        $claim->invoice_date = $request->input('invoice_date');
        $claim->claimraisedby = $request->input('claimraisedby');
        $claim->attachment=json_encode($data);
        $claim->slug = \Str::slug($request->Invoice."_".$claimtime->toDateTimeString());
        $claim->save();
        if ($alertstobesend === '1') {
            //Send Email Notification to the user
            \Mail::send('/emails/singleclaimprovidermail', array(
                'Invoice' => $request->input('Invoice'),
                'Amount' => $request->get('Amount'),
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ), function ($message) use ($request) {
                $message->from('info-alerts@aar.co.ke');
                $message->to(auth()->user()->email)->subject('A NEW SINGLE CLAIM FROM PORTAL');

            });

            //Send Email Notification to the Admin
            \Mail::send('/emails/singleclaimprovidermail1', array(
                'Invoice' => $request->input('Invoice'),
                'Amount' => $request->get('Amount'),
                'serviceType' => $request->get('serviceType'),
                'providerType' => $request->get('providerType'),
                'invoice_date' => $request->get('invoice_date'),
                'Date' => time(),
            ), function ($message) use ($request) {
                $message->from('info-alerts@aar.co.ke');
                $message->to('info-alerts@aar.co.ke')->subject('A NEW SINGLE CLAIM FROM PORTAL');

            });
        }
        return redirect ('/Claims')->with('success', 'Your claim has been submitted successfully. Thank you.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Claim  $claims
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $myClaim=Claim::toBase()->where('slug', $slug)->first();
        return view('Claims.show', compact('myClaim'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Claim  $claims
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $slug)
    {
        $myClaim=DB::table('claims')->where('slug', $slug)->first();
        return view('Claims.edit', compact('myClaim'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Claim  $claims
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Claim $claims)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Claim  $claims
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claim $claims)
    {
        //
    }

    //////Search and Filtering
    ///
    ///
    public function gettheclaimsprovider()
    {
        return view('TestInvoiceReport');
    }
    public function getClaimsprovider(Request $request)
    {
        $user_id = auth()->user()->id;
        if ($request->ajax()) {
            $data = Claim::where('user_id', $user_id)->latest()->get();
            return Datatables::of($data)
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y/m/d'); // human readable format
                })
                ->addIndexColumn()
                ->make(true);
        }
    }
}
