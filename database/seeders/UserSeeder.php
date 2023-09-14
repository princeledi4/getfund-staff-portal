<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Prince Awagah',
            'email' => 'admin@getfund.gov.gh',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
