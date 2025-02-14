<?php

namespace App\Http\Controllers\Leader;

use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Role;
use Auth;

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
        $this->middleware('auth');
        $this->middleware('role:leader');
    }

    /**
     *  Return a view to create a payment
     */
    public function create()
    {
        return view('leader.payment.create');
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
            'receipt_image' => 'image'
        ]);

        // Create Payment
        $p = new Payment();
        $p->title = $request->input('title');
        $p->description = $request->input('description');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = boolval($request->input('guide_money'));
        if($p->guide_money === false) {
            $p->is_cash = true;
        } else {
            $p->is_cash = boolval($request->input('is_cash'));
        }
        if($p->guide_money === true) {
            $p->paid_back = true;
        } else {
            $p->paid_back = boolval($request->input('paid_back'));
        }

        $p->approved = false;
        $p->user_id = Auth::user()->id;
        $p->ref_id = $p->generateReadableId();
        $p->save();

        $paymentId = Payment::where('ref_id', $p->ref_id)->first()->_id;
        $this->SaveReceipt($request->receipt_image, $p->ref_id, $paymentId);

        $request->session()->flash('alert-success', $p->title . ' payment has been added.');
        return redirect()->route('leader.home');
    }

    /**
     *  Return a view to edit a payment
     */
    public function edit(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->receipt_url = $this->GetReceiptUrl($payment->ref_id, $payment->_id);
        $user = Auth::user();

        // Check if leader own this payment
        if($payment->user->id !== $user->id) {
            $request->session()->flash('alert-error', 'You do not own this payment! Please do not try this again.');
            return back();
        }

        return view('leader.payment.edit')->with([
            'payment' => $payment
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
            'receipt_image' => 'image'
        ]);

        // Update Payment
        $p = Payment::findOrFail($id);
        $user = Auth::user();

        // Check if leader own this payment or is in accounts
        if($p->user->id !== $user->id) {
            $request->session()->flash('alert-error', 'You do not own this payment! Please do not try this again.');
            return redirect()->route('leader.home');
        }
        else if($p->in_accounts === 1) {
            $request->session()->flash('alert-error', 'You can not alter a payment after it has been added to accounts! Please do not try this again.');
            return redirect()->route('leader.home');
        }

        $p->title = $request->input('title');
        $p->description = $request->input('description');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = boolval($request->input('guide_money'));

        // Check type of money used to determin if needs to be paid back.
        if($p->guide_money) {
            $p->paid_back = true;
        }
        else {
            $p->paid_back = false;
        }

        $p->save();

        $paymentId = Payment::where('ref_id', $p->ref_id)->first()->_id;
        $this->SaveReceipt($request->receipt_image, $p->ref_id, $paymentId);

        $request->session()->flash('alert-success', $p->title . ' payment has been updated.');
        return redirect()->route('leader.home');
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
        $user = Auth::user();

        // Check if leader own this payment or not in accounts
        if($p->user->id !== $user->id) {
            $request->session()->flash('alert-error', 'You do not own this payment! Please do not try this again.');
            return back();
        }
        else if($p->in_accounts === 1) {
            $request->session()->flash('alert-error', 'You can not delete a payment after it has been added to accounts! Please do not try this again.');
            return back();
        }

        $p->delete();

        $request->session()->flash('alert-success', $p->title . ' payment has been deleted');
        return redirect()->route('leader.home');
    }

}
