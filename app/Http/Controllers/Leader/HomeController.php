<?php

namespace App\Http\Controllers\Leader;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

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
        $payments = $user->payments;

        // Total all time
        $total_all_time = 0;
        foreach ($payments as $payment) {
            $total_all_time += $payment->amount;
        }

        // Get last 30 days
        $dateMonthAgo = Carbon::now()->subDays(30);
        $total_30_days = 0;
        foreach ($payments as $payment) {
            if($dateMonthAgo <= Carbon::parse($payment->purchase_date)) {
                $total_30_days += $payment->amount;
            }
        }

        // Get total to be paid amount
        $total_to_be_paid = 0;
        foreach ($payments as $payment) {
            if(!$payment->paid_back) {
                $total_to_be_paid += $payment->amount;
            }
        }

        return view('leader.home')->with([
            'user' => $user,
            'payments' => $payments,
            'total_all_time' => $total_all_time,
            'total_30_days' => $total_30_days,
            'total_to_be_paid' => $total_to_be_paid, 
        ]);
    }
}
