<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException(
                'Demo accounts must not be seeded in production.'
            );
        }

        User::updateOrCreate(
            ['email' => 'fps@pdstrust.test'],
            [
                'name' => 'Demo FPS Owner',
                'password' => Hash::make('DemoPass123!'),
                'role' => 'fps_owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'official@pdstrust.test'],
            [
                'name' => 'Demo Government Official',
                'password' => Hash::make('DemoPass123!'),
                'role' => 'government_official',
            ]
        );
    }
}