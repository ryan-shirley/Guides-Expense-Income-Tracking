<?php

namespace App\Http\Controllers\API\Admin;

use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use DateTime;

class PaymentController extends Controller
{
    use Imagehandler;

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
     *  Loads payments
     */
    public function index(Request $request)
    {
        // TODO: Limit and offset from API call

        // Get Payments
        $payments = Payment::with(['user' => function ($query) {
            $query->select('name');
        }])->orderBy('purchase_date', 'DESC')->offset(0)->limit(10)->get();

        // Format Payments ID
        if($payments) {
            foreach ($payments as $index => $payment) {
                $payments[$index]->keyID = "p_" . $payment->ref_id;
                $payments[$index]->code = $payment->code != null ? $payment->code : "N/A";
                $payments[$index]->receipt_url = $this->GetReceiptUrl($payment->ref_id, $payment->_id);

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
            'data' => $payments
        ];
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
