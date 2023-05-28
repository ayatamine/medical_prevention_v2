<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //log message
        $this->command->info('Consultation Seeder Started');
        \App\Models\Consultation::factory(5)->create();

        //log message
        $this->command->info('Consultation Seeder Ended');
    }
}
