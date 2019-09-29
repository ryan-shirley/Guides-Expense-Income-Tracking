<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
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
        $this->middleware('role:admin');
    }

    /**
     * Show the admin home dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments  = Payment::all()->sortByDesc("id");

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

        // Get total to pay back
        $total_to_pay_back = 0;
        foreach ($payments as $payment) {
            if(!$payment->paid_back) {
                $total_to_pay_back += $payment->amount;
            }
        }

        return view('admin.home')->with([
            'payments' => $payments,
            'total_all_time' => $total_all_time,
            'total_30_days' => $total_30_days,
            'total_to_pay_back' => $total_to_pay_back, 
        ]);
    }
}
