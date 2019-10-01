<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;
use App\Role;
use App\BankAccount;
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
        $payments  = Payment::all()->sortByDesc("id");

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
            'user_id' => 'required|exists:users,id',
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
        $p->save();

        return redirect()->route('admin.payments.index');
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
            'user_id' => 'required|exists:users,id',
        ]);

        // Create Payment
        $p = Payment::findOrFail($id);
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');
        $p->paid_back = $request->input('paid_back');
        $p->user_id = $request->input('user_id');
        $p->save();

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
        $request->session()->flash('alert-success', $p->title . ' has been deleted');
        return redirect()->route('admin.payments.index');
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

        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('POST', 'https://hooks.zapier.com/hooks/catch/4854411/o25u35d/', [
        //     'json' => json_decode(json_encode($payment), true)
        // ]);

        // // Check if error saving to Google Drive
        // if($response->getStatusCode() !== 200) {
        //     $request->session()->flash('alert-error', 'Oops something went wrong! Please try again later.');
        //     return back();
        // }
        
        // Save 
        $payment->approved = !$payment->approved;
        $payment->save();

        // Take out of bank account
        $bankBalance = BankAccount::where('title', 'Main')->first();
        $bankBalance->balance -= $payment->amount;
        $bankBalance->save();

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

    
}
