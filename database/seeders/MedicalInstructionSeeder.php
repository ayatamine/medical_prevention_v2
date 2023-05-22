<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalInstruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MedicalInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        return MedicalInstruction::factory(3)->create();
    }
}
