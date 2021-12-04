<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BankTransaction;
use DateTime;

class BankTransactionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     *  Loads payments from date range
     */
    public function index(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Get Transactions
        if(!is_null($startDate) && !is_null($endDate)) {
            $from = new DateTime($startDate);
            $to = new DateTime($endDate);

            $transactions = BankTransaction::whereBetween('date', [$from, $to])->orderBy('date', 'ASC')->get();
        } else {
            $transactions = null;
        }

        // Format Transactions
        if($transactions) {
            foreach ($transactions as $index => $transaction) {

                if($transaction->is_logement) {
                    $transactions[$index]->logements = $transaction->amount;
                    $transactions[$index]->withdrawals = 0;
                } else {
                    $transactions[$index]->logements = 0;
                    $transactions[$index]->withdrawals = $transaction->amount;
                }
            }
        }

        return [
            'data' => $transactions,
            'endDate' => $endDate,
            'startDate' => $startDate
        ];
    }

}
