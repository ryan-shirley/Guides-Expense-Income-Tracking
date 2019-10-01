<?php

use Illuminate\Database\Seeder;
use App\BankAccount;

class BankAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment = new BankAccount();
        $payment->title = 'Main';
        $payment->balance = 2000;
        $payment->save();
    }
}
