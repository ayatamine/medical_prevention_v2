<?php

namespace Database\Seeders;

use App\Models\ChronicDiseaseCategory;
use App\Models\ChronicDiseases;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChronicDiseaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ChronicDiseaseCategory::create([
        //     'name'=>'chronic disease ' ,
        //     'name_ar'=>'chronic disease ' 
        // ]);
        // ChronicDiseaseCategory::create([
        //     'name'=>'chronic disease 2' ,
        //     'name_ar'=>'chronic disese 2'
        // ]);
        // ChronicDiseaseCategory::create([
        //     'name'=>'chronic disease 3' ,
        //     'name_ar'=>'chronic disease 3' ,
        // ]);
        ChronicDiseases::whereNull('chronic_disease_category_id')
        ->update(['chronic_disease_category_id'=>random_int(1,3)]);
    }
}
