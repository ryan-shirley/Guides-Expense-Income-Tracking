<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageHandler;
use App\Traits\UsePaymentsEnricher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Role;
use App\BankAccount;
use App\Event;
use Auth;

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
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Show the payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments  = Payment::orderBy('purchase_date', 'desc')->paginate(15)->onEachSide(2);

        return view('admin.payment.index')->with([
            'payments' => $this->Enrich($payments),
            'user' => Auth::user(),
        ]);
    }

    /**
     *  Return a view to create a payment
     */
    public function create()
    {
        $role_leader = Role::where('name', 'leader')->first();
        $events = Event::all();

        return view('admin.payment.create')->with([
            'leaders' => $role_leader->users,
            'events' => $events
        ]);
    }

    /**
     *  Stores a payment in the DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'description' => 'required|string|max:65535',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'purchase_date' => 'required|date',
            'guide_money' => 'required|boolean',
            'paid_back' => 'required|boolean',
            'user_id' => 'required|exists:users,_id',
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
            'event_id' => 'exclude_if:event_id,0|exists:events,_id',
            'receipt_image' => 'image'
        ]);

        // Create Payment
        $p = new Payment();
        $p->title = $request->input('title');
        $p->description = $request->input('description');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = boolval($request->input('guide_money'));
        $p->paid_back = boolval($request->input('paid_back'));
        $p->approved = false;
        $p->user_id = $request->input('user_id');
        $p->code = $request->input('code');
        $p->is_cash = boolval($request->input('is_cash'));

        if($request->input('event_id') !== '0') {
            $p->event_id = $request->input('event_id');
        } else {
            $p->event_id = null;
        }

        $p->ref_id = $p->generateReadableId();
        $p->save();

        $paymentId = Payment::where('ref_id', $p->ref_id)->first()->_id;
        $this->SaveReceipt($request->receipt_image, $p->ref_id, $paymentId);

        $request->session()->flash('alert-success', $p->title . ' payment has been added.');
        return redirect()->route('admin.payments.index');
    }

    /**
     *  Return a view to edit a payment
     */
    public function edit($id)
    {
        $role_leader = Role::where('name', 'leader')->first();
        $payment = Payment::findOrFail($id);
        $payment->receipt_url = $this->GetReceiptUrl($payment->ref_id, $payment->_id);
        $events = Event::all();

        return view('admin.payment.edit')->with([
            'payment' => $payment,
            'leaders' => $role_leader->users,
            'events' => $events
        ]);
    }

    /**
     *  Updates a payment in the DB
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'description' => 'required|string|max:65535',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'purchase_date' => 'required|date',
            'guide_money' => 'required|boolean',
            'paid_back' => 'required|boolean',
            'user_id' => 'required|exists:users,_id',
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
            'event_id' => 'exclude_if:event_id,0|exists:events,_id',
            'receipt_image' => 'image'
        ]);

        // Update Payment
        $p = Payment::findOrFail($id);
        $p->description = $request->input('description');
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = boolval($request->input('guide_money'));
        $p->paid_back = boolval($request->input('paid_back'));
        $p->user_id = $request->input('user_id');
        $p->code = $request->input('code');
        $p->is_cash = boolval($request->input('is_cash'));

        if($request->input('event_id') !== '0') {
            $p->event_id = $request->input('event_id');
        } else {
            $p->event_id = null;
        }

        $p->save();

        $paymentId = Payment::where('ref_id', $p->ref_id)->first()->_id;
        $this->SaveReceipt($request->receipt_image, $p->ref_id, $paymentId);

        $request->session()->flash('alert-success', $p->title . ' payment has been updated.');
        return redirect()->route('admin.payments.index');
    }

    /**
     * Remove the payment from DB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $p = Payment::find($id);
        $p->delete();
        $request->session()->flash('alert-success', $p->title . ' payment has been deleted');
        return redirect()->route('admin.payments.index');
    }

    /**
     * Change paid back status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function paidBack(Request $request, $id)
    {
        // Get User
        $user = Auth::user();

        // Change paid back status
        $payment = Payment::findOrFail($id);
        $payment->paid_back = true;
        $payment->save();

        $request->session()->flash('alert-success', $payment->title . ' payment has been marked as paid.');
        return redirect()->route('admin.payments.index');
    }

    /**
     * Change account status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        // Mark Approved
        $payment = Payment::findOrFail($id);
        $payment->approved = true;
        $payment->save();

        // Take out of bank account
        $bankBalance = BankAccount::where('title', 'Main')->first();
        $bankBalance->balance -= $payment->amount;
        $bankBalance->save();

        $request->session()->flash('alert-success', $payment->title . ' payment has been approved!');
        return redirect()->route('admin.payments.index');
    }

    /**
     * Mark payment as received receipt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receivedReceipt(Request $request, $id)
    {
        // Get User
        $user = Auth::user();

        // Create data and convert amount into negative as expense
        $payment = Payment::findOrFail($id);

        // Save
        $payment->receipt_received = true;
        $payment->save();

        $request->session()->flash('alert-success', $payment->title . ' payment has been marked as received receipt.');
        return redirect()->route('admin.payments.index');
    }

    /**
     * List of people that need to be paid back
     *
     * @return \Illuminate\Http\Response
     */
    public function toPayBack()
    {
        $paymentsToBePaidBack = Payment::where('paid_back', false)->get();
        $leadersToPayBack = $this->groupAndSumPayments($paymentsToBePaidBack);

        return view('admin.payment.toBePaidBack')->with([
            'leadersToPayBack' => $leadersToPayBack
        ]);
    }

    private function groupAndSumPayments($array) {
        // Group
        $groups = array();
        foreach ( $array as $value ) {
            $groups[$value['user_id']][] = $value;
        }

        // Sum
        $groupSum = array();
        foreach ( $groups as $group ) {
            $userSum = array_sum(array_column($group, 'amount'));
            $userId = $group[0]->user_id;

            $groupSum[$userId] = $userSum;
        }

        return $groupSum;
    }

    /**
     * Show payments exporter.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $columns = array(
            array(
                'data' => 'purchase_date',
                'title' => 'Date'
            ),
            array(
                'data' => 'title',
                'title' => 'Store Name'
            ),
            array(
                'data' => 'keyID',
                'title' => 'Ref'
            ),
            array(
                'data' => 'cash_only',
                'title' => 'Cash Only'
            ),
            array(
                'data' => 'other',
                'title' => 'Other'
            ),
            array(
                'data' => 'code',
                'title' => 'Code'
            ),
       );

        return view('admin.payment.export')->with([
            'user' => Auth::user(),
            'columns' => json_encode($columns)
        ]);
    }


}
