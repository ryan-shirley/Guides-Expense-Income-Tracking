<?php

use Illuminate\Database\Seeder;
use App\BankAccount;

class FixBankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldBankAccounts = BankAccount::all();

        foreach ($oldBankAccounts as $oldBankAccount) {
            $newBankAccount = new BankAccount();
            $newBankAccount->title = $oldBankAccount->title;
            $newBankAccount->balance = $oldBankAccount->balance;
            $newBankAccount->save();

            $oldBankAccount->delete();
        }

    }
}
