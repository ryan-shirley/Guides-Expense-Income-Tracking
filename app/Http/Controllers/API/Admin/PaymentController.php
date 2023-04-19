<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use DateTime;

class PaymentController extends Controller
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
    public function export(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Get Payments
        if(!is_null($startDate) && !is_null($endDate)) {
            $from = new DateTime($startDate);
            $to = new DateTime($endDate);

            $payments = Payment::whereBetween('purchase_date', [$from, $to])->orderBy('purchase_date', 'ASC')->get();
        } else {
            $payments = null;
        }

        // Format Payments ID
        if($payments) {
            foreach ($payments as $index => $payment) {
                $payments[$index]->keyID = "p_" . $payment->ref_id;
                $payments[$index]->code = $payment->code != null ? $payment->code : "N/A";

                if($payment->is_cash) {
                    $payments[$index]->cash_only = $payment->amount;
                    $payments[$index]->other = 0;
                } else {
                    $payments[$index]->cash_only = 0;
                    $payments[$index]->other = $payment->amount;
                }
            }
        }

        return [
            'data' => $payments,
            'endDate' => $endDate,
            'startDate' => $startDate
        ];
    }

}
