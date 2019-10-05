<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_leader = Role::where('name', 'leader')->first();

        $admin = new User();
        $admin->name = 'Ryan';
        $admin->email = 'ryanjshirley@gmail.com';
        $admin->password = bcrypt('secret');
        $admin->approved_at = now();
        $admin->save();
        $admin->roles()->attach($role_admin);

        $admin2 = new User();
        $admin2->name = 'Emily Darlington';
        $admin2->email = 'emilyjane.darlington@gmail.com';
        $admin2->password = bcrypt('secret');
        $admin2->approved_at = now();
        $admin2->save();
        $admin2->roles()->attach($role_admin);

        $leader = new User();
        $leader->name = 'Leader Name';
        $leader->email = 'leader@example.com';
        $leader->password = bcrypt('secret');
        $leader->save();
        $leader->roles()->attach($role_leader);
    }
}
