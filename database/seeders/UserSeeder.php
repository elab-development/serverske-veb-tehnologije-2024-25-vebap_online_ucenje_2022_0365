<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(3)->state(['role' => 'instructor'])->create();
        User::factory()->count(10)->state(['role' => 'user'])->create();
        User::factory()->count(1)->state(['role' => 'admin'])->create();
    }
}
