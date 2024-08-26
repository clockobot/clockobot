<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->createOne([
            'name' => 'Clockobot',
            'is_admin' => 1,
            'email' => 'clockobot@clockobot.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'notifications' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // User::factory(50)->create();
    }
}
