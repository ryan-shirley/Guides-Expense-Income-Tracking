<?php

namespace App\Http\Controllers\Leader;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

class ChatbotController extends Controller
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
     * Show the chatbot
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('leader.chatbot')->with([
            'user' => Auth::user()
        ]);
    }
}
