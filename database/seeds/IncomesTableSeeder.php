<?php

use Illuminate\Database\Seeder;
use App\Income;
use App\User;

class IncomesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment = new Income();
        $payment->title = 'Fees';
        $payment->amount = 1000;
        $payment->date = '2019/09/25';
        $payment->save();

        $payment2 = new Income();
        $payment2->title = 'Grant';
        $payment2->amount = 550;
        $payment2->date = '2019/09/26';
        $payment2->save();
    }
}
