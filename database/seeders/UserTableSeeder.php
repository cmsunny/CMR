<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'first_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $admin->assignRole('admin');
        $subadmin = User::create([
            'first_name' => 'SubAdmin',
            'email' => 'subadmin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $subadmin->assignRole('subadmin');
    }
}
