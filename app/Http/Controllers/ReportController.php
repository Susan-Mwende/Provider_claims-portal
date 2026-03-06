<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use DataTables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function gettheclaims()
    {
        return view('ClaimsReports');
    }

    public function getClaims(Request $request)
    {
        $model = Claim::with('users')->select('claims.*')->latest()->get();
        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('pname', function (Claim $claim) {
                    return $claim->users->pname;
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y/m/d'); // human readable format
                })
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function index()
    {
        return view('Reporting.dashboard');
    }

}
