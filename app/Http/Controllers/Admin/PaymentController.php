<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Role;
use App\BankAccount;
use App\Event;
use Auth;
use Guzzle\Http\Client;

class PaymentController extends Controller
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
     * Show the payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments  = Payment::all();

        // Format Payments ID
        foreach ($payments as $index => $payment) {
            $payments[$index]->keyID = "p_" . $payment->id;
            
            if($payment->is_cash) {
                $payments[$index]->cash_only = $payment->amount;
                $payments[$index]->other = 0;
            } else {
                $payments[$index]->cash_only = 0;
                $payments[$index]->other = $payment->amount;
            }
        }

        return view('admin.payment.index')->with([
            'payments' => $payments
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
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'purchase_date' => 'required|date',
            'guide_money' => 'required|boolean',
            'paid_back' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
            'event_id' => 'exclude_if:event_id,0|exists:events,id'
        ]);

        // Create Payment
        $p = new Payment();
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');
        $p->paid_back = $request->input('paid_back');
        $p->approved = false;
        $p->user_id = $request->input('user_id');
        $p->code = $request->input('code');
        $p->is_cash = $request->input('is_cash');
        
        if($request->input('event_id') !== '0') {
            $p->event_id = $request->input('event_id');
        } else {
            $p->event_id = null;
        }

        $p->save();

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
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'purchase_date' => 'required|date',
            'guide_money' => 'required|boolean',
            'paid_back' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:65535',
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
            'event_id' => 'exclude_if:event_id,0|exists:events,id',
        ]);

        // Update Payment
        $p = Payment::findOrFail($id);
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');
        $p->paid_back = $request->input('paid_back');
        $p->user_id = $request->input('user_id');
        $p->code = $request->input('code');
        $p->is_cash = $request->input('is_cash');

        if($request->input('event_id') !== '0') {
            $p->event_id = $request->input('event_id');
        } else {
            $p->event_id = null;
        }

        $p->save();

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
        // Get User
        $user = Auth::user();

        // Create data and convert amount into negative as expense
        $payment = Payment::findOrFail($id);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env("ZAPIER_WEBHOOK_PAYMENT"), [
            'json' => json_decode(json_encode($payment), true)
        ]);

        // Check if error saving to Google Drive
        if($response->getStatusCode() !== 200) {
            $request->session()->flash('alert-error', 'Oops something went wrong! ' . $request->getBody());
            return redirect()->route('admin.payments.index');
        }
        
        // Save 
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
        $leadersToPayBack = Payment::where('paid_back', '0')->groupBy('user_id')
            ->selectRaw('sum(amount) as sum, user_id')
            ->pluck('sum','user_id');

        return view('admin.payment.toBePaidBack')->with([
            'leadersToPayBack' => $leadersToPayBack
        ]);
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
                'title' => 'Details'
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
