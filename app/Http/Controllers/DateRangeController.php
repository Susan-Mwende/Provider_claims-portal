<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DateRangeController extends Controller
{
    function index(Request $request)
    {
        if(request()->ajax())
        {
            if(!empty($request->from_date))
            {
                $data = DB::table('claims')
                    ->whereBetween('invoice_date', array($request->from_date, $request->to_date))
                    ->get();
            }
            else
            {
                $data = DB::table('claims')
                    ->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('claimslist2');
    }
}

?>
