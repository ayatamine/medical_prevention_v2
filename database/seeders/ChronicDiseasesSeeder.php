<?php

namespace Database\Seeders;

use App\Models\ChronicDiseases;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChronicDiseasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChronicDiseases::factory(5)->create();
    }
}
