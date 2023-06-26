<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Page;
use App\Models\Scale;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Language;
use App\Models\Symptome;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\Advertisement;
use App\Models\FamilyHistory;
use App\Models\ScaleQuestion;
use App\Models\SubSpeciality;
use App\Models\Recommendation;
use App\Models\BallanceHistory;
use App\Models\ChronicDiseases;
use Illuminate\Database\Seeder;
use App\Models\MedicalInstruction;

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
        // if(!SubSpeciality::count()){
        //     $this->call(SubSpecialitySeeder::class);
        // }
        // if(!Language::count()){
        //     $this->call(LanguageSeeder::class);
        // }
        // if(!Page::count()){
        //     $this->call(PageSeeder::class);
        // }
        if(!Advertisement::count()){
            $this->call(AdvertisementSeeder::class);
        }
        if(!FamilyHistory::count()){
            $this->call(FamilyHistorySeeder::class);
        }
        if(!ChronicDiseases::count()){
            $this->call(FamilyHistory::class);
        }
        if(!BallanceHistory::count()){
            $this->call(BallanceHistorySeeder::class);
        }
        if(!Patient::count()){
            $this->call(PatientSeeder::class);
        }
        if(!Consultation::count()){
            $this->call(ConsultationSeeder::class);
        }
        if(!Prescription::count()){
            $this->call(PrescriptionSeeder::class);
        }
        if(!LabTest::count()){
            $this->call(LabTestSeeder::class);
        }
        if(!Symptome::count()){
            $this->call(SymptomeSeeder::class);
        }
        if(!Scale::count()){
            $this->call(ScaleSeeder::class);
        }
        if(!ScaleQuestion::count()){
            $this->call(ScaleQuestionSeeder::class);
        }


    }
}
