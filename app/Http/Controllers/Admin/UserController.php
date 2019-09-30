<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
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
     * Show the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user  = User::findOrFail($id);

        return view('admin.payment.user.show')->with([
            'user' => $user
        ]);
    }
}
