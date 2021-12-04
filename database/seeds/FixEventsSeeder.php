<?php

use Illuminate\Database\Seeder;
use App\Event;
use App\Income;
use App\Payment;

class FixEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get events
        $allEvents = Event::all();

        foreach ($allEvents as $oldEvent) {    
            // Create new events
            $newEvent = new Event();
            $newEvent->title = $oldEvent->title;
            $newEvent->start_date = $oldEvent->start_date;
            $newEvent->end_date = $oldEvent->end_date;
            $newEvent->created_at = $oldEvent->created_at;
            $newEvent->updated_at = $oldEvent->updated_at;
            $newEvent->save();

            // Fix events in income
            Income::where('event_id', '==', $oldEvent->id)->update(array('event_id' => $newEvent->id));
            
            // Fix events in payments
            Payment::where('event_id', '==', $oldEvent->id)->update(array('event_id' => $newEvent->id));

            $oldEvent->delete();
        }
    }
}
