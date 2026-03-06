<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        Upload::create([
            'file' => $request->file('file')->storeAs('file', $request->user()->id.'.zip')
        ]);


    
        if ($request->wantsJson()) {
            return response([], 204);
        }

        return back();
    }
}
