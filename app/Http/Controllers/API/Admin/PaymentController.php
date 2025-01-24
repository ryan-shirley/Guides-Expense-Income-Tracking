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
use Carbon\Carbon;

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
    public function index(Request $request, $year)
    {
        $request->validate([
            'limit' => 'required|integer|max:200|min:1',
            'search' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $limit = $request->query('limit');
        $search = $request->query('search');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        // Set default date range for the current year
        $currentYear = date('Y');
        $startDate = $dateFrom ? 
            Carbon::parse($dateFrom)->startOfDay() : 
            Carbon::createFromFormat('Y-m-d', "$currentYear-01-01")->startOfDay();
        
        $endDate = $dateTo ? 
            Carbon::parse($dateTo)->endOfDay() : 
            Carbon::createFromFormat('Y-m-d', "$currentYear-12-31")->endOfDay();

        // Get Payments
        $payments = Payment::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])
        ->whereBetween('purchase_date', [$startDate, $endDate]);

        // Apply search if provided
        if ($search) {
            $payments->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('code_name', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhere(function($q) use ($search) {
                          // Search for payment type (cash/other)
                          if (stripos('cash', $search) !== false) {
                              $q->where('is_cash', true);
                          } elseif (stripos('other', $search) !== false) {
                              $q->where('is_cash', false);
                          }
                          // Search for guide/personal money
                          if (stripos('guide', $search) !== false) {
                              $q->where('guide_money', true);
                          } elseif (stripos('personal', $search) !== false) {
                              $q->where('guide_money', false);
                          }
                      });
            });
        }

        $payments = $payments->orderBy('purchase_date', 'DESC')
                            ->paginate(intval($limit));

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

            $payments = Payment::whereBetween('purchase_date', [$from, $to])
                ->orderByRaw('CAST(purchase_date as DATETIME) ASC')
                ->get();
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
