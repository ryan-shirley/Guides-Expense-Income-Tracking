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

        // Total for year
        $total_year = 0;
        foreach ($payments as $payment) {
            if(Carbon::now()->startOfYear() <= Carbon::parse($payment->purchase_date) && Carbon::now()->endOfYear() > Carbon::parse($payment->purchase_date)) {
                $total_year += $payment->amount;
            }
        }

        // Get total to pay back
        $total_to_pay_back = 0;
        foreach ($payments as $payment) {
            if(!$payment->paid_back) {
                $total_to_pay_back += $payment->amount;
            }
        }

        // Get number of expenses waiting on approval
        $num_waiting_approval = 0;
        foreach ($payments as $payment) {
            if(!$payment->approved) {
                $num_waiting_approval++;
            }
        }

        return view('admin.home')->with([
            'total_year' => $total_year,
            'total_to_pay_back' => $total_to_pay_back, 
            'num_waiting_approval' => $num_waiting_approval,
        ]);
    }
}
