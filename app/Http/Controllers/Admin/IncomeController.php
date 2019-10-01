<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Income;
use App\BankAccount;

class IncomeController extends Controller
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
     * Show all the incomming money.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incomes  = Income::all()->sortByDesc("id");

        return view('admin.income.index')->with([
            'incomes' => $incomes
        ]);
    }

    /**
     *  Return a view to create an income
     */
    public function create()
    {
        return view('admin.income.create');
    }

    /**
     *  Stores a income in the DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'date' => 'required|date',
        ]);

        // Create Income
        $i = new Income();
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->save();

        return redirect()->route('admin.incomes.index');
    }

    /**
     *  Return a view to edit an income
     */
    public function edit($id)
    {
        $income = Income::findOrFail($id);

        return view('admin.income.edit')->with([
            'income' => $income,
        ]);
    }

    /**
     *  Updates a income in the DB
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'date' => 'required|date',
        ]);

        // Update Income
        $i = Income::findOrFail($id);
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->save();

        return redirect()->route('admin.incomes.index');
    }

    /**
     * Remove the income from DB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $i = Income::find($id);
        $i->delete();
        $request->session()->flash('alert-success', $i->title . ' has been deleted');
        return redirect()->route('admin.incomes.index');
    }

    /**
     * Change income approval status.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        // Get income
        $income = Income::findOrFail($id);

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
        $income->approved = !$income->approved;
        $income->save();

        // Add to bank account
        $bankBalance = BankAccount::where('title', 'Main')->first();
        $bankBalance->balance += $income->amount;
        $bankBalance->save();

        return redirect()->route('admin.incomes.index');
    }

    
}
