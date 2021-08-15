<?php

use Illuminate\Database\Seeder;
use App\Income;

class FixIncomesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allIncomes = Income::all();

        foreach ($allIncomes as $oldIncome) {
            $newIncome = new Income();
            $newIncome->title = $oldIncome->title;
            $newIncome->amount = $oldIncome->amount;
            $newIncome->date = $oldIncome->date;
            $newIncome->approved = $oldIncome->approved;
            $newIncome->code = $oldIncome->code;
            $newIncome->is_cash = $oldIncome->is_cash;
            $newIncome->event_id = $oldIncome->event_id;
            $newIncome->save();

            $oldIncome->delete();
        }
    }
}
