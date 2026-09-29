<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoadTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            User::updateOrCreate([
                'email' => "user{$i}@test.com",
                'name' => "Test User{$i}", 'password' => Hash::make('password'),
            ]);
        }
    }
}
