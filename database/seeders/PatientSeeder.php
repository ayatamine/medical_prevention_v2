<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('start seeding patient');
       \App\Models\Patient::create([
            'full_name'=>'Ali Mahmoud',
            'phone_number'=>'+213645871259',
            'age'=>50,
       ]);
       $this->command->info('finish seeding patient');
    }
}
