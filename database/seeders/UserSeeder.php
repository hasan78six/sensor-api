<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users with password 'test@123'
        User::factory(10)->create([
            'password' => Hash::make('test@123'),
        ]);
    }
}