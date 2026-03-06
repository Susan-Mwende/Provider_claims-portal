<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class UserStatusController extends Controller

{

    /**

     * Responds with a welcome message with instructions

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $users = User::get();

        return view('users',compact('users'));

    }



    /**

     * Responds with a welcome message with instructions

     *

     * @return \Illuminate\Http\Response

     */

    public function userChangeStatus(Request $request)

    {

        \Log::info($request->all());

        $user = User::find($request->user_id);

        $user->status = $request->status;

        $user->save();



        return response()->json(['success'=>'Status change successfully.']);

    }

}
