<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Role;
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
     * Show the payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payment.show')->with([
            'payment' => $payment
        ]);
    }

    /**
     *  Return a view to create a payment
     */
    public function create()
    {
        $role_leader = Role::where('name', 'leader')->first();

        return view('admin.payment.create')->with([
            'leaders' => $role_leader->users
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
            'in_accounts' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
        ]);

        // Create Payment
        $p = new Payment();
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');
        $p->paid_back = $request->input('paid_back');
        $p->in_accounts = $request->input('in_accounts');
        $p->user_id = $request->input('user_id');
        $p->save();

        return redirect()->route('admin.home');
    }

    /**
     *  Return a view to edit a payment
     */
    public function edit($id)
    {
        $role_leader = Role::where('name', 'leader')->first();
        $payment = Payment::findOrFail($id);

        return view('admin.payment.edit')->with([
            'payment' => $payment,
            'leaders' => $role_leader->users
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
            'in_accounts' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
        ]);

        // Create Payment
        $p = Payment::findOrFail($id);
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');
        $p->paid_back = $request->input('paid_back');
        $p->in_accounts = $request->input('in_accounts');
        $p->user_id = $request->input('user_id');
        $p->save();

        return redirect()->route('admin.home');
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
        $request->session()->flash('alert-success', $p->title . ' has been deleted');
        return redirect()->route('admin.home');
    }

    /**
     * Change paid back status.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePaymentStatus($id)
    {
        // Get User
        $user = Auth::user();

        // Change paid back status
        $payment = Payment::findOrFail($id);
        $payment->paid_back = !$payment->paid_back;
        $payment->save();

        return redirect()->route('admin.home');
    }

    /**
     * Change account status.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeAccountStatus(Request $request, $id)
    {
        // Get User
        $user = Auth::user();

        // Create data and convert amount into negative as expense
        $payment = Payment::findOrFail($id);
        $payment->amount = -1 * abs($payment->amount);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://hooks.zapier.com/hooks/catch/4854411/o25u35d/', [
            'json' => json_decode(json_encode($payment), true)
        ]);

        // Check if error saving to Google Drive
        if($response->getStatusCode() !== 200) {
            $request->session()->flash('alert-error', 'Oops something went wrong! Please try again later.');
            return back();
        }
        
        // Save 
        $payment->amount = abs($payment->amount);
        $payment->in_accounts = !$payment->in_accounts;
        $payment->save();

        return redirect()->route('admin.home');
    }
        
}
