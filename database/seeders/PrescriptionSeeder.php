<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //log message
        $this->command->info('Prescripiton Seeder Started');

        \App\Models\Prescription::create([
            'drug_name'=>'drug name 1',
            'route'=>'tablet',
            'dose'=>1,
            'unit'=>'unit',
            'frequancy'=>'1 times per day',
            'duration'=>1,
            'duration_unit'=>'week',
            'instructions'=>'some other instructions here',
            'doctor_id'=>Doctor::firstOrFail()?->id ?? Doctor::factory(1)->create()->id
        ]);
        \App\Models\Prescription::create([
            'drug_name'=>'drug name 2',
            'route'=>'tablet',
            'dose'=>2,
            'unit'=>'unit',
            'frequancy'=>'2 times per day',
            'duration'=>2,
            'duration_unit'=>'day',
            'instructions'=>'some other instructions here',
            'doctor_id'=>Doctor::firstOrFail()?->id ?? Doctor::factory(1)->create()->id
        ]);
        //log message
        $this->command->info('prescription Seeder finished');
    }
}
