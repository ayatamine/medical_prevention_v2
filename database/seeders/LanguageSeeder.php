<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Language::create([
             'name' => 'Arabic',
            'label' => 'ar',
            'direction' => 'rtl',
            'status' => 0,
            'file' => 'ar.php'
       ]);
       Language::create([
             'name' => 'English',
            'label' => 'en',
            'direction' => 'ltr',
            'status' => 1,
            'file' => 'en.php'
       ]);
    }
}
