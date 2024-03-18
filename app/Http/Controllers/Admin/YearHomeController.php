<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use App\Income;
use App\User;
use Carbon\Carbon;
use DateTime;

class YearHomeController extends Controller
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
    public function index($year)
    {   
        $startYearDate = Carbon::createFromFormat('Y-m-d', "$year-01-01")->startOfDay();
        $endYearDate = Carbon::createFromFormat('Y-m-d', "$year-12-31")->endOfDay();

        $payments = Payment::whereBetween('purchase_date', [$startYearDate, $endYearDate])->orderBy('id', 'DESC')->get();

        // Total for year
        $total_year = 0;
        foreach ($payments as $payment) {
            if($payment->approved === true) {
                $total_year += $payment->amount;
            }
        }

        // Get total to pay back
        $paymentsToPayBack  = Payment::whereBetween('purchase_date', [$startYearDate, $endYearDate])->where('paid_back', false)->get();
        $total_to_pay_back = 0;
        foreach ($paymentsToPayBack as $payment) {
            $total_to_pay_back += $payment->amount;
        }

        // Get number of expenses waiting on approval
        $paymentsToApprove  = Payment::whereBetween('purchase_date', [$startYearDate, $endYearDate])->where('approved', false)->get();
        $num_waiting_approval = 0;
        foreach ($paymentsToApprove as $payment) {
            $num_waiting_approval++;
        }

        // Income for current year
        $incomeForYear = Income::whereBetween('date', [$startYearDate, $endYearDate])->where('approved', true)->get()->sum('amount');

        return view('admin.yearHome')->with([
            'total_year' => number_format($total_year, 2),
            'incomeForYear' => number_format($incomeForYear, 2),
            'total_to_pay_back' => number_format($total_to_pay_back, 2), 
            'num_waiting_approval' => $num_waiting_approval,
            'show_sidebar' => true,
            'year' => $year
        ]);
    }
}
