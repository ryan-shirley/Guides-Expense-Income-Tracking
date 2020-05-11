<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Income;
use App\BankAccount;
use Auth;

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

        foreach ($incomes as $index => $income) {
            $incomes[$index]->keyID = "i_" . $income->id;

            if($income->is_cash) {
                $incomes[$index]->cash_and_cheque = $income->amount;
                $incomes[$index]->online = 0;
            } else {
                $incomes[$index]->cash_and_cheque = 0;
                $incomes[$index]->online = $income->amount;
            }
        }

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
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
        ]);

        // Create Income
        $i = new Income();
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->code = $request->input('code');
        $i->is_cash = $request->input('is_cash');
        $i->save();

        $request->session()->flash('alert-success', $i->title . ' income has been added.');
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
            'code' => 'string|max:65535',
            'is_cash' => 'boolean',
        ]);

        // Update Income
        $i = Income::findOrFail($id);
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->code = $request->input('code');
        $i->is_cash = $request->input('is_cash');
        $i->save();

        $request->session()->flash('alert-success', $i->title . ' income has been updated.');
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
        $request->session()->flash('alert-success', $i->title . ' income has been deleted');
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

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env("ZAPIER_WEBHOOK_INCOME"), [
            'json' => json_decode(json_encode($income), true)
        ]);

       // Check if error saving to Google Drive
       if($response->getStatusCode() !== 200) {
            $request->session()->flash('alert-error', 'Oops something went wrong! ' . $request->getBody());
            return redirect()->route('admin.incomes.index');
        }
        
        // Save 
        $income->approved = !$income->approved;
        $income->save();

        // Add to bank account
        $bankBalance = BankAccount::where('title', 'Main')->first();
        $bankBalance->balance += $income->amount;
        $bankBalance->save();

        $request->session()->flash('alert-success', $income->title . ' income has been approved.');
        return redirect()->route('admin.incomes.index');
    }

    /**
     * Show incomes exporter.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return view('admin.income.export')->with([
            'user' => Auth::user()
        ]);
    }
    
}
