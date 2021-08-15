<?php

namespace App\Http\Controllers\API\leader;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:leader');
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
            'guide_money' => 'required|boolean'
        ]);

        // Create Payment
        $p = new Payment();
        $p->title = $request->input('title');
        $p->amount = $request->input('amount');
        $p->purchase_date = $request->input('purchase_date');
        $p->guide_money = $request->input('guide_money');

        // Check type of money used to determin if needs to be paid back.
        if($p->guide_money) {
            $p->paid_back = true;
        }
        else {
            $p->paid_back = false;
        }

        $p->approved = false;
        $p->user_id = $request->user()->id;
        $p->ref_id = $p->generateReadableId();
        $p->save();

        return $p;
    }

}
