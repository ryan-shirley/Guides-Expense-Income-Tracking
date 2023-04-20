<?php

namespace App\Http\Controllers\API\Admin;

use App\BankAccount;
use App\Traits\ImageHandler;
use App\Traits\UsePaymentsEnricher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use DateTime;

class PaymentController extends Controller
{
    use Imagehandler;
    use UsePaymentsEnricher;

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

        return $this->Enrich($payments);
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

        return [
            'data' => $this->Enrich($payments),
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
