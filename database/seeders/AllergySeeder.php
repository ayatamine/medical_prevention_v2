<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        return Allergy::factory(2)->create();
    }
}
