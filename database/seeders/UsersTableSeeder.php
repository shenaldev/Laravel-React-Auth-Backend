<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'fname' => 'admin',
            'lname' => 'j',
            'email' => 'admin@auth.com',
            'password' => Hash::make('pass'),
        ]);

        UserRole::create([
            'user_id' => $admin->id,
            'role_id' => 1,
        ]);

        $user = User::create([
            'fname' => 'user',
            'lname' => 'k',
            'email' => 'user@auth.com',
            'password' => Hash::make('pass'),
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => 2,
        ]);

    }
}
