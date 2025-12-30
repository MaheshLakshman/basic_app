<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'test@yopmail.com',
        ],
        [
            'name' => 'Test User',
            'email' => 'test@yopmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            LanguageSeeder::class,
        ]);
    }
}
