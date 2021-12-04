<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Show all the incomming money.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereNull('approved_at')->get();

        return view('admin.users.index', compact('users'));
    }
    /**
     * Remove the pending approval account from DB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);
        DB::table('role_user')->delete($id);
        $user->delete();
        $request->session()->flash('alert-success', $user->name . ' account has been deleted');
        return redirect()->route('admin.users');
    }

    /**
     * Approve account as leader.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->approved_at = now();
        $user->api_token = md5(uniqid($user->email, true));
        $user->save();

        $request->session()->flash('alert-success', $user->name . ' has been approved.');
        return redirect()->route('admin.users');
    }

    
}
