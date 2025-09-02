<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(4)
            ->state(new Sequence(
                ['is_admin' => 1],
                ['is_admin' => 0],
                ['is_manager' => 1],
                ['is_manager' => 0],
            ))->create();
    }
}
