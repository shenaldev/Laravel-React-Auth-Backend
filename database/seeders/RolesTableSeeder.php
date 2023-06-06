<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            'name' => 'Admin',
            'slug' => Str::slug('Admin'),
        ]);

        Role::create([
            'name' => 'User',
            'slug' => Str::slug('user'),
        ]);

    }
}
