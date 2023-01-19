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
            'name' => 'ali',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $admin->assignRole('admin');
        $subadmin = User::create([
            'name' => 'ali',
            'email' => 'subadmin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $subadmin->assignRole('subadmin');
    }
}