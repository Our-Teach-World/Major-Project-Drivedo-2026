<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::updateOrCreate(
            ['username' => 'Principal'],
            [
                'email' => 'principal@campuscore.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'principal',
                'branch' => 'null'
            ]
        );
    }
}
