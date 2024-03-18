<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Income;
use App\BankAccount;
use Carbon\Carbon;
use DB;
use DateTime;

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
        // Retrieve all payments and pluck the 'date' property
        $uniqueYears = Payment::pluck('purchase_date')
        ->map(function ($date) {
            return date('Y', strtotime($date));
        })
        ->unique()
        ->toArray();

        // Get the current year
        $currentYear = date('Y');

        // Add the current year if it's not already in the list
        if (!in_array($currentYear, $uniqueYears)) {
            $uniqueYears[] = $currentYear;
        }

        // Sort the years in descending order
        rsort($uniqueYears);

        
        $from = new DateTime(Carbon::now()->startOfYear());
        $to = new DateTime(Carbon::now());
        $payments = Payment::whereBetween('purchase_date', [$from, $to])->orderBy('id', 'DESC')->get();

        // Total for year
        $total_year = 0;
        foreach ($payments as $payment) {
            if(Carbon::now()->startOfYear() <= Carbon::parse($payment->purchase_date) && Carbon::now()->endOfYear() > Carbon::parse($payment->purchase_date) && $payment->approved === true) {
                $total_year += $payment->amount;
            }
        }

        // Get total to pay back
        $paymentsToPayBack  = Payment::where('paid_back', false)->get();
        $total_to_pay_back = 0;
        foreach ($paymentsToPayBack as $payment) {
            if(!$payment->paid_back) {
                $total_to_pay_back += $payment->amount;
            }
        }

        // Get number of expenses waiting on approval
        $paymentsToApprove  = Payment::where('approved', false)->get();
        $num_waiting_approval = 0;
        foreach ($paymentsToApprove as $payment) {
            if(!$payment->approved) {
                $num_waiting_approval++;
            }
        }

        // Income for current year
        $incomeForYear = Income::whereBetween('date', [$from, $to])->where('approved', true)->get()->sum('amount');

        return view('admin.home')->with([
            'total_year' => number_format($total_year, 2),
            'incomeForYear' => number_format($incomeForYear, 2),
            'total_to_pay_back' => number_format($total_to_pay_back, 2), 
            'num_waiting_approval' => $num_waiting_approval,
            'show_sidebar' => false,
            'years' => $uniqueYears
        ]);
    }
}
