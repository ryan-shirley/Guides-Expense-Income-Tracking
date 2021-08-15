<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Payment;

class FixUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Users
        $users =  User::all();
        foreach ($users as $oldUser) {
            // Create new mongo user
            $newUser = new User();
            $newUser->name = $oldUser->name;
            $newUser->email = $oldUser->email;
            $newUser->password = $oldUser->password;
            $newUser->api_token = md5(uniqid($oldUser->email, true));
            $newUser->approved_at = $oldUser->approved_at;
            $newUser->save();
            
            // Migrate payments to new user and delete old
            $userPayments = $oldUser->payments;
            foreach ($userPayments as $oldPayment) {
                $newPayment = new Payment();
                $newPayment->title = $oldPayment->title;
                $newPayment->amount = $oldPayment->amount;
                $newPayment->purchase_date = $oldPayment->purchase_date;
                $newPayment->guide_money = $oldPayment->guide_money;
                $newPayment->paid_back = $oldPayment->paid_back;
                $newPayment->approved = $oldPayment->approved;
                $newPayment->receipt_received = $oldPayment->receipt_received;
                $newPayment->user_id = $newUser->id;
                $newPayment->code = $oldPayment->code;
                $newPayment->is_cash = $oldPayment->is_cash;
                $newPayment->event_id = $oldPayment->event_id;

                $newPayment->save();

                $oldPayment->delete();
            }
        
            // Delete old user
            $oldUser->delete();
        }
    }
}
