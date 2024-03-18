<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BankTransaction;
use Auth;
use Carbon\Carbon;

class BankTransactionsController extends Controller
{
    /**
     * Display a listing of the transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', "$year-01-01")->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', "$year-12-31")->endOfDay();
        $transactions = BankTransaction::whereBetween('date', [$startDate, $endDate])->orderBy("id")->get();

        return view('admin.bank-transactions.index')->with([
            'transactions' => $transactions,
            'year' => $year
        ]);
    }

    /**
     * Show the form for creating a new transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($year)
    {
        return view('admin.bank-transactions.create')->with([
            'year' => $year
        ]);;
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $year)
    {
        $request->validate([
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'date' => 'required|date',
            'is_logement' => 'boolean'
        ]);

        // Create Transaction
        $tx = new BankTransaction();
        $tx->amount = $request->input('amount');
        $tx->date = $request->input('date');
        $tx->is_logement = boolval($request->input('is_logement'));
        $tx->save();

        $request->session()->flash('alert-success', 'Transaction has been added.');
        return redirect()->route('admin.bank-transactions.index', $year);
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($year, $id)
    {
        $tx = BankTransaction::findOrFail($id);

        return view('admin.bank-transactions.edit')->with([
            'transaction' => $tx,
            'year' => $year
        ]);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $year, $id)
    {
        $request->validate([
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'date' => 'required|date',
            'is_logement' => 'boolean'
        ]);

        // Update Transaction
        $tx = BankTransaction::findOrFail($id);
        $tx->amount = $request->input('amount');
        $tx->date = $request->input('date');
        $tx->is_logement = boolval($request->input('is_logement'));
        $tx->save();

        $request->session()->flash('alert-success', 'Transaction has been updated.');
        return redirect()->route('admin.bank-transactions.index', $year);
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($year, $id, Request $request)
    {
        $tx = BankTransaction::find($id);
        $tx->delete();

        $request->session()->flash('alert-success', 'Transaction has been deleted');
        return redirect()->route('admin.bank-transactions.index', $year);
    }

    /**
     * Show bank transactions exporter.
     *
     * @return \Illuminate\Http\Response
     */
    public function export($year)
    {
        $columns = array(
            array(
                'data' => 'date',
                'title' => 'Date'
            ),
            array(
                'data' => 'logements',
                'title' => 'Logements'
            ),
            array(
                'data' => 'withdrawals',
                'title' => 'Withdrawals'
            ),
       );

        return view('admin.bank-transactions.export')->with([
            'user' => Auth::user(),
            'columns' => json_encode($columns),
            'year' => $year
        ]);
    }
}
