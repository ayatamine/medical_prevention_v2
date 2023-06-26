<?php

namespace Database\Seeders;

use App\Models\Symptome;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SymptomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Symptome::factory(5)->create();
    }
}
