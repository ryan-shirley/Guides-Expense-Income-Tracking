<?php

use Illuminate\Database\Seeder;
use App\BankTransaction;

class FixBankTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allBankTransactions = BankTransaction::all();

        foreach ($allBankTransactions as $oldTransaction) {
            $newTransaction = new Income();
            $newTransaction->amount = $oldTransaction->amount;
            $newTransaction->date = $oldTransaction->date;
            $newTransaction->is_logement = $oldTransaction->is_logement;
            $newTransaction->created_at = $oldTransaction->created_at;
            $newTransaction->updated_at = $oldTransaction->updated_at;
            $newTransaction->save();

            $oldTransaction->delete();
        }
    }
}
