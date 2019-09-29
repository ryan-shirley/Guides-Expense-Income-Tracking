<?php

namespace App\Http\Controllers\Leader;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:leader');
    }

    /**
     * Show the leader dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get User
        $user = Auth::user();

        return view('leader.home')->with([
            'user' => $user
        ]);
    }
}
