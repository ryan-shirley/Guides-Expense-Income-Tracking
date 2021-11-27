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
            $newBankAccount->created_at = $oldBankAccount->created_at;
            $newBankAccount->updated_at = $oldBankAccount->updated_at;
            $newBankAccount->save();

            $oldBankAccount->delete();
        }

    }
}
