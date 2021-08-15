<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Income;
use App\BankAccount;
use App\Event;
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
        $events = Event::all();

        return view('admin.income.create')->with([
            'events' => $events
        ]);
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
            'event_id' => 'exclude_if:event_id,0|exists:events,_id'
        ]);

        // Create Income
        $i = new Income();
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->code = $request->input('code');
        $i->is_cash = $request->input('is_cash');

        if($request->input('event_id') !== '0') {
            $i->event_id = $request->input('event_id');
        } else {
            $i->event_id = null;
        }
        
        $i->ref_id = $i->generateReadableId();
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
        $events = Event::all();

        return view('admin.income.edit')->with([
            'income' => $income,
            'events' => $events
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
            'event_id' => 'exclude_if:event_id,0|exists:events,id'
        ]);

        // Update Income
        $i = Income::findOrFail($id);
        $i->title = $request->input('title');
        $i->amount = $request->input('amount');
        $i->date = $request->input('date');
        $i->code = $request->input('code');
        $i->is_cash = $request->input('is_cash');

        if($request->input('event_id') !== '0') {
            $i->event_id = $request->input('event_id');
        } else {
            $i->event_id = null;
        }

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
        // Mark income approved
        $income = Income::findOrFail($id);
        $income->approved = 1;
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
        $columns = array(
            array(
                'data' => 'date',
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
                'data' => 'cash_and_cheque',
                'title' => 'Cash & Cheque'
            ),
            array(
                'data' => 'online',
                'title' => 'Online Payments'
            ),
            array(
                'data' => 'code',
                'title' => 'Code'
            ),
       );

        return view('admin.income.export')->with([
            'user' => Auth::user(),
            'columns' => json_encode($columns)
        ]);
    }
    
}
