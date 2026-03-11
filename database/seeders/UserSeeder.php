<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'System Admin',
            'email' => 'admin@4h.com',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);
    }
}
