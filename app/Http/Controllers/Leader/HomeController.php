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

        // Format Payments ID
        foreach ($payments as $index => $payment) {
            $payments[$index]->keyID = "p_" . $payment->ref_id;
            
            if($payment->is_cash) {
                $payments[$index]->cash_only = $payment->amount;
                $payments[$index]->other = 0;
            } else {
                $payments[$index]->cash_only = 0;
                $payments[$index]->other = $payment->amount;
            }
        }

        // Total for year
        $total_year = 0;
        foreach ($payments as $payment) {
            if(Carbon::now()->startOfYear() <= Carbon::parse($payment->purchase_date) && Carbon::now()->endOfYear() > Carbon::parse($payment->purchase_date) && $payment->approved === 1) {
                $total_year += $payment->amount;
            }
        }

        // Get last 30 days
        $dateMonthAgo = Carbon::now()->subDays(30);
        $total_30_days = 0;
        foreach ($payments as $payment) {
            if($dateMonthAgo <= Carbon::parse($payment->purchase_date) && $payment->approved === 1) {
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

        // Get number of expenses waiting on approval
        $num_waiting_approval = 0;
        foreach ($payments as $payment) {
            if(!$payment->approved) {
                $num_waiting_approval++;
            }
        }

        return view('leader.home')->with([
            'user' => $user,
            'payments' => $payments,
            'total_year' => $total_year,
            'num_waiting_approval' => $num_waiting_approval,
            'total_30_days' => $total_30_days,
            'total_to_be_paid' => $total_to_be_paid, 
        ]);
    }
}
