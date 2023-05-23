<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Recommendation;
use Illuminate\Database\Seeder;
use App\Models\MedicalInstruction;
use App\Models\SubSpeciality;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        if(!Recommendation::count()){
            $this->call(RecommendationSeeder::class);
        }
        if(!MedicalInstruction::count()){
            $this->call(MedicalInstructionSeeder::class);
        }
        if(!SubSpeciality::count()){
            $this->call(SubSpecialitySeeder::class);
        }
    }
}
