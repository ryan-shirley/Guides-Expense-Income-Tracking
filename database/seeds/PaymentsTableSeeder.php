<?php

use Illuminate\Database\Seeder;
use App\Payment;
use App\User;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leader = User::where('name', 'Leader Name')->first();

        $payment = new Payment();
        $payment->title = 'Juice';
        $payment->amount = 25.50;
        $payment->purchase_date = '2019/09/25';
        $payment->guide_money = false;
        $payment->paid_back = false;
        $payment->user_id = $leader->id;
        $payment->save();

        $payment2 = new Payment();
        $payment2->title = 'Kettle';
        $payment2->amount = 129.99;
        $payment2->purchase_date = '2019/09/27';
        $payment2->guide_money = true;
        $payment2->paid_back = true;
        $payment2->user_id = $leader->id;
        $payment2->save();
    }
}
