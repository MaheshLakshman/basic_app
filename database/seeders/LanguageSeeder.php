<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Language::updateOrCreate(['code' => 'en'], ['name' => 'English', 'is_active' => true]);
        \App\Models\Language::updateOrCreate(['code' => 'ar'], ['name' => 'Arabic', 'is_active' => true]);
    }
}
