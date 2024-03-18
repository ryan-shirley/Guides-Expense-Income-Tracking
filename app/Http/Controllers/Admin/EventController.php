<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;
use Auth;

class EventController extends Controller
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
     * Show the events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year)
    {
        $events = Event::all();

        return view('admin.events.index')->with([
            'events' => $events,
            'year' => $year
        ]);
    }

    /**
     *  Return a view to create an event
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     *  Return a view to show an individual event and incomes / expenses
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Format Payments ID
        $payments = $event->payments;
        foreach ($payments as $index => $payment) {
            $payments[$index]->keyID = "p_" . $payment->ref_id;
            
            if($payment->is_cash) {
                $payments[$index]->cash_only = $payment->amount;
                $payments[$index]->other = 0;
            } else {
                $payments[$index]->cash_only = 0;
                $payments[$index]->other = $payment->amount;
            }
        }

        // Format Incomes ID/ref
        $incomes = $event->incomes;
        foreach ($incomes as $index => $income) {
            $incomes[$index]->keyID = "i_" . $income->ref_id;

            if($income->is_cash) {
                $incomes[$index]->cash_and_cheque = $income->amount;
                $incomes[$index]->online = 0;
            } else {
                $incomes[$index]->cash_and_cheque = 0;
                $incomes[$index]->online = $income->amount;
            }
        }

        return view('admin.events.show')->with([
            'event' => $event,
            'incomes' => $incomes,
            'payments' => $payments,
        ]);
    }

    /**
     *  Stores an event in the DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Create Event
        $e = new Event();
        $e->title = $request->input('title');
        $e->start_date = $request->input('start_date');
        $e->end_date = $request->input('end_date');
        $e->save();

        $request->session()->flash('alert-success', $e->title . ' event has been added.');
        return redirect()->route('admin.events.index');
    }

    /**
     *  Return a view to edit an event
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.events.edit')->with([
            'event' => $event
        ]);
    }

    /**
     *  Updates an event in the DB
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:65535',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Update Event
        $e = Event::findOrFail($id);
        $e->title = $request->input('title');
        $e->start_date = $request->input('start_date');
        $e->end_date = $request->input('end_date');
        $e->save();

        $request->session()->flash('alert-success', $e->title . ' event has been updated.');
        return redirect()->route('admin.events.index');
    }

    /**
     * Remove the event from DB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // Find event
        $event = Event::findOrFail($id);

        // Delete only if no payments and no incomes associated
        if ($event->payments->count() == 0 && $event->incomes->count() == 0) {
            $event->delete();
            
            $request->session()->flash('alert-success', $event->title . ' event has been deleted');
            return redirect()->route('admin.events.index');
        }
        else {
            $request->session()->flash('alert-error', $event->title . ' has incomes/payments associated. Can not delete.');
            return redirect()->route('admin.events.index');
        }
    }
}
