<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Carl Llemos',
            'email' => 'super@admin.com',
            'username' => 'superadmin01',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        User::factory()->create([
            'name' => 'Kim Mariano',
            'email' => 'admin@admin.com',
            'username' => 'admin01',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
