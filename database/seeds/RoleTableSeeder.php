<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();

        $admin = DB::table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
        $role = Sentinel::findRoleByName('Admin');
        $role->addPermission('admin');
        $role->save();

        $user = DB::table('roles')->insert([
            'name' => 'User',
            'slug' => 'user',
        ]);
        $role = Sentinel::findRoleByName('User');
        $role->addPermission('user');
        $role->save();

        $company = DB::table('roles')->insert([
            'name' => 'Company',
            'slug' => 'company',
        ]);
        $role = Sentinel::findRoleByName('Company');
        $role->addPermission('company');
        $role->save();
    }
}
