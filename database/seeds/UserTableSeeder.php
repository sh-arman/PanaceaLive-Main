<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->truncate();

        $data = [
            'email' => 'admin@panacealive.com',
            'name' => 'Panacea Admin',
            'password' => 'panacearocks',
            'phone_number' => '8801711111111',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $user = Sentinel::register($data, true);
        $role = Sentinel::findRoleByName('Admin');
        $role->users()->attach($user);
    }
}
