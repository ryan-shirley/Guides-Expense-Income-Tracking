<?php

namespace App\Http\Controllers\API\Admin;

use App\BankAccount;
use App\Traits\ImageHandler;
use Illuminate\Http\JsonResponse;
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
        }])->orderBy('purchase_date', 'DESC')->paginate(15);

        // Format Payments ID
        if($payments) {
            foreach ($payments as $index => $payment) {
                $payments[$index]->keyID = "p_" . $payment->ref_id;
                $payments[$index]->code = $payment->code != null ? $payment->code : "N/A";
                $payments[$index]->receipt_url = $this->GetReceiptUrl($payment->ref_id, $payment->_id);

                switch ($payment->code) {
                    case 1:
                        $payments[$index]->code_name = "Programme Material";
                        break;
                    case 2:
                        $payments[$index]->code_name = "Uniform & Badges";
                        break;
                    case 3:
                        $payments[$index]->code_name = "Camps / Outings / Events";
                        break;
                    case 4:
                        $payments[$index]->code_name = "IGG Membership Fees";
                        break;
                    case 5:
                        $payments[$index]->code_name = "Equipment";
                        break;
                    case 6:
                        $payments[$index]->code_name = "Unit Administration";
                        break;
                    case 7:
                        $payments[$index]->code_name = "Rent";
                        break;
                    case 8:
                        $payments[$index]->code_name = "Adult Training";
                        break;
                    case 9:
                        $payments[$index]->code_name = "Thinking Day";
                        break;
                    case 10:
                        $payments[$index]->code_name = "Banking Fees";
                        break;
                    case 11:
                        $payments[$index]->code_name = "Miscellaneous";
                        break;
                    case 12:
                        $payments[$index]->code_name = "Fundraising";
                        break;
                    case 13:
                        $payments[$index]->code_name = "If drawing cash from bank or float (petty cash)";
                        break;
                    default:
                        echo "N/A";
                }

                if($payment->is_cash) {
                    $payments[$index]->cash_only = $payment->amount;
                    $payments[$index]->other = 0;
                } else {
                    $payments[$index]->cash_only = 0;
                    $payments[$index]->other = $payment->amount;
                }
            }
        }

        return $payments;
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


    /**
     * Mark as paid back
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function markPaidBack(Request $request, string $id){
        // Change paid back status
        $payment = Payment::findOrFail($id);
        $payment->paid_back = true;
        $payment->save();

        return response()->json([
            'message' => 'Payment successfully marked as paid back',
        ]);
    }

    /**
     * Approve payment
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function approve(Request $request, string $id){
        // Mark Approved
        $payment = Payment::findOrFail($id);
        $payment->approved = true;
        $payment->save();

        // Take out of bank account
        $bankBalance = BankAccount::where('title', 'Main')->first();
        $bankBalance->balance -= $payment->amount;
        $bankBalance->save();

        return response()->json([
            'message' => 'Payment successfully approved',
        ]);
    }

    /**
     * Mark as received receipt
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function receivedReceipt(Request $request, string $id){
        // Create data and convert amount into negative as expense
        $payment = Payment::findOrFail($id);

        // Save
        $payment->receipt_received = true;
        $payment->save();

        return response()->json([
            'message' => 'Payment successfully marked with receipt received',
        ]);
    }

    /**
     * Delete receipt
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Request $request, string $id){
        $p = Payment::find($id);
        $p->delete();

        return response()->json([
            'message' => 'Payment successfully deleted',
        ]);
    }

}
