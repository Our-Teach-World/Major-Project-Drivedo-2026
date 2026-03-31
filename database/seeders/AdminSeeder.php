<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('admin')->insert([
            'username' => 'admin',
            'password' => '$2y$12$sYA2Sc1J.jU0gliUS1taUeS6ouRnwQpRLnuGjyWh59/VaRXL7MsX2', // admin123
        ]);
    }
}
