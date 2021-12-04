<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(PaymentsTableSeeder::class);
        // $this->call(IncomesTableSeeder::class);
        // $this->call(BankAccountsTableSeeder::class);
        $this->call(FixUsersSeeder::class);
        $this->call(FixIncomesSeeder::class);
        $this->call(FixEventsSeeder::class);
        $this->call(FixBankAccountSeeder::class);
        $this->call(FixBankTransactionsSeeder::class);
    }
}
