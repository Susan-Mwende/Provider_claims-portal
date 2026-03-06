<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimsViewTestController extends Controller
{
    public function index(Request $request) {
        $providers = DB::table('users')->pluck('pname', 'pcode')->toArray();

        //$users = DB::select('select top 10000 a.*, a.Invoice,a.amount,a.serviceType,a.providerType,a.invoice_date,b.name from claims a inner join users b on a.user_id = b.id order by b.id asc');
        if(request()->ajax()) {
            if (!empty($request->from_date) && !empty($request->to_date)) {

                $from = $request->from_date;
                $from = date('Y-m-d', strtotime($from));
                $to = $request->to_date;
                $to = date('Y-m-d', strtotime($to));
                // $to = date('Y-m-d', strtotime($to . ' +1 day'));

//
                try{

                    $users = DB::table('claims')
                        ->join('users', 'users.id', '=', 'claims.user_id')
                        ->select('users.pname','users.pcode','claims.*')
                        // ->whereRaw("CONVERT(DATE, claims.created_at) >= ?", [$from])
                        //->whereRaw("CONVERT(DATE, claims.created_at) <= ?", [$to]);
                        //->whereBetween('claims.created_at', array($from, $to));
                        ->whereRaw("CONVERT(date, claims.created_at) BETWEEN ? AND ?", [$from, $to]);
                    if (!empty($request->invoice)) {
                        $invoice = $request->invoice;
                        $users->where('claims.invoice', 'like', "%$invoice%");
                    }
                    if (!empty($request->provider)) {
                        $provider = $request->provider;
                        $users->where('users.pcode', 'like', "%$provider%");
                    }
                    $users->get();
                    return datatables()->of($users)->make(true);
                }catch (\Exception $e) {
                    // Log any errors that occur
                    Log::error('Error retrieving data: ' . $e->getMessage());
                    // Return an error response to the AJAX request
                    return response()->json(['error' => 'An error occurred while retrieving data.']);
                }
            }else if(!empty($request->invoice)){
                $invoice = $request->invoice;
                $users = DB::table('claims')
                    ->join('users', 'users.id', '=', 'claims.user_id')
                    ->select('users.*','claims.*')
                    ->where('claims.invoice', 'like', "%$invoice%")
                    // ->where('claims.created_at', '=', '2023-03-13 15:07:33.467')
                    ->get();
                return datatables()->of($users)->make(true);
            }else if(!empty($request->provider)){
                $provider = $request->provider;
                $users = DB::table('claims')
                    ->join('users', 'users.id', '=', 'claims.user_id')
                    ->select('users.*','claims.*')
                    ->where('users.pcode', 'like', "%$provider%")
                    // ->where('claims.created_at', '=', '2023-03-13 15:07:33.467')
                    ->get();
                return datatables()->of($users)->make(true);
            }  else {
                $tab = array();
                $users = DB::table('claims')
                    ->join('users', 'users.id', '=', 'claims.user_id')
                    ->select('users.*', 'claims.*')
                    //  ->where('claims.created_at', 'like', '%2023-03-23%')

                    ->limit(100000)

                    ->get();
                return datatables()->of($users)->make(true);

            }

        }
        return view('ClaimViewTest', compact('providers'));
    }

}
