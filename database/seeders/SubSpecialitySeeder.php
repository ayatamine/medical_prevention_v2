<?php

namespace Database\Seeders;

use App\Models\SubSpeciality;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubSpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        return SubSpeciality::factory(3)->create();
    }
}
