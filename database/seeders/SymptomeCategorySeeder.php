<?php

namespace Database\Seeders;

use App\Models\Symptome;
use Illuminate\Database\Seeder;
use App\Models\SymptomeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SymptomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SymptomeCategory::create([
            'name'=>'common health issues' ,
            'name_ar'=>'أكثر الاعراض المعروفة'
        ]);
        SymptomeCategory::create([
            'name'=>'General Physician' ,
            'name_ar'=>'فيزيائي عام'
        ]);
        SymptomeCategory::create([
            'name'=>'Orthopedist' ,
            'name_ar'=>' متخصص عظام'
        ]);
        
        Symptome::whereNull('symptome_category_id')
        ->update(['symptome_category_id'=>random_int(1,3)]);
    }
}
