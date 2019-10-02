<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Income;
use App\BankAccount;
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
            if(Carbon::now()->startOfYear() <= Carbon::parse($payment->purchase_date) && Carbon::now()->endOfYear() > Carbon::parse($payment->purchase_date) && $payment->approved === 1) {
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

        // Income for current year
        $incomeForYear = Income::whereBetween('date', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->where('approved', '1')->get()->sum('amount');

        // Bank balance
        $bankBalance = BankAccount::where('title', 'Main')->first()->balance;

        // Payment history for year
        $paymentTotalsPerMonth = Payment::where('approved', '1')->get()->groupBy(function($val) {
            return Carbon::parse($val->purchase_date)->format('M');
        });

        // Payment totals per month
        $paymentHistory = array();
        $prevMonthBal = $bankBalance;
        foreach($paymentTotalsPerMonth as $month => $payments) {
            $total = 0;
            foreach($payments as $payment) {
                $total += $payment->amount;
            }
            $paymentHistory[date_parse($month)['month']]['label'] = $month;
            $paymentHistory[date_parse($month)['month']]['balance'] = $total;
        }
        // Sort months in order
        ksort($paymentHistory);

        // Store payments for bank balance
        $paymentHistoryBank = $paymentHistory;

        // Seperate into months and values
        $paymentMonths = array();
        $paymentValues = array();
        foreach($paymentHistory as $index => $value) {
            array_push($paymentMonths ,$value['label']);
            array_push($paymentValues ,$value['balance']);
        }
        $paymentHistory = array(
            'paymentMonths' => json_encode($paymentMonths, true),
            'paymentValues' => json_encode($paymentValues, true),
        );


        // Income history for year
        $incomeTotalsPerMonth = Income::where('approved', '1')->get()->groupBy(function($val) {
            return Carbon::parse($val->date)->format('M');
        });

        // Payment totals per month
        $incomeHistory = array();
        $prevMonthBal = $bankBalance;
        foreach($incomeTotalsPerMonth as $month => $payments) {
            $total = 0;
            foreach($payments as $payment) {
                $total += $payment->amount;
            }
            $incomeHistory[date_parse($month)['month']]['label'] = $month;
            $incomeHistory[date_parse($month)['month']]['balance'] = $total;
        }
        // Sort months in order
        ksort($incomeHistory);

        // Store income for bank balance
        $incomeHistoryBank = $incomeHistory;

        // Seperate into months and values
        $incomeMonths = array();
        $incomeValues = array();
        foreach($incomeHistory as $index => $value) {
            array_push($incomeMonths ,$value['label']);
            array_push($incomeValues ,$value['balance']);
        }
        $incomeHistory = array(
            'incomeMonths' => json_encode($incomeMonths, true),
            'incomeValues' => json_encode($incomeValues, true),
        );






        // Bank Balance History
        $bankBalancePrevious = $bankBalance;
        $month = Carbon::now();
        $paymentsFromBank = array_reverse($paymentHistoryBank,true);

        // Loop payments
        foreach($paymentsFromBank as $index => $value) {
            $found = false;

            // Loop though income
            foreach($incomeHistoryBank as $i => $val) {
                // If months are the same subtract
                if($value['label'] === $val['label']) {
                    $value['balance'] -= $val['balance'];
                }
            }
            

            // Loop until found month
            while($found === false) {
                // If equal to month
                if($month->month === Carbon::parse($value['label'])->month) {
                    
                    // Check if current month
                    if(Carbon::now()->month === Carbon::parse($value['label'])->month) {
                        $paymentHistoryBank[$index]['balance'] = $bankBalancePrevious;
                        $bankBalancePrevious += $value['balance'];
                    }
                    else {
                        $paymentHistoryBank[$index]['balance'] = $bankBalancePrevious;
                        $bankBalancePrevious += $value['balance'];
                    }
                    
                    $found = true;
                }
                
                $month->subMonth(1);
            }
        }
        
        // Seperate into months and values
        $bankMonths = array();
        $bankValues = array();
        foreach($paymentHistoryBank as $index => $value) {
            array_push($bankMonths ,$value['label']);
            array_push($bankValues ,$value['balance']);
        }
        $paymentHistoryBank = array(
            'bankMonths' => json_encode($bankMonths, true),
            'bankValues' => json_encode($bankValues, true),
        );


        return view('admin.home')->with([
            'total_year' => number_format($total_year, 2),
            'incomeForYear' => number_format($incomeForYear, 2),
            'bankBalance' => number_format($bankBalance, 2),
            'total_to_pay_back' => number_format($total_to_pay_back, 2), 
            'num_waiting_approval' => $num_waiting_approval,
            'paymentHistory' => $paymentHistory,
            'incomeHistory' => $incomeHistory,
            'bankHistory' => $paymentHistoryBank,
        ]);
    }
}
