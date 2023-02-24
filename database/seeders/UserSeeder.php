<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin User
        $admin = User::create([
            'name'      => 'Admin',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('testing123'),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name'      => 'User',
            'email'     => 'user@gmail.com',
            'password'  => bcrypt('testing123'),
        ]);
        $user->assignRole('user');
    }
}
